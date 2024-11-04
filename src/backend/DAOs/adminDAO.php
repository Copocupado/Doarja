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

$postAction = isset($requestData['action']) ? $requestData['action'] : null;
$postData = isset($requestData['data']) ? $requestData['data'] : null;

$getAction = isset($_GET['action']) ? $_GET['action'] : null;
$getData = isset($_GET['data']) ? $_GET['data'] : null;

if (isset($getAction)) {

    switch ($getAction) {
        case 'read':
            $response = getAdmin($getData);
            echo json_encode($response);
            break;

        case 'isUserAdmin':
            $response = isUserAdmin();
            echo json_encode($response);
            break;

        case 'fetchAll':
            $response = getAllAdmins();
            echo json_encode($response);
            break;

        default:
            echo json_encode([
                'success' => false,
                'message' => 'acao invalida',
            ]);
            break;
    }
}

if (isset($postAction)) {
    switch ($postAction) {
        case 'create':
            $response = addAdmin($postData);
            echo json_encode($response);
            break;
        case 'delete':
            $response = deleteAdmin($postData);
            echo json_encode($response);
            break;
        case 'update':
            $response = updateAdmin($postData);
            echo json_encode($response);
            break;
    }
}

function getAdmin($admin)
{
    try {
        global $table;

        $password = $admin['senha'];
        unset($admin['senha']);

        $response = getEntry($table, $admin);

        if (!$response['success'])
            return $response;

        $adminList = $response['message'];

        if (empty($adminList)) {
            return [
                'success' => false,
                'message' => 'Nenhum administrador encontrado com essas credenciais'
            ];
        }

        $admin = $adminList[0];

        $response = verifyPasswords($admin['senha'], $password);
        if (!$response['success'])
            return $response;

        if ($admin['ativo'] == 0) {
            return [
                'success' => false,
                'message' => 'Sua conta está desativada, tente novamente mais tarde'
            ];
        }

        return [
            'success' => true,
            'message' => $admin
        ];
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Exception: ' . $th->getMessage(),
        ];
    }
}

function isUserAdmin()
{
    try {
        $response = getSessionData();
        if (!$response['success'])
            return $response;

        $data = $response['message'];

        if ($data == null) {
            return [
                'success' => true,
                'message' => false,
            ];
        }
        return [
            'success' => true,
            'message' => $data['role'] == 'admin' && $data['ativo'] == 1,
        ];
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao verificar se o usuário é um admin: ' . $th->getMessage(),
        ];
    }
}

function getAllAdmins()
{
    try {
        global $table;

        $response = getTable($table);

        if (!$response['success'])
            return $response;

        return [
            'success' => true,
            'message' => $response['message'],
        ];
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Erro ao obter todos os admins: ' . $th->getMessage(),
        ];
    }
}

function addAdmin($admin)
{
    try {
        global $table;

        if (isset($admin['senha'])) {
            $admin['senha'] = password_hash($admin['senha'], PASSWORD_BCRYPT);
        } else {
            return [
                'success' => false,
                'message' => 'Administrador deve conter senha'
            ];
        }

        $admin['ativo'] = isset($admin['ativo']) && $admin['ativo'] ? 1 : 0;

        $response = addEntry($table, $admin);

        if (!$response['success'])
            return $response;

        return [
            'success' => true,
            'message' => 'Administrador adicionado com sucesso',
        ];

    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao adicionar o administrador: ' . $th->getMessage(),
        ];
    }
}
function deleteAdmin($admin)
{
    try {
        global $table;

        $admin['ativo'] = isset($admin['ativo']) && $admin['ativo'] == 'Ativado' ? 1 : 0;

        $response = deleteEntry($table, $admin);

        if (!$response['success'])
            return $response;

        return [
            'success' => true,
            'message' => 'Administrador excluido com sucesso',
        ];
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao excluir o administrador: ' . $th->getMessage(),
        ];
    }
}

function updateAdmin($admin)
{
    try {
        global $table;

        $admin['ativo'] = isset($admin['ativo']) && $admin['ativo'] ? 1 : 0;

        $response = updateEntry($table, 'email', $admin['email'], $admin);

        if (!$response['success'])
            return $response;

        return [
            'success' => true,
            'message' => 'Administrador atualizado com sucesso',
        ];
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao atualizar o administrador: ' . $th->getMessage(),
        ];
    }
}
?>