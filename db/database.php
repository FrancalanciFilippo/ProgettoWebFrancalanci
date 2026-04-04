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
}

?>