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
                    P.tipo, 
                    P.descrizione AS post_descrizione, 
                    P.data_inizio, 
                    P.data_fine, 
                    P.luogo, 
                    P.data_creazione, 
                    P.max_partecipanti, 
                    P.utente_id, 
                    P.materia_id,
                    M.nome AS materia_nome,
                    U.nome AS creatore_nome,
                    U.cognome AS creatore_cognome,
                    COUNT(DISTINCT Part.utente_id) AS partecipanti_attuali
                FROM Post P
                INNER JOIN Utente U ON P.utente_id = U.id
                INNER JOIN Materia M ON P.materia_id = M.id
                LEFT JOIN Partecipazione Part ON P.id = Part.post_id";
        
        $whereConditions = [];
        $havingConditions = [];
        $params = [];
        $types = "";
        
        // Escludi post a cui l'utente già partecipa
        if (!empty($filters['exclude_user_id'])) {
            $whereConditions[] = "NOT EXISTS (SELECT 1 FROM Partecipazione Pp WHERE Pp.post_id = P.id AND Pp.utente_id = ?)";
            $params[] = $filters['exclude_user_id'];
            $types .= "i";
        }

        // Escludi post creati dall'utente stesso (discovery)
        if (!empty($filters['not_owner_id'])) {
            $whereConditions[] = "P.utente_id != ?";
            $params[] = $filters['not_owner_id'];
            $types .= "i";
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

        if (!empty($filters['search'])) {
            $whereConditions[] = "P.titolo LIKE ?";
            $params[] = "%" . $filters['search'] . "%";
            $types .= "s";
        }
        

        if (empty($filters['show_unavailable'])) {
            $whereConditions[] = "P.data_fine >= CURDATE()";
            $havingConditions[] = "partecipanti_attuali < P.max_partecipanti";
        }
        
        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(" AND ", $whereConditions);
        }
        
        $sql .= " GROUP BY P.id, P.titolo, P.tipo, P.descrizione, P.data_inizio, P.data_fine, P.luogo, P.data_creazione, P.max_partecipanti, P.utente_id, P.materia_id, M.nome, U.nome, U.cognome";
        
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
                    P.*, 
                    M.nome AS materia_nome,
                    U.nome AS creatore_nome,
                    U.cognome AS creatore_cognome,
                    COUNT(DISTINCT Part.utente_id) AS partecipanti_attuali
                FROM Post P
                INNER JOIN Utente U ON P.utente_id = U.id
                INNER JOIN Materia M ON P.materia_id = M.id
                LEFT JOIN Partecipazione Part ON P.id = Part.post_id
                WHERE P.id = ?
                GROUP BY P.id, M.nome, U.nome, U.cognome";
                
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
        $sql = "SELECT id, email, nome, cognome, password, descrizione, tipo FROM Utente WHERE email = ?";
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
            $newId = $this->db->insert_id;
            return ['success' => true, 'message' => 'Utente creato con successo.', 'user_id' => $newId];
        } catch (mysqli_sql_exception $e) {
            error_log("Errore durante la creazione dell'utente: " . $e->getMessage());
            return ['success' => false, 'message' => 'Errore interno del server.'];
        } finally {
            $stmt->close();
        }
    }

    /* ELIMINA ACCOUNT UTENTE */
    public function deleteUser($userId) {
        $this->db->begin_transaction();
        try {
            // Eliminiamo l'utente. Il trigger TRG_User_Anonymize nel DB si occuperà di:
            // 1. Riassegnare i suoi Post all'ID 1 (deleted@system.it)
            // 2. Riassegnare i suoi Commenti all'ID 1
            // 3. Eliminare le sue Partecipazioni
            $sql = "DELETE FROM Utente WHERE id = ? AND tipo = 'utente'";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $deleted = $stmt->affected_rows > 0;
            $stmt->close();
            
            $this->db->commit();
            return $deleted
                ? ['success' => true,  'message' => 'Account e relativi dati eliminati con successo.']
                : ['success' => false, 'message' => 'Utente non trovato o indisponibile.'];
            
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Errore durante l'eliminazione dell'utente ID $userId: " . $e->getMessage());
            return ['success' => false, 'message' => 'Errore durante l\'eliminazione.'];
        }
    }

    /* MODIFICA PROFILO UTENTE */
    public function updateUserProfile($userId, $newEmail, $nome, $cognome, $descrizione) {
        // Verifica se la nuova email è già presa da un ALTRO utente
        $sqlCheck = "SELECT id FROM Utente WHERE email = ? AND id != ?";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->bind_param("si", $newEmail, $userId);
        $stmtCheck->execute();
        if ($stmtCheck->get_result()->num_rows > 0) {
            $stmtCheck->close();
            return ['success' => false, 'message' => 'Email già registrata da un altro utente.', 'new_email' => null];
        }
        $stmtCheck->close();
        
        $sql = "UPDATE Utente SET email = ?, nome = ?, cognome = ?, descrizione = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            error_log("Prepare failed: " . $this->db->error);
            return ['success' => false, 'message' => 'Errore interno del server.', 'new_email' => null];
        }
        
        $stmt->bind_param("ssssi", $newEmail, $nome, $cognome, $descrizione, $userId);
        
        try {
            $stmt->execute();
            return [
                'success'   => true,
                'message'   => 'Profilo aggiornato con successo!',
                'new_email' => $newEmail
            ];
        } catch (mysqli_sql_exception $e) {
            error_log("Errore update profilo per ID $userId: " . $e->getMessage());
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
            $sql = "INSERT INTO Post (titolo, tipo, descrizione, data_inizio, data_fine, luogo, max_partecipanti, utente_id, materia_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            
            $dataFine = !empty($postData['data_fine']) ? $postData['data_fine'] : null;
            $descrizione = $postData['descrizione'] ?? '';
            
            $tipoMap = ['session' => 'sessione', 'project' => 'progettuale'];
            $tipo = $tipoMap[$postData['tipo']] ?? $postData['tipo'];
            
            $stmt->bind_param("ssssssiii", 
                $postData['titolo'],
                $tipo,
                $descrizione,
                $postData['data_inizio'],
                $dataFine,
                $postData['luogo'],
                $postData['max_partecipanti'],
                $postData['utente_id'],
                $postData['materia_id']
            );
            
            $stmt->execute();
            $postId = $this->db->insert_id;
            $stmt->close();

            if (!$postId) throw new Exception("Insert post fallito");

            // 2. Auto-partecipazione
            $partStmt = $this->db->prepare("INSERT INTO Partecipazione (utente_id, post_id) VALUES (?, ?)");
            $partStmt->bind_param("ii", $postData['utente_id'], $postId);
            $partStmt->execute();
            $partStmt->close();

            // 3. Gestione File
            if (!empty($files['name']) && !empty($files['name'][0])) {
                $names     = is_array($files['name'])     ? $files['name']     : [$files['name']];
                $mimeTypes = is_array($files['type'])     ? $files['type']     : [$files['type']];
                $sizes     = is_array($files['size'])     ? $files['size']     : [$files['size']];
                $tmps      = is_array($files['tmp_name']) ? $files['tmp_name'] : [$files['tmp_name']];
                $errors    = is_array($files['error'])    ? $files['error']    : [$files['error']];

                $fileStmt = $this->db->prepare("INSERT INTO File (nome, tipo, dimensione_byte, contenuto, post_id) VALUES (?, ?, ?, ?, ?)");
                
                for ($i = 0; $i < count($names); $i++) {
                    if ($errors[$i] !== UPLOAD_ERR_OK) continue;
                    
                    $fileName = basename($names[$i]);
                    if (empty($fileName)) continue;
                    
                    $fileSize = (int)$sizes[$i];
                    $fileType = $mimeTypes[$i];
                    $fileTmp  = $tmps[$i];
                    
                    $content = @file_get_contents($fileTmp);
                    if ($content === false) continue;

                    $fileStmt->bind_param("ssisi", $fileName, $fileType, $fileSize, $content, $postId);
                    $fileStmt->execute();
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
    public function updatePost($postId, $userId, $postData, $filesToAdd = [], $fileIdsToDelete = [], $userIdsToKick = []) {
        $this->db->begin_transaction();
        try {
            // Verifica permessi: proprietario del post OPPURE admin
            $stmt = $this->db->prepare("SELECT utente_id FROM Post WHERE id = ?");
            $stmt->bind_param("i", $postId);
            $stmt->execute();
            $post = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$post) {
                $this->db->rollback();
                return ['success' => false, 'message' => 'Post non trovato.'];
            }

            // Controllo tipo utente chiamante
            $stmt = $this->db->prepare("SELECT tipo FROM Utente WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $caller = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            $isOwner = ($post['utente_id'] == $userId);
            $isAdmin = ($caller && $caller['tipo'] === 'admin');

            if (!$isOwner && !$isAdmin) {
                $this->db->rollback();
                return ['success' => false, 'message' => 'Non hai i permessi per modificare questo post.'];
            }


            $sql = "UPDATE Post SET luogo = ?, data_inizio = ?, data_fine = ?, descrizione = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            
            $dataFine = !empty($postData['data_fine']) ? $postData['data_fine'] : null;
            
            $stmt->bind_param("ssssi", 
                $postData['luogo'],
                $postData['data_inizio'],
                $dataFine,
                $postData['descrizione'],
                $postId
            );
            $stmt->execute();
            $stmt->close();

            // Rimozione File
            if (!empty($fileIdsToDelete)) {
                $placeholders = implode(',', array_fill(0, count($fileIdsToDelete), '?'));
                $delSql = "DELETE FROM File WHERE post_id = ? AND id IN ($placeholders)";
                $delStmt = $this->db->prepare($delSql);
                
                $bindTypes = "i" . str_repeat('i', count($fileIdsToDelete));
                $params = array_merge([$postId], $fileIdsToDelete);
                $delStmt->bind_param($bindTypes, ...$params);
                $delStmt->execute();
                $delStmt->close();
            }

            // Rimozione Partecipanti (Kicking)
            if (!empty($userIdsToKick)) {
                $placeholders = implode(',', array_fill(0, count($userIdsToKick), '?'));
                $kickSql = "DELETE FROM Partecipazione WHERE post_id = ? AND utente_id IN ($placeholders)";
                $kickStmt = $this->db->prepare($kickSql);
                
                $bindTypes = "i" . str_repeat('i', count($userIdsToKick));
                $params = array_merge([$postId], $userIdsToKick);
                $kickStmt->bind_param($bindTypes, ...$params);
                $kickStmt->execute();
                $kickStmt->close();
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
                    if ($content === false) continue;

                    $fileStmt->bind_param("ssisi", $fileName, $fileType, $fileSize, $content, $postId);
                    $fileStmt->execute();
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
    public function getMyPosts($userId) {
        $sql = "SELECT 
                    P.id, 
                    P.titolo, 
                    P.tipo, 
                    P.descrizione AS post_descrizione, 
                    P.data_inizio, 
                    P.data_fine, 
                    P.luogo, 
                    P.data_creazione, 
                    P.max_partecipanti, 
                    P.utente_id, 
                    P.materia_id,
                    M.nome AS materia_nome,
                    COUNT(DISTINCT Part.utente_id) AS partecipanti_attuali
                FROM Post P
                INNER JOIN Materia M ON P.materia_id = M.id
                LEFT JOIN Partecipazione Part ON P.id = Part.post_id
                WHERE P.utente_id = ?
                GROUP BY P.id, M.nome
                ORDER BY P.data_creazione DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
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
    public function isPartecipante($userId, $postId) {
        $sql = "SELECT 1 FROM Partecipazione WHERE utente_id = ? AND post_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $userId, $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        $found = $result->num_rows > 0;
        $stmt->close();
        return $found;
    }

    /* PARTECIPAZIONE AD UN POST */
    public function partecipa($userId, $postId) {
        if ($this->isPartecipante($userId, $postId)) {
            return ['success' => false, 'message' => 'Partecipi già a questo post.'];
        }

        $postInfo = $this->getPostInfo($postId);
        if (!$postInfo) {
            return ['success' => false, 'message' => 'Post non trovato.'];
        }

        if ($postInfo['utente_id'] == $userId) {
            return ['success' => false, 'message' => 'Sei il creatore di questo post, non serve iscriverti!'];
        }

        $attuali = (int)($postInfo['partecipanti_attuali'] ?? 0);
        $max     = (int)($postInfo['max_partecipanti']     ?? 0);
        if ($attuali >= $max) {
            return ['success' => false, 'message' => 'Il post ha raggiunto il numero massimo di partecipanti.'];
        }

        $sql = "INSERT INTO Partecipazione (utente_id, post_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $userId, $postId);

        try {
            $stmt->execute();
            return ['success' => true, 'message' => 'Partecipazione registrata con successo!'];
        } catch (mysqli_sql_exception $e) {
            error_log("Errore partecipazione ID $userId al post $postId: " . $e->getMessage());
            return ['success' => false, 'message' => 'Errore durante la partecipazione.'];
        } finally {
            $stmt->close();
        }
    }

    /* POST A CUI L'UTENTE PARTECIPA */
    public function getJoinedPosts($userId) {
        $sql = "SELECT 
                    P.id, 
                    P.titolo, 
                    P.tipo, 
                    P.descrizione AS post_descrizione, 
                    P.data_inizio, 
                    P.data_fine, 
                    P.luogo, 
                    P.data_creazione, 
                    P.max_partecipanti, 
                    P.utente_id, 
                    P.materia_id,
                    M.nome AS materia_nome,
                    U.nome AS creatore_nome,
                    U.cognome AS creatore_cognome,
                    COUNT(DISTINCT Part.utente_id) AS partecipanti_attuali
                FROM Post P
                INNER JOIN Materia M ON P.materia_id = M.id
                INNER JOIN Utente U ON P.utente_id = U.id
                INNER JOIN Partecipazione MyPart ON P.id = MyPart.post_id AND MyPart.utente_id = ?
                LEFT JOIN Partecipazione Part ON P.id = Part.post_id
                GROUP BY P.id, M.nome, U.nome, U.cognome
                ORDER BY P.data_creazione DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
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
    public function deletePost($postId, $userId) {
        $this->db->begin_transaction();
        try {
            $stmt = $this->db->prepare("SELECT id FROM Post WHERE id = ? AND utente_id = ?");
            $stmt->bind_param("ii", $postId, $userId);
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
    public function lasciaPost($userId, $postId) {
        $checkStmt = $this->db->prepare("SELECT utente_id FROM Post WHERE id = ?");
        $checkStmt->bind_param("i", $postId);
        $checkStmt->execute();
        $ownerResult = $checkStmt->get_result()->fetch_assoc();
        $checkStmt->close();

        if ($ownerResult && $ownerResult['utente_id'] == $userId) {
            return ['success' => false, 'message' => 'Non puoi uscire da una sessione creata da te.'];
        }

        if (!$this->isPartecipante($userId, $postId)) {
            return ['success' => false, 'message' => 'Non partecipi a questo post.'];
        }

        $sql = "DELETE FROM Partecipazione WHERE utente_id = ? AND post_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $userId, $postId);
        
        try {
            $stmt->execute();
            return ['success' => true, 'message' => 'Hai abbandonato il post con successo.'];
        } catch (Exception $e) {
            error_log("Errore abbandono post ID $postId da parte di ID $userId: " . $e->getMessage());
            return ['success' => false, 'message' => 'Errore di sistema. Riprova più tardi.'];
        } finally {
            $stmt->close();
        }
    }

    /* OTTENERE I COMMENTI DI UN POST */
    public function getComments($postId) {
        $sql = "SELECT 
                    C.id, C.testo, C.data_scrittura, C.risposta_id, C.utente_id,
                    U.nome AS creatore_nome, U.cognome AS creatore_cognome,
                    R.testo AS risposta_testo, R.data_scrittura AS risposta_data,
                    RU.nome AS risposta_autore_nome, RU.cognome AS risposta_autore_cognome
                FROM Commento C
                INNER JOIN Utente U ON C.utente_id = U.id
                LEFT JOIN Commento R ON C.risposta_id = R.id
                LEFT JOIN Utente RU ON R.utente_id = RU.id
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
    public function addComment($postId, $userId, $testo, $rispostaId = null) {
        $sql = "INSERT INTO Commento (testo, utente_id, post_id, risposta_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("siii", $testo, $userId, $postId, $rispostaId);
        
        try {
            $stmt->execute();
            return ['success' => true, 'message' => 'Commento aggiunto.'];
        } catch (Exception $e) {
            error_log("Errore aggiunta commento per ID $userId al post $postId: " . $e->getMessage());
            return ['success' => false, 'message' => 'Errore interno del server.'];
        } finally {
            $stmt->close();
        }
    }
    /* OTTENERE I PARTECIPANTI DI UN POST (ESCLUSO IL CREATORE) */
    public function getPostParticipants($postId) {
        $sql = "SELECT U.id, U.nome, U.cognome, U.email
                FROM Partecipazione P
                INNER JOIN Utente U ON P.utente_id = U.id
                INNER JOIN Post Post ON P.post_id = Post.id
                WHERE P.post_id = ? AND U.id != Post.utente_id
                ORDER BY U.nome ASC, U.cognome ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $participants = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        return $participants ?: [];
    }

    /* RIMOZIONE PARTECIPANTE DA PARTE DEL PROPRIETARIO */
    public function removeParticipantByOwner($ownerId, $postId, $targetUserId) {
        // Verifica che il chiamante sia il proprietario del post OPPURE un admin
        $checkStmt = $this->db->prepare("SELECT utente_id FROM Post WHERE id = ?");
        $checkStmt->bind_param("i", $postId);
        $checkStmt->execute();
        $res = $checkStmt->get_result()->fetch_assoc();
        $checkStmt->close();

        // Check if caller is admin
        $caller = $this->getUserById($ownerId);
        $isAdmin = ($caller && $caller['tipo'] === 'admin');

        if (!$res || ($res['utente_id'] != $ownerId && !$isAdmin)) {
            return ['success' => false, 'message' => 'Non hai i permessi per rimuovere partecipanti da questo post.'];
        }

        if ($targetUserId == $ownerId) {
            return ['success' => false, 'message' => 'Non puoi rimuovere te stesso (creatore) tramite questa funzione.'];
        }

        $sql = "DELETE FROM Partecipazione WHERE utente_id = ? AND post_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $targetUserId, $postId);
        
        try {
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                return ['success' => true, 'message' => 'Utente rimosso con successo.'];
            } else {
                return ['success' => false, 'message' => 'L\'utente non partecipava a questo post.'];
            }
        } catch (Exception $e) {
            error_log("Errore rimozione partecipante ID $targetUserId dal post $postId: " . $e->getMessage());
            return ['success' => false, 'message' => 'Errore interno del server.'];
        } finally {
            $stmt->close();
        }
    }

    /* --- METODI PER AMMINISTRAZIONE --- */

    /**
     * Recupera tutti gli utenti di tipo 'utente' (esclude altri admin)
     */
    public function getUsersForAdmin() {
        $sql = "SELECT id, email, nome, cognome, descrizione FROM Utente WHERE tipo = 'utente' ORDER BY email ASC";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Recupera tutti i post della piattaforma con i dati dei creatori
     */
    public function getPostsForAdmin() {
        $sql = "SELECT P.id, P.titolo, U.nome AS creatore_nome, U.cognome AS creatore_cognome, U.email AS creatore_email
                FROM Post P
                INNER JOIN Utente U ON P.utente_id = U.id
                ORDER BY P.data_creazione DESC";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Elimina un utente dal punto di vista dell'admin (salta vincoli di proprietà)
     * Include protezione per non eliminare altri admin
     */
    public function deleteUserByAdmin($userId) {
        $check = $this->db->prepare("SELECT tipo FROM Utente WHERE id = ?");
        $check->bind_param("i", $userId);
        $check->execute();
        $res = $check->get_result()->fetch_assoc();
        $check->close();

        if (!$res || $res['tipo'] === 'admin') {
            return ['success' => false, 'message' => 'Impossibile eliminare l\'utente: l\'ID non esiste o è un amministratore.'];
        }

        $stmt = $this->db->prepare("DELETE FROM Utente WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $success = $stmt->execute();
        $stmt->close();

        return $success 
            ? ['success' => true, 'message' => 'Utente eliminato con successo.'] 
            : ['success' => false, 'message' => 'Errore durante l\'eliminazione dell\'utente dal database.'];
    }

    /**
     * Elimina un post dal punto di vista dell'admin
     */
    public function deletePostByAdmin($postId) {
        $stmt = $this->db->prepare("DELETE FROM Post WHERE id = ?");
        $stmt->bind_param("i", $postId);
        $success = $stmt->execute();
        $stmt->close();

        return $success 
            ? ['success' => true, 'message' => 'Post eliminato con successo dall\'amministratore.'] 
            : ['success' => false, 'message' => 'Errore durante l\'eliminazione del post.'];
    }

    /**
     * Recupera i dati di un utente specifico tramite ID (utile per admin_edit_user)
     */
    public function getUserById($userId) {
        $stmt = $this->db->prepare("SELECT id, email, nome, cognome, descrizione, tipo FROM Utente WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $res ?: null;
    }
}
?>