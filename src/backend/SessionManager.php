<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

session_start();

$jsonData = file_get_contents('php://input');
$requestData = json_decode($jsonData, true);

if (json_last_error() === JSON_ERROR_NONE) {
    if (!isset($requestData['action'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON data.']);
        exit; // Make sure to exit after sending a response
    }

    switch ($requestData['action']) {
        case 'saveSessionData':
            // Check if 'data' key exists before calling the function
            if (isset($requestData['data'])) {
                saveSessionData($requestData['data']);
                echo json_encode(['success' => true, 'message' => 'data saved on key data']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Data key is missing.']);
            }
            break;

        case 'getSessionData':
            $data = getSessionData();
            echo json_encode(['success' => true, 'data' => $data]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action.']);
            break;    
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to decode JSON.']);
}

function saveSessionData($data) {
    $_SESSION['data'] = $data; // Directly set the session data
}

function getSessionData() {
    return $_SESSION['data'] ?? null;
}
?>
