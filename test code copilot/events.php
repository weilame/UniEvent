<?php
// Set the content type to JSON
header('Content-Type: application/json');

// Allow cross-origin requests (CORS) - IMPORTANT FOR FLUTTERFLOW
// In a production environment, you should restrict this to your FlutterFlow app's domain.
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS requests (required for complex CORS requests)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// --- Database Configuration ---
// !! IMPORTANT: Replace these with your actual database credentials !!
define('DB_HOST', 'localhost'); // <<< PUT YOUR DATABASE HOST HERE (e.g., 'localhost' or your database host IP)
define('DB_NAME', 'event_management'); // <<< YOUR DATABASE NAME
define('DB_USER', 'root');   // <<< PUT YOUR DATABASE USERNAME HERE (e.g., 'root')
define('DB_PASS', '');   // <<< PUT YOUR DATABASE PASSWORD HERE (e.g., '' for no password)

// --- Database Connection ---
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Fetch as associative array
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// --- API Logic ---

// Get the action from the URL query parameter (e.g., api.php?action=read)
$action = $_GET['action'] ?? ''; // Default to empty string if not set

switch ($action) {
    case 'read':
        handleRead($pdo);
        break;
    case 'create':
        handleCreate($pdo);
        break;
    case 'update':
        handleUpdate($pdo);
        break;
    case 'delete':
        handleDelete($pdo);
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid or missing action.']);
        break;
}

// --- API Functions ---

/**
 * Handles reading data from the 'event' table.
 * Supports reading all events or a specific event by ID.
 * @param PDO $pdo The PDO database connection object.
 */
function handleRead(PDO $pdo) {
    $id = $_GET['id'] ?? null; // Get ID from query parameter if provided

    try {
        if ($id) {
            // Read a single event by ID
            $stmt = $pdo->prepare("SELECT Evt_id, Evt_title, Evt_desc, Evt_loc, Evt_campus, Evt_date_start, Evt_date_end, Evt_time_start, Evt_time_end, Evt_poster, Evt_poster_type FROM event WHERE Evt_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $event = $stmt->fetch();

            if ($event) {
                echo json_encode(['status' => 'success', 'data' => $event]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Event not found.']);
            }
        } else {
            // Read all events
            $stmt = $pdo->query("SELECT Evt_id, Evt_title, Evt_desc, Evt_loc, Evt_campus, Evt_date_start, Evt_date_end, Evt_time_start, Evt_time_end, Evt_poster, Evt_poster_type FROM event ORDER BY Evt_date_start DESC, Evt_time_start DESC");
            $events = $stmt->fetchAll();
            echo json_encode(['status' => 'success', 'data' => $events]);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to read data: ' . $e->getMessage()]);
    }
}

/**
 * Handles creating a new event in the 'event' table.
 * Expects JSON data in the request body with all event attributes.
 * @param PDO $pdo The PDO database connection object.
 */
function handleCreate(PDO $pdo) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['status' => 'error', 'message' => 'Method not allowed. Use POST.']);
        return;
    }

    // Get raw POST data (JSON)
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate input - check for all required fields
    $required_fields = ['Evt_title', 'Evt_desc', 'Evt_loc', 'Evt_campus', 'Evt_date_start', 'Evt_date_end', 'Evt_time_start', 'Evt_time_end', 'Evt_poster', 'Evt_poster_type'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field])) {
            echo json_encode(['status' => 'error', 'message' => "Missing required field: $field."]);
            return;
        }
    }

    $Evt_title = $data['Evt_title'];
    $Evt_desc = $data['Evt_desc'];
    $Evt_loc = $data['Evt_loc'];
    $Evt_campus = $data['Evt_campus'];
    $Evt_date_start = $data['Evt_date_start'];
    $Evt_date_end = $data['Evt_date_end'];
    $Evt_time_start = $data['Evt_time_start'];
    $Evt_time_end = $data['Evt_time_end'];
    $Evt_poster = $data['Evt_poster'];
    $Evt_poster_type = $data['Evt_poster_type'];

    try {
        $stmt = $pdo->prepare("INSERT INTO event (Evt_title, Evt_desc, Evt_loc, Evt_campus, Evt_date_start, Evt_date_end, Evt_time_start, Evt_time_end, Evt_poster, Evt_poster_type) VALUES (:Evt_title, :Evt_desc, :Evt_loc, :Evt_campus, :Evt_date_start, :Evt_date_end, :Evt_time_start, :Evt_time_end, :Evt_poster, :Evt_poster_type)");
        $stmt->bindParam(':Evt_title', $Evt_title);
        $stmt->bindParam(':Evt_desc', $Evt_desc);
        $stmt->bindParam(':Evt_loc', $Evt_loc);
        $stmt->bindParam(':Evt_campus', $Evt_campus);
        $stmt->bindParam(':Evt_date_start', $Evt_date_start);
        $stmt->bindParam(':Evt_date_end', $Evt_date_end);
        $stmt->bindParam(':Evt_time_start', $Evt_time_start);
        $stmt->bindParam(':Evt_time_end', $Evt_time_end);
        $stmt->bindParam(':Evt_poster', $Evt_poster);
        $stmt->bindParam(':Evt_poster_type', $Evt_poster_type);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Event created successfully.', 'Evt_id' => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create event: ' . $e->getMessage()]);
    }
}

