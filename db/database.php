<?php
class DatabaseHelper {
    private $db;

    public function __construct($servername, $username, $password, $dbname, $port) {
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connessione al db fallita.");
        }
    }

    /* TUTTI I POST PUBBLICATI */
    public function getAllPosts($filters = []) {
        $sql = "SELECT 
                    P.id, 
                    P.titolo, 
                    P.richiede_approvazione, 
                    P.tipo, 
                    P.descrizione AS post_descrizione, 
                    P.data_inizio, 
                    P.data_fine, 
                    P.luogo, 
                    P.data_creazione, 
                    P.max_partecipanti, 
                    P.utente_email, 
                    P.materia_id,
                    M.nome AS materia_nome,
                    U.nome AS creatore_nome,
                    U.cognome AS creatore_cognome,
                    COUNT(Part.utente_email) AS partecipanti_attuali
                FROM Post P
                INNER JOIN Utente U ON P.utente_email = U.email
                INNER JOIN Materia M ON P.materia_id = M.id
                LEFT JOIN Partecipazione Part ON P.id = Part.post_id";
        
        $whereConditions = [];
        $havingConditions = [];
        $params = [];
        $types = "";
        
        if (!empty($filters['exclude_user'])) {
            $whereConditions[] = "NOT EXISTS (SELECT 1 FROM Partecipazione Pp WHERE Pp.post_id = P.id AND Pp.utente_email = ?)";
            $params[] = $filters['exclude_user'];
            $types .= "s";
        }
        
        if (!empty($filters['subject'])) {
            $whereConditions[] = "M.nome = ?";
            $params[] = ucfirst($filters['subject']);
            $types .= "s";
        }

        if (!empty($filters['type'])) {
            $whereConditions[] = "P.tipo = ?";
            $params[] = $filters['type'];
            $types .= "s";
        }
        
        if (!empty($filters['date_from'])) {
            $whereConditions[] = "P.data_inizio >= ?";
            $params[] = $filters['date_from'];
            $types .= "s";
        }
        
        if (!empty($filters['no_auth'])) {
            $whereConditions[] = "P.richiede_approvazione = 0";
        }
        
        if (empty($filters['show_unavailable'])) {
            $whereConditions[] = "P.data_fine >= CURDATE()";
            $havingConditions[] = "partecipanti_attuali < P.max_partecipanti";
        }
        
        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(" AND ", $whereConditions);
        }
        
        $sql .= " GROUP BY P.id";
        
        if (!empty($havingConditions)) {
            $sql .= " HAVING " . implode(" AND ", $havingConditions);
        }
        
        $orderBy = "P.data_creazione DESC";
        if (!empty($filters['sort']) && $filters['sort'] === 'meno-recenti') {
            $orderBy = "P.data_creazione ASC";
        }
        $sql .= " ORDER BY " . $orderBy;
                
        $stmt = $this->db->prepare($sql);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();

        $posts = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $posts ?: [];
    }

    /* INFO DETTAGLIATE POST */
    public function getPostInfo($id) {
        $sql = "SELECT 
                    P.id, 
                    P.titolo, 
                    P.richiede_approvazione, 
                    P.tipo, 
                    P.descrizione AS post_descrizione, 
                    P.data_inizio, 
                    P.data_fine, 
                    P.luogo, 
                    P.data_creazione, 
                    P.max_partecipanti, 
                    P.utente_email, 
                    P.materia_id,
                    M.nome AS materia_nome,
                    U.nome AS creatore_nome,
                    U.cognome AS creatore_cognome,
                    COUNT(Part.utente_email) AS partecipanti_attuali
                FROM Post P
                INNER JOIN Utente U ON P.utente_email = U.email
                INNER JOIN Materia M ON P.materia_id = M.id
                LEFT JOIN Partecipazione Part ON P.id = Part.post_id
                WHERE P.id = ?
                GROUP BY P.id";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $postInfo = $result->fetch_assoc();
        $stmt->close();
        return $postInfo ?: null;
    }

    /* MATERIE DISPONIBILI */
    public function getAllMaterie() {
        $sql = "SELECT id, nome FROM Materia ORDER BY nome";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $materie = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $materie ?: [];
    }

    /* FILE ALLEGATI DI UN POST */
    public function getPostFiles($postId) {
        $sql = "SELECT id, nome, tipo, dimensione_byte FROM File WHERE post_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();

        $files = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $files ?: [];
    }

    /* FILE PER IL DOWNLOAD */
    public function getFileForDownload($fileId) {
        $sql = "SELECT nome, tipo, dimensione_byte, contenuto FROM File WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $fileId);
        $stmt->execute();
        $result = $stmt->get_result();

        $file = $result->fetch_assoc();
        $stmt->close();
        return $file ?: null;
    }

    /* RICERCA UTENTE PER EMAIL */
    public function getUserByEmail($email) {
        $sql = "SELECT email, nome, cognome, password, descrizione, tipo FROM Utente WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $user = $result->fetch_assoc();
        $stmt->close();
        return $user ?: null;
    }

    /* CREAZIONE NUOVO UTENTE */
    public function createUser($email, $nome, $cognome, $password, $descrizione = "") {
        $existingUser = $this->getUserByEmail($email);
        if ($existingUser) {
            return ['success' => false, 'message' => 'Email già registrata.'];
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO Utente (email, nome, cognome, password, descrizione, tipo) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $tipo = 'utente';
        
        $stmt->bind_param("ssssss", $email, $nome, $cognome, $hashedPassword, $descrizione, $tipo);
        
        try {
            $stmt->execute();
            return ['success' => true, 'message' => 'Utente creato con successo.'];
        } catch (mysqli_sql_exception $e) {
            error_log("Errore durante la creazione dell'utente: " . $e->getMessage());
            return ['success' => false, 'message' => 'Errore interno del server.'];
        } finally {
            $stmt->close();
        }
    }

    /* ELIMINA ACCOUNT UTENTE */
    public function deleteUser($email) {
        $this->db->begin_transaction();
        try {
            // 1. Elimina partecipazioni dell'utente ai post altrui
            $stmtPart = $this->db->prepare("DELETE FROM Partecipazione WHERE utente_email = ?");
            $stmtPart->bind_param("s", $email);
            $stmtPart->execute();
            $stmtPart->close();
            
            // 2. Trova i post dell'utente da eliminare a cascata
            $stmtGetPosts = $this->db->prepare("SELECT id FROM Post WHERE utente_email = ?");
            $stmtGetPosts->bind_param("s", $email);
            $stmtGetPosts->execute();
            $resPosts = $stmtGetPosts->get_result();
            
            while ($post = $resPosts->fetch_assoc()) {
                $postId = (int)$post['id']; // FIX: cast a int per sicurezza
                
                $this->db->query("DELETE FROM Partecipazione WHERE post_id = " . $postId);
                $this->db->query("DELETE FROM File WHERE post_id = " . $postId);
                $this->db->query("DELETE FROM Post WHERE id = " . $postId);
            }
            $stmtGetPosts->close();

            // 3. Infine, elimina l'utente
            $sql = "DELETE FROM Utente WHERE email = ? AND tipo = 'utente'";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $deleted = $stmt->affected_rows > 0;
            $stmt->close();
            
            $this->db->commit();
            return $deleted
                ? ['success' => true,  'message' => 'Account eliminato.']
                : ['success' => false, 'message' => 'Utente non trovato o indisponibile.'];
            
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Errore durante l'eliminazione dell'utente: " . $e->getMessage());
            return ['success' => false, 'message' => 'Errore durante l\'eliminazione.'];
        }
    }

    /* MODIFICA PROFILO UTENTE */
    public function updateUserProfile($oldEmail, $newEmail, $nome, $cognome, $descrizione) {
        if ($newEmail !== $oldEmail) {
            $existing = $this->getUserByEmail($newEmail);
            if ($existing) {
                return ['success' => false, 'message' => 'Email già registrata.', 'new_email' => null];
            }
        }
        
        $sql = "UPDATE Utente SET email = ?, nome = ?, cognome = ?, descrizione = ? WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            error_log("Prepare failed: " . $this->db->error);
            return ['success' => false, 'message' => 'Errore interno del server.', 'new_email' => null];
        }
        
        $stmt->bind_param("sssss", $newEmail, $nome, $cognome, $descrizione, $oldEmail);
        
        try {
            $stmt->execute();
            return [
                'success'   => true,
                'message'   => 'Profilo aggiornato con successo!',
                'new_email' => $newEmail
            ];
        } catch (mysqli_sql_exception $e) {
            error_log("Errore update profilo: " . $e->getMessage());
            return ['success' => false, 'message' => 'Errore durante l\'aggiornamento.', 'new_email' => null];
        } finally {
            $stmt->close();
        }
    }

    /* CREAZIONE NUOVO POST */
    public function createPost($postData, $files = []) {
        $this->db->begin_transaction();
        try {
            // 1. Insert Post
            $sql = "INSERT INTO Post (titolo, richiede_approvazione, tipo, descrizione, data_inizio, data_fine, luogo, max_partecipanti, utente_email, materia_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            
            $richiedeApprovazione = !empty($postData['richiede_approvazione']) ? 1 : 0;
            $dataFine = !empty($postData['data_fine']) ? $postData['data_fine'] : null;
            $descrizione = $postData['descrizione'] ?? '';
            
            $tipoMap = ['session' => 'sessione', 'project' => 'progettuale'];
            $tipo = $tipoMap[$postData['tipo']] ?? $postData['tipo'];
            
            $stmt->bind_param("sisssssisi", 
                $postData['titolo'],
                $richiedeApprovazione,
                $tipo,
                $descrizione,
                $postData['data_inizio'],
                $dataFine,
                $postData['luogo'],
                $postData['max_partecipanti'],
                $postData['utente_email'],
                $postData['materia_id']
            );
            
            $stmt->execute();
            $postId = $this->db->insert_id;
            $stmt->close();

            if (!$postId) throw new Exception("Insert post fallito");

            // 2. Auto-partecipazione
            $partStmt = $this->db->prepare("INSERT INTO Partecipazione (utente_email, post_id) VALUES (?, ?)");
            $partStmt->bind_param("si", $postData['utente_email'], $postId);
            $partStmt->execute();
            $partStmt->close();

            // 3. Upload File
            if (!empty($files['name'])) {
                $names   = is_array($files['name'])     ? $files['name']     : [$files['name']];
                $mimeTypes = is_array($files['type'])   ? $files['type']     : [$files['type']]; // FIX: rinominato da $types a $mimeTypes
                $sizes   = is_array($files['size'])     ? $files['size']     : [$files['size']];
                $tmps    = is_array($files['tmp_name']) ? $files['tmp_name'] : [$files['tmp_name']];
                $errors  = is_array($files['error'])    ? $files['error']    : [$files['error']];

                $fileStmt = $this->db->prepare("INSERT INTO File (nome, tipo, dimensione_byte, contenuto, post_id) VALUES (?, ?, ?, ?, ?)");

                for ($i = 0; $i < count($names); $i++) {
                    if ($errors[$i] !== UPLOAD_ERR_OK) continue;
                    
                    $fileName = basename($names[$i]);
                    if (empty($fileName)) continue;
                    
                    $fileSize = (int)$sizes[$i];
                    $fileType = $mimeTypes[$i];
                    $fileTmp  = $tmps[$i];
                    
                    $content = @file_get_contents($fileTmp);
                    if ($content === false || strlen($content) === 0) continue;

                    try {
                        $fileStmt->bind_param("ssisi", $fileName, $fileType, $fileSize, $content, $postId);
                        $fileStmt->execute();
                    } catch (mysqli_sql_exception $e) {
                        continue;
                    }
                }
                $fileStmt->close();
            }

            $this->db->commit();
            return $postId;

        } catch (Exception $e) {
            $this->db->rollback();
            return 0;
        }
    }

    /* AGGIORNA DATI POST E FILE */
    public function updatePost($postId, $userEmail, $postData, $filesToAdd = [], $fileIdsToDelete = []) {
        $this->db->begin_transaction();
        try {
            $stmt = $this->db->prepare("SELECT id FROM Post WHERE id = ? AND utente_email = ?");
            $stmt->bind_param("is", $postId, $userEmail);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                $stmt->close();
                $this->db->rollback();
                return ['success' => false, 'message' => 'Post non trovato o non hai i permessi per modificarlo.'];
            }
            $stmt->close();

            $sql = "UPDATE Post SET luogo = ?, data_inizio = ?, data_fine = ?, richiede_approvazione = ?, descrizione = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            
            $dataFine = !empty($postData['data_fine']) ? $postData['data_fine'] : null;
            $richiedeApprovazione = !empty($postData['richiede_approvazione']) ? 1 : 0;
            
            $stmt->bind_param("sssisi", 
                $postData['luogo'],
                $postData['data_inizio'],
                $dataFine,
                $richiedeApprovazione,
                $postData['descrizione'],
                $postId
            );
            $stmt->execute();
            $stmt->close();

            if (!empty($fileIdsToDelete)) {
                $placeholders = implode(',', array_fill(0, count($fileIdsToDelete), '?'));
                $delSql = "DELETE FROM File WHERE post_id = ? AND id IN ($placeholders)";
                $delStmt = $this->db->prepare($delSql);
                
                $bindTypes = "i" . str_repeat('i', count($fileIdsToDelete)); // FIX: rinominato da $types a $bindTypes
                $params = array_merge([$postId], $fileIdsToDelete);
                $delStmt->bind_param($bindTypes, ...$params);
                $delStmt->execute();
                $delStmt->close();
            }

            if (!empty($filesToAdd['name']) && !empty($filesToAdd['name'][0])) {
                $names     = is_array($filesToAdd['name'])     ? $filesToAdd['name']     : [$filesToAdd['name']];
                $mimeTypes = is_array($filesToAdd['type'])     ? $filesToAdd['type']     : [$filesToAdd['type']]; // FIX: rinominato da $types a $mimeTypes
                $sizes     = is_array($filesToAdd['size'])     ? $filesToAdd['size']     : [$filesToAdd['size']];
                $tmps      = is_array($filesToAdd['tmp_name']) ? $filesToAdd['tmp_name'] : [$filesToAdd['tmp_name']];
                $errors    = is_array($filesToAdd['error'])    ? $filesToAdd['error']    : [$filesToAdd['error']];

                $fileStmt = $this->db->prepare("INSERT INTO File (nome, tipo, dimensione_byte, contenuto, post_id) VALUES (?, ?, ?, ?, ?)");

                for ($i = 0; $i < count($names); $i++) {
                    if ($errors[$i] !== UPLOAD_ERR_OK) continue;
                    
                    $fileName = basename($names[$i]);
                    if (empty($fileName)) continue;
                    
                    $fileSize = (int)$sizes[$i];
                    $fileType = $mimeTypes[$i];
                    $fileTmp  = $tmps[$i];
                    
                    $content = @file_get_contents($fileTmp);
                    if ($content === false || strlen($content) === 0) continue;

                    try {
                        $fileStmt->bind_param("ssisi", $fileName, $fileType, $fileSize, $content, $postId);
                        $fileStmt->execute();
                    } catch (mysqli_sql_exception $e) {
                        continue;
                    }
                }
                $fileStmt->close();
            }

            $this->db->commit();
            return ['success' => true, 'message' => 'Post aggiornato con successo.'];

        } catch (Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Errore interno durante l\'aggiornamento.'];
        }
    }

    /* POST CREATI DALL'UTENTE */
    public function getMyPosts($email) {
        $sql = "SELECT 
                    P.id, 
                    P.titolo, 
                    P.richiede_approvazione, 
                    P.tipo, 
                    P.descrizione AS post_descrizione, 
                    P.data_inizio, 
                    P.data_fine, 
                    P.luogo, 
                    P.data_creazione, 
                    P.max_partecipanti, 
                    P.utente_email, 
                    P.materia_id,
                    M.nome AS materia_nome,
                    COUNT(DISTINCT Part.utente_email) AS partecipanti_attuali
                FROM Post P
                INNER JOIN Materia M ON P.materia_id = M.id
                LEFT JOIN Partecipazione Part ON P.id = Part.post_id
                WHERE P.utente_email = ?
                GROUP BY P.id
                ORDER BY P.data_creazione DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $posts = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        foreach ($posts as &$post) {
            $post['files'] = $this->getPostFiles((int)$post['id']);
        }
        
        return $posts ?: [];
    }

    /* VERIFICA PARTECIPAZIONE */
    public function isPartecipante($email, $postId) {
        $sql = "SELECT 1 FROM Partecipazione WHERE utente_email = ? AND post_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $email, $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        $found = $result->num_rows > 0;
        $stmt->close();
        return $found;
    }

    /* PARTECIPAZIONE AD UN POST */
    public function partecipa($email, $postId) {
        if ($this->isPartecipante($email, $postId)) {
            return ['success' => false, 'message' => 'Partecipi già a questo post.'];
        }

        $postInfo = $this->getPostInfo($postId);
        if (!$postInfo) {
            return ['success' => false, 'message' => 'Post non trovato.'];
        }

        if ($postInfo['utente_email'] === $email) {
            return ['success' => false, 'message' => 'Sei il creatore di questo post, non serve iscriverti!'];
        }

        $attuali = (int)($postInfo['partecipanti_attuali'] ?? 0);
        $max     = (int)($postInfo['max_partecipanti']     ?? 0);
        if ($attuali >= $max) {
            return ['success' => false, 'message' => 'Il post ha raggiunto il numero massimo di partecipanti.'];
        }

        $sql = "INSERT INTO Partecipazione (utente_email, post_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $email, $postId);

        try {
            $stmt->execute();
            return ['success' => true, 'message' => 'Partecipazione registrata con successo!'];
        } catch (mysqli_sql_exception $e) {
            error_log("Errore partecipazione: " . $e->getMessage());
            return ['success' => false, 'message' => 'Errore durante la partecipazione.'];
        } finally {
            $stmt->close();
        }
    }

    /* POST A CUI L'UTENTE PARTECIPA */
    public function getJoinedPosts($email) {
        // FIX: stringa SQL non chiusa, aggiunto GROUP BY e ORDER BY mancanti
        $sql = "SELECT 
                    P.id, 
                    P.titolo, 
                    P.richiede_approvazione, 
                    P.tipo, 
                    P.descrizione AS post_descrizione, 
                    P.data_inizio, 
                    P.data_fine, 
                    P.luogo, 
                    P.data_creazione, 
                    P.max_partecipanti, 
                    P.utente_email, 
                    P.materia_id,
                    M.nome AS materia_nome,
                    U.nome AS creatore_nome,
                    U.cognome AS creatore_cognome,
                    COUNT(DISTINCT Part.utente_email) AS partecipanti_attuali
                FROM Post P
                INNER JOIN Materia M ON P.materia_id = M.id
                INNER JOIN Utente U ON P.utente_email = U.email
                INNER JOIN Partecipazione MyPart ON P.id = MyPart.post_id AND MyPart.utente_email = ?
                LEFT JOIN Partecipazione Part ON P.id = Part.post_id
                GROUP BY P.id
                ORDER BY P.data_creazione DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $posts = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        foreach ($posts as &$post) {
            $post['files'] = $this->getPostFiles((int)$post['id']);
        }

        return $posts ?: [];
    }

    /* ELIMINA POST */
    public function deletePost($postId, $userEmail) {
        $this->db->begin_transaction();
        try {
            $stmt = $this->db->prepare("SELECT id FROM Post WHERE id = ? AND utente_email = ?");
            $stmt->bind_param("is", $postId, $userEmail);
            $stmt->execute();
            if ($stmt->get_result()->num_rows === 0) {
                $this->db->rollback();
                $stmt->close();
                return ['success' => false, 'message' => 'Post non trovato o non hai i permessi.'];
            }
            $stmt->close();

            $this->db->query("DELETE FROM Partecipazione WHERE post_id = " . (int)$postId);
            $this->db->query("DELETE FROM File WHERE post_id = "           . (int)$postId);
            $this->db->query("DELETE FROM Post WHERE id = "                . (int)$postId);

            $this->db->commit();
            return ['success' => true, 'message' => 'Post eliminato con successo.'];
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Errore eliminazione post: " . $e->getMessage());
            return ['success' => false, 'message' => 'Errore interno del server durante eliminazione.'];
        }
    }

    /* ABBANDONA POST (ESCI DALLA PARTECIPAZIONE) */
    public function lasciaPost($email, $postId) {
        $checkStmt = $this->db->prepare("SELECT utente_email FROM Post WHERE id = ?");
        $checkStmt->bind_param("i", $postId);
        $checkStmt->execute();
        $ownerResult = $checkStmt->get_result()->fetch_assoc();
        $checkStmt->close();

        if ($ownerResult && $ownerResult['utente_email'] === $email) {
            return ['success' => false, 'message' => 'Non puoi uscire da una sessione creata da te.'];
        }

        if (!$this->isPartecipante($email, $postId)) {
            return ['success' => false, 'message' => 'Non partecipi a questo post.'];
        }

        $sql = "DELETE FROM Partecipazione WHERE utente_email = ? AND post_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $email, $postId);
        
        try {
            $stmt->execute();
            return ['success' => true, 'message' => 'Hai abbandonato il post con successo.'];
        } catch (Exception $e) {
            error_log("Errore abbandono post: " . $e->getMessage());
            return ['success' => false, 'message' => 'Errore di sistema. Riprova più tardi.'];
        } finally {
            $stmt->close();
        }
    }

    /* OTTENERE I COMMENTI DI UN POST */
    public function getComments($postId) {
        $sql = "SELECT 
                    C.id, C.testo, C.data_scrittura, C.risposta_id, C.utente_email,
                    U.nome AS creatore_nome, U.cognome AS creatore_cognome,
                    R.testo AS risposta_testo, R.data_scrittura AS risposta_data,
                    RU.nome AS risposta_autore_nome, RU.cognome AS risposta_autore_cognome
                FROM Commento C
                INNER JOIN Utente U ON C.utente_email = U.email
                LEFT JOIN Commento R ON C.risposta_id = R.id
                LEFT JOIN Utente RU ON R.utente_email = RU.email
                WHERE C.post_id = ?
                ORDER BY C.data_scrittura ASC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $comments = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        return $comments ?: [];
    }

    /* AGGIUNGERE UN COMMENTO A UN POST */
    public function addComment($postId, $userEmail, $testo, $rispostaId = null) {
        $sql = "INSERT INTO Commento (testo, utente_email, post_id, risposta_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssii", $testo, $userEmail, $postId, $rispostaId);
        
        try {
            $stmt->execute();
            return ['success' => true, 'message' => 'Commento aggiunto.'];
        } catch (Exception $e) {
            error_log("Errore aggiunta commento: " . $e->getMessage());
            return ['success' => false, 'message' => 'Errore interno del server.'];
        } finally {
            $stmt->close();
        }
    }
}
?>