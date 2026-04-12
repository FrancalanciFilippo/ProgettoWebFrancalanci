<?php
class DatabaseHelper {
    private $db;

    public function __construct($servername, $username, $password, $dbname, $port) {
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connessione al db fallita.");
        }
    }

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
        
        if (!empty($filters['exclude_user_id'])) {
            $whereConditions[] = "NOT EXISTS (SELECT 1 FROM Partecipazione Pp WHERE Pp.post_id = P.id AND Pp.utente_id = ?)";
            $params[] = $filters['exclude_user_id'];
            $types .= "i";
        }

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

    public function getAllMaterie() {
        $sql = "SELECT id, nome FROM Materia ORDER BY nome";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $materie = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $materie ?: [];
    }

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
        } catch (Exception $e) {
            error_log("Errore durante la creazione dell'utente: " . $e->getMessage());
            return ['success' => false, 'message' => 'Errore interno del server.'];
        } finally {
            $stmt->close();
        }
    }

    public function deleteUser($userId) {
        $this->db->begin_transaction();
        try {
            $sql = "DELETE FROM Utente WHERE id = ? AND tipo = 'utente'";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $deleted = $stmt->affected_rows > 0;
            
            $this->db->commit();
            return $deleted
                ? ['success' => true,  'message' => 'Account e relativi dati eliminati con successo.']
                : ['success' => false, 'message' => 'Utente non trovato o indisponibile.'];
            
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Errore durante l'eliminazione dell'utente ID $userId: " . $e->getMessage());
            return ['success' => false, 'message' => 'Errore durante l\'eliminazione.'];
        } finally {
            if (isset($stmt)) $stmt->close();
        }
    }

    public function updateUserProfile($userId, $newEmail, $nome, $cognome, $descrizione) {
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
        } catch (Exception $e) {
            error_log("Errore update profilo per ID $userId: " . $e->getMessage());
            return ['success' => false, 'message' => 'Errore durante l\'aggiornamento.', 'new_email' => null];
        } finally {
            $stmt->close();
        }
    }

    public function updatePassword($userId, $oldPassword, $newPassword) {
        $sql = "SELECT password FROM Utente WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if (!$user) {
            return ['success' => false, 'message' => 'Utente non trovato.'];
        }

        if (!password_verify($oldPassword, $user['password'])) {
            return ['success' => false, 'message' => 'La vecchia password non è corretta.'];
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $sql = "UPDATE Utente SET password = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $hashedPassword, $userId);

        try {
            $stmt->execute();
            return ['success' => true, 'message' => 'Password modificata con successo!'];
        } catch (Exception $e) {
            error_log("Errore update password per ID $userId: " . $e->getMessage());
            return ['success' => false, 'message' => 'Errore durante la modifica della password.'];
        } finally {
            $stmt->close();
        }
    }

    public function createPost($postData) {
        $this->db->begin_transaction();
        try {
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

            if (!$postId) {
                $this->db->rollback();
                return 0;
            }

            $partStmt = $this->db->prepare("INSERT INTO Partecipazione (utente_id, post_id) VALUES (?, ?)");
            $partStmt->bind_param("ii", $postData['utente_id'], $postId);
            $partStmt->execute();
            $partStmt->close();

            $this->db->commit();
            return $postId;

        } catch (Exception $e) {
            $this->db->rollback();
            return 0;
        } finally {
            if (isset($stmt)) $stmt->close();
        }
    }

    public function updatePost($postId, $userId, $postData, $userIdsToKick = []) {
        $this->db->begin_transaction();
        try {
            $stmt = $this->db->prepare("SELECT utente_id FROM Post WHERE id = ?");
            $stmt->bind_param("i", $postId);
            $stmt->execute();
            $post = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$post) {
                $this->db->rollback();
                return ['success' => false, 'message' => 'Post non trovato.'];
            }

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

            $this->db->commit();
            return ['success' => true, 'message' => 'Post aggiornato con successo.'];

        } catch (Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Errore interno durante l\'aggiornamento.'];
        } finally {
            if (isset($stmt)) $stmt->close();
        }
    }

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
        return $posts ?: [];
    }

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
        } catch (Exception $e) {
            error_log("Errore partecipazione ID $userId al post $postId: " . $e->getMessage());
            return ['success' => false, 'message' => 'Errore durante la partecipazione.'];
        } finally {
            $stmt->close();
        }
    }

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
        return $posts ?: [];
    }

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
            $this->db->query("DELETE FROM Post WHERE id = "                . (int)$postId);

            $this->db->commit();
            return ['success' => true, 'message' => 'Post eliminato con successo.'];
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Errore eliminazione post: " . $e->getMessage());
            return ['success' => false, 'message' => 'Errore interno del server durante eliminazione.'];
        }
    }

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

    public function removeParticipantByOwner($ownerId, $postId, $targetUserId) {
        $checkStmt = $this->db->prepare("SELECT utente_id FROM Post WHERE id = ?");
        $checkStmt->bind_param("i", $postId);
        $checkStmt->execute();
        $res = $checkStmt->get_result()->fetch_assoc();
        $checkStmt->close();

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

    public function getUsersForAdmin() {
        $sql = "SELECT id, email, nome, cognome, descrizione FROM Utente WHERE tipo = 'utente' ORDER BY email ASC";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getPostsForAdmin() {
        $sql = "SELECT P.id, P.titolo, U.nome AS creatore_nome, U.cognome AS creatore_cognome, U.email AS creatore_email
                FROM Post P
                INNER JOIN Utente U ON P.utente_id = U.id
                ORDER BY P.data_creazione DESC";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

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

    public function deletePostByAdmin($postId) {
        $stmt = $this->db->prepare("DELETE FROM Post WHERE id = ?");
        $stmt->bind_param("i", $postId);
        $success = $stmt->execute();
        $stmt->close();

        return $success 
            ? ['success' => true, 'message' => 'Post eliminato con successo dall\'amministratore.'] 
            : ['success' => false, 'message' => 'Errore durante l\'eliminazione del post.'];
    }

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