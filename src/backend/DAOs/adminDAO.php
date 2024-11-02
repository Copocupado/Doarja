<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Content-Type: application/json");

    include __DIR__ . '/../MySQLFunctions.php';
    include __DIR__ . '/../SessionManager.php';
    
    if (isset($_GET['action'])) {
    
        switch($_GET['action']){
            case 'getAdmin':
                $email = $_GET['email'];
                $password = $_GET['password'];

                $user = getAdmin($email, $password);
                echo json_encode($user);
                break;

            case 'isUserAdmin':
                $response = isUserAdmin();
                echo json_encode($response);
                break;

            default:
                echo json_encode(['error' => 'Invalid action or missing parameters']);
                break;
        }
    }

    function getAdmin($email, $password) {
        $usersList = getEntry('admins', 'email', $email);
    
        if (empty($usersList)) {
            return null;
        }
    
        $user = $usersList[0];
        if (verifyPasswords($user['senha'], $password)) { 
            return $user;
        } else {
            return null;
        }
    }

    function isUserAdmin() {
        $reponse = getSessionData();

        return $reponse['role'] == 'admin';
    }
?>