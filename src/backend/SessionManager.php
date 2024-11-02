<?php
    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");

    session_start();

    $jsonData = file_get_contents('php://input');
    $requestData = json_decode($jsonData, true);

    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'getSessionData':
                $data = getSessionData();
                echo json_encode($data);
                break;
        }
    }

    if (isset($requestData['action'])) {
        switch ($requestData['action']) {
            case 'saveSessionData':
                saveSessionData($requestData['data']);
                echo json_encode(['success' => true, 'message' => 'Data saved successfully.']);
                break;
            case 'destroySession':
                destroySession();
                echo json_encode(['success' => true, 'message' => 'Session destroyed.']);
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action for POST request.']);
                break;
        }
    }

    function saveSessionData($data) {
        $_SESSION['data'] = $data;
    }

    function getSessionData() {
        return $_SESSION['data'] ?? null;
    }

    function destroySession() {
        $_SESSION['data'] = null;
        session_unset();
        session_destroy();
    }
?>
