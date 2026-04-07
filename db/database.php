<?php
class DatabaseHelper {
    private mysqli $db;

    public function __construct(string $servername, string $username, string $password, string $dbname, int $port = 3306) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        } catch (mysqli_sql_exception $e) {
            throw new Exception("Errore di connessione al database. Riprova più tardi.");
        }
    }

    public function getAllPosts(array $filters = []): array {
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
        
        $orderBy = "P.data_creazione DESC"; // default
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

    public function getPostInfo(int $id) {
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

    public function getAllMaterie(): array {
        $sql = "SELECT id, nome FROM Materia ORDER BY nome";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $materie = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $materie ?: [];
    }

    public function getPostFiles(int $postId): array {
        $sql = "SELECT id, nome, tipo, dimensione_byte FROM File WHERE post_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();

        $files = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $files ?: [];
    }

    public function getFileForDownload(int $fileId): ?array {
        $sql = "SELECT nome, tipo, dimensione_byte, contenuto FROM File WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $fileId);
        $stmt->execute();
        $result = $stmt->get_result();

        $file = $result->fetch_assoc();
        $stmt->close();
        return $file ?: null;
    }

    public function getUserByEmail(string $email): ?array {
        $sql = "SELECT email, nome, cognome, password, descrizione, tipo FROM Utente WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $user = $result->fetch_assoc();
        $stmt->close();
        return $user ?: null;
    }

    public function createUser(string $email, string $nome, string $cognome, string $password, string $descrizione = ""): bool {
        $existingUser = $this->getUserByEmail($email);
        if ($existingUser) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO Utente (email, nome, cognome, password, descrizione, tipo) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $tipo = 'utente';
        
        $stmt->bind_param("ssssss", $email, $nome, $cognome, $hashedPassword, $descrizione, $tipo);
        
        try {
            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            error_log("Errore durante la creazione dell'utente: " . $e->getMessage());
            return false;
        } finally {
            $stmt->close();
        }
    }

    public function deleteUser(string $email): bool {
        $sql = "DELETE FROM Utente WHERE email = ? AND tipo = 'utente'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        
        try {
            $stmt->execute();
            $deleted = $stmt->affected_rows > 0;
            return $deleted;
        } catch (mysqli_sql_exception $e) {
            error_log("Errore durante l'eliminazione dell'utente: " . $e->getMessage());
            return false;
        } finally {
            $stmt->close();
        }
    }

public function updateUserProfile(string $oldEmail, string $newEmail, string $nome, string $cognome, string $descrizione): array {
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
            'success' => true, 
            'message' => 'Profilo aggiornato con successo!', 
            'new_email' => $newEmail
        ];
    } catch (mysqli_sql_exception $e) {
        error_log("Errore update profilo: " . $e->getMessage());
        return ['success' => false, 'message' => 'Errore durante l\'aggiornamento.', 'new_email' => null];
    }
    finally {
        $stmt->close();
    }
}

public function createPost(array $postData, array $files = []): int {
    $this->db->begin_transaction();
    try {
        // 1. Insert Post
        $sql = "INSERT INTO Post (titolo, richiede_approvazione, tipo, descrizione, data_inizio, data_fine, luogo, max_partecipanti, utente_email, materia_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        $richiedeApprovazione = !empty($postData['richiede_approvazione']) ? 1 : 0;
        $dataFine = !empty($postData['data_fine']) ? $postData['data_fine'] : null;
        $descrizione = $postData['descrizione'] ?? '';
        
        // Mappa tipo frontend → DB
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

        // 3. Upload File (✅ FIX: gestione indipendente per ogni file)
        if (!empty($files['name'])) {
            $names = is_array($files['name']) ? $files['name'] : [$files['name']];
            $types = is_array($files['type']) ? $files['type'] : [$files['type']];
            $sizes = is_array($files['size']) ? $files['size'] : [$files['size']];
            $tmps  = is_array($files['tmp_name']) ? $files['tmp_name'] : [$files['tmp_name']];
            $errors = is_array($files['error']) ? $files['error'] : [$files['error']];

            error_log("📁 [DB] Tentativo upload di " . count($names) . " file(s)");

            // Preparo la statement UNA sola volta (migliora performance)
            $fileStmt = $this->db->prepare("INSERT INTO File (nome, tipo, dimensione_byte, contenuto, post_id) VALUES (?, ?, ?, ?, ?)");

            for ($i = 0; $i < count($names); $i++) {
                if ($errors[$i] !== UPLOAD_ERR_OK) {
                    error_log("⚠️ File[$i] errore PHP: {$errors[$i]}");
                    continue;
                }
                
                $fileName = basename($names[$i]);
                if (empty($fileName)) continue;
                
                $fileSize = (int)$sizes[$i];
                $fileType = $types[$i];
                $fileTmp  = $tmps[$i];
                
                // Lettura sicura del contenuto
                $content = @file_get_contents($fileTmp);
                if ($content === false || strlen($content) === 0) {
                    error_log("⚠️ File saltato (vuoto/illeggibile): $fileName");
                    continue;
                }

                try {
                    // ✅ bind_param fuori dal try per evitare problemi di riferimento
                    $fileStmt->bind_param("ssisi", $fileName, $fileType, $fileSize, $content, $postId);
                    $fileStmt->execute();
                    error_log("✅ File salvato: $fileName (" . strlen($content) . " bytes)");
                } catch (mysqli_sql_exception $e) {
                    // Se UN file fallisce, NON annulliamo tutto. Logghiamo e continuiamo.
                    error_log("❌ File NON salvato ($fileName): " . $e->getMessage());
                }
            }
            $fileStmt->close();
        }

        $this->db->commit();
        return $postId;

    } catch (Exception $e) {
        $this->db->rollback();
        error_log("💥 Rollback transazione: " . $e->getMessage());
        return 0;
    }
}
}

?>