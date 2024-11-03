<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Content-Type: application/json");

    include __DIR__ . '/../MySQLFunctions.php';
    include __DIR__ . '/../SessionManager.php';

    $table = 'admins';

    $jsonData = file_get_contents('php://input');
    $requestData = json_decode($jsonData, true);
    
    if (isset($_GET['action'])) {
    
        switch($_GET['action']){
            case 'getAdmin':
                $email = $_GET['email'];
                $password = $_GET['password'];

                $message = getAdmin($email, $password);
                echo json_encode($message);
                break;

            case 'isUserAdmin':
                $response = isUserAdmin();
                echo json_encode($response);
                break;

            case 'getAllAdmins':
                $response = getAllAdmins();
                echo json_encode($response);
                break;

            default:
                echo json_encode(['error' => 'Invalid action or missing parameters']);
                break;
        }
    }

    if (isset($requestData['action'])) {
        switch ($requestData['action']) {
            case 'addAdmin':
                $response = addAdmin($requestData['data']);
                echo json_encode($response);
                break;
            case 'deleteAdmin':
                $response = deleteAdmin($requestData['data']);
                echo json_encode($response);
                break;
            case 'updateAdmin':
                $response = updateAdmin($requestData['data']);
                echo json_encode($response);
                break;
        }
    }

    function getAdmin($email, $password) {
        global $table;
        $usersList = getEntry($table, 'email', $email);
    
        if (empty($usersList)) {
            return [
                'success' => false,
                'message' => 'Nenhum administrador encontrado com essas credenciais'
            ];
        }
    
        $user = $usersList[0];
        if (!verifyPasswords($user['senha'], $password)) { 
            return [
                'success' => false,
                'message' => 'As senhas não coincidem'
            ];
        }

        if($user['ativo'] == 0){
            return [
                'success' => false,
                'message' => 'Sua conta está desativada, tente novamente mais tarde'
            ];
        }

        return [
            'success' => true,
            'message' => $user
        ];
    }

    function isUserAdmin() {
        $response = getSessionData();
        if($response == null){
            return false;
        }
        return $response['role'] == 'admin' && $response['ativo'] == 1;
    }

    function getAllAdmins() {
        global $table;
        return getTable($table);
    }

    function addAdmin($admin) {
        global $table;
    
        if (isset($admin['senha'])) {
            $admin['senha'] = password_hash($admin['senha'], PASSWORD_BCRYPT);
        } else {
            return [
                'success' => false,
                'message' => 'Password is required.'
            ];
        }

        $admin['ativo'] = isset($admin['ativo']) && $admin['ativo'] ? 1 : 0;
    
        $response = addEntry($table, $admin);
        return $response;
    }
    function deleteAdmin($admin) {
        global $table;
        $response = deleteEntry($table, 'email', $admin['email']);
        return $response;
    }

    function updateAdmin($admin) {
        global $table;
        $admin['ativo'] = isset($admin['ativo']) && $admin['ativo'] ? 1 : 0;
        $response = updateEntry($table, 'email', $admin['email'], $admin);
        return $response;
    }
?>