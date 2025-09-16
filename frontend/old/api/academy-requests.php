<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Database connection
$host = 'database';
$dbname = 'vult';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$endpoint = $_GET['endpoint'] ?? '';

switch ($method) {
    case 'GET':
        if ($endpoint === 'academy-requests') {
            getAcademyRequests($pdo);
        } elseif ($endpoint === 'players') {
            getPlayers($pdo);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
        }
        break;
        
    case 'POST':
        if ($endpoint === 'academy-requests') {
            createAcademyRequest($pdo);
        } elseif ($endpoint === 'players') {
            createPlayer($pdo);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
        }
        break;
        
    case 'PUT':
        if ($endpoint === 'academy-requests') {
            $id = $_GET['id'] ?? '';
            updateAcademyRequest($pdo, $id);
        } elseif ($endpoint === 'players') {
            $id = $_GET['id'] ?? '';
            updatePlayer($pdo, $id);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
        }
        break;
        
    case 'DELETE':
        if ($endpoint === 'academy-requests') {
            $id = $_GET['id'] ?? '';
            deleteAcademyRequest($pdo, $id);
        } elseif ($endpoint === 'players') {
            $id = $_GET['id'] ?? '';
            deletePlayer($pdo, $id);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}

function getAcademyRequests($pdo) {
    try {
        $status = $_GET['status'] ?? '';
        $search = $_GET['search'] ?? '';
        
        $sql = "SELECT * FROM academy_requests WHERE 1=1";
        $params = [];
        
        if ($status) {
            $sql .= " AND status = :status";
            $params[':status'] = $status;
        }
        
        if ($search) {
            $sql .= " AND (academy_name LIKE :search OR manager_name LIKE :search OR email LIKE :search)";
            $params[':search'] = "%$search%";
        }
        
        $sql .= " ORDER BY requested_at DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'data' => $requests,
            'count' => count($requests)
        ]);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function getPlayers($pdo) {
    try {
        $status = $_GET['status'] ?? '';
        $sport = $_GET['sport'] ?? '';
        $search = $_GET['search'] ?? '';
        
        $sql = "SELECT * FROM players WHERE 1=1";
        $params = [];
        
        if ($status) {
            $sql .= " AND status = :status";
            $params[':status'] = $status;
        }
        
        if ($sport) {
            $sql .= " AND sport = :sport";
            $params[':sport'] = $sport;
        }
        
        if ($search) {
            $sql .= " AND (name LIKE :search OR phone LIKE :search)";
            $params[':search'] = "%$search%";
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $players = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'data' => $players,
            'count' => count($players)
        ]);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function createAcademyRequest($pdo) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $sql = "INSERT INTO academy_requests (academy_name, manager_name, email, phone, address, city, branches_count, sports, description, status) 
                VALUES (:academy_name, :manager_name, :email, :phone, :address, :city, :branches_count, :sports, :description, 'pending')";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':academy_name' => $input['academy_name'],
            ':manager_name' => $input['manager_name'],
            ':email' => $input['email'],
            ':phone' => $input['phone'],
            ':address' => $input['address'] ?? '',
            ':city' => $input['city'] ?? '',
            ':branches_count' => $input['branches_count'] ?? 1,
            ':sports' => $input['sports'] ?? '',
            ':description' => $input['description'] ?? ''
        ]);
        
        $requestId = $pdo->lastInsertId();
        
        echo json_encode([
            'success' => true,
            'message' => 'Academy request created successfully',
            'data' => ['id' => $requestId]
        ]);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function createPlayer($pdo) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $sql = "INSERT INTO players (name, nationality, id_number, phone, address, dob, academy_id, sport, status, paid) 
                VALUES (:name, :nationality, :id_number, :phone, :address, :dob, :academy_id, :sport, 'active', :paid)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $input['name'],
            ':nationality' => $input['nationality'] ?? '',
            ':id_number' => $input['id_number'] ?? '',
            ':phone' => $input['phone'],
            ':address' => $input['address'] ?? '',
            ':dob' => $input['dob'] ?? null,
            ':academy_id' => $input['academy_id'] ?? 1,
            ':sport' => $input['sport'] ?? '',
            ':paid' => $input['paid'] ?? 0.00
        ]);
        
        $playerId = $pdo->lastInsertId();
        
        echo json_encode([
            'success' => true,
            'message' => 'Player created successfully',
            'data' => ['id' => $playerId]
        ]);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function updateAcademyRequest($pdo, $id) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $sql = "UPDATE academy_requests SET status = :status, notes = :notes";
        
        if ($input['status'] === 'approved') {
            $sql .= ", approved_at = NOW()";
        } elseif ($input['status'] === 'rejected') {
            $sql .= ", rejected_at = NOW()";
        }
        
        $sql .= " WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':status' => $input['status'],
            ':notes' => $input['notes'] ?? '',
            ':id' => $id
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Academy request updated successfully'
        ]);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function updatePlayer($pdo, $id) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $sql = "UPDATE players SET name = :name, nationality = :nationality, phone = :phone, 
                address = :address, sport = :sport, status = :status, paid = :paid 
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $input['name'],
            ':nationality' => $input['nationality'] ?? '',
            ':phone' => $input['phone'],
            ':address' => $input['address'] ?? '',
            ':sport' => $input['sport'] ?? '',
            ':status' => $input['status'] ?? 'active',
            ':paid' => $input['paid'] ?? 0.00,
            ':id' => $id
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Player updated successfully'
        ]);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function deleteAcademyRequest($pdo, $id) {
    try {
        $sql = "DELETE FROM academy_requests WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Academy request deleted successfully'
        ]);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function deletePlayer($pdo, $id) {
    try {
        $sql = "DELETE FROM players WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Player deleted successfully'
        ]);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