/**
 * Handles updating an existing event in the 'event' table.
 * Expects JSON data in the request body (Evt_id and fields to update).
 * @param PDO $pdo The PDO database connection object.
 */
function handleUpdate(PDO $pdo) {
    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
        echo json_encode(['status' => 'error', 'message' => 'Method not allowed. Use PUT.']);
        return;
    }

    // Get raw PUT data (JSON)
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate input
    if (!isset($data['Evt_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing Evt_id to update.']);
        return;
    }

    $Evt_id = $data['Evt_id'];
    $update_fields = [];
    $params = [':Evt_id' => $Evt_id];

    // Dynamically build the UPDATE query based on provided fields
    if (isset($data['Evt_title'])) { $update_fields[] = "Evt_title = :Evt_title"; $params[':Evt_title'] = $data['Evt_title']; }
    if (isset($data['Evt_desc'])) { $update_fields[] = "Evt_desc = :Evt_desc"; $params[':Evt_desc'] = $data['Evt_desc']; }
    if (isset($data['Evt_loc'])) { $update_fields[] = "Evt_loc = :Evt_loc"; $params[':Evt_loc'] = $data['Evt_loc']; }
    if (isset($data['Evt_campus'])) { $update_fields[] = "Evt_campus = :Evt_campus"; $params[':Evt_campus'] = $data['Evt_campus']; }
    if (isset($data['Evt_date_start'])) { $update_fields[] = "Evt_date_start = :Evt_date_start"; $params[':Evt_date_start'] = $data['Evt_date_start']; }
    if (isset($data['Evt_date_end'])) { $update_fields[] = "Evt_date_end = :Evt_date_end"; $params[':Evt_date_end'] = $data['Evt_date_end']; }
    if (isset($data['Evt_time_start'])) { $update_fields[] = "Evt_time_start = :Evt_time_start"; $params[':Evt_time_start'] = $data['Evt_time_start']; }
    if (isset($data['Evt_time_end'])) { $update_fields[] = "Evt_time_end = :Evt_time_end"; $params[':Evt_time_end'] = $data['Evt_time_end']; }
    if (isset($data['Evt_poster'])) { $update_fields[] = "Evt_poster = :Evt_poster"; $params[':Evt_poster'] = $data['Evt_poster']; }
    if (isset($data['Evt_poster_type'])) { $update_fields[] = "Evt_poster_type = :Evt_poster_type"; $params[':Evt_poster_type'] = $data['Evt_poster_type']; }

    if (empty($update_fields)) {
        echo json_encode(['status' => 'error', 'message' => 'No fields provided for update.']);
        return;
    }

    try {
        $sql = "UPDATE event SET " . implode(', ', $update_fields) . " WHERE Evt_id = :Evt_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Event updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Event not found or no changes made.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update event: ' . $e->getMessage()]);
    }
}

/**
 * Handles deleting an event from the 'event' table.
 * Expects JSON data in the request body (Evt_id).
 * @param PDO $pdo The PDO database connection object.
 */
function handleDelete(PDO $pdo) {
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        echo json_encode(['status' => 'error', 'message' => 'Method not allowed. Use DELETE.']);
        return;
    }

    // Get raw DELETE data (JSON)
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate input
    if (!isset($data['Evt_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing Evt_id to delete.']);
        return;
    }

    $Evt_id = $data['Evt_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM event WHERE Evt_id = :Evt_id");
        $stmt->bindParam(':Evt_id', $Evt_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Event deleted successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Event not found.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete event: ' . $e->getMessage()]);
    }
}

?>
