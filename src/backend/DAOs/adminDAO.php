<?php
header("Access-Control-Allow-Origin: http://localhost:3000"); // Allow only this origin
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true"); // Allow credentials
header("Content-Type: application/json");

include_once __DIR__ . '/../baseDAO.php';

$adminDAO = new AdminDAO();

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

        case 'isItem':
            $response = isItem();
            echo json_encode($response);
            break;

        case 'fetchAll':
            $response = getAllAdmins();
            echo json_encode($response);
            break;

        default:
            echo json_encode([
                'success' => false,
                'message' => 'Ação inválida!',
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
    global $adminDAO;

    try {
        $password = $admin['senha'];
        unset($admin['senha']);

        $response = $adminDAO->read($admin);

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

function isItem()
{
    global $adminDAO;

    try {
        return $adminDAO->isItem();
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao verificar se o usuário é um admin: ' . $th->getMessage(),
        ];
    }
}

function getAllAdmins()
{
    global $adminDAO;
    try {
        return $adminDAO->fetchAll();
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Erro ao obter todos os admins: ' . $th->getMessage(),
        ];
    }
}

function addAdmin($admin)
{
    global $adminDAO;

    try {

        if (isset($admin['senha'])) {
            $admin['senha'] = password_hash($admin['senha'], PASSWORD_BCRYPT);
        } else {
            return [
                'success' => false,
                'message' => 'Administrador deve conter senha'
            ];
        }

        $admin['ativo'] = isset($admin['ativo']) && $admin['ativo'] ? 1 : 0;

        return $adminDAO->create($admin);

    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao adicionar o administrador: ' . $th->getMessage(),
        ];
    }
}
function deleteAdmin($admin)
{
    global $adminDAO;
    try {

        $admin['ativo'] = isset($admin['ativo']) && $admin['ativo'] == 'Ativado' ? 1 : 0;

        return $adminDAO->delete($admin);

    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao excluir o administrador: ' . $th->getMessage(),
        ];
    }
}

function updateAdmin($admin)
{
    global $adminDAO;
    try {

        $admin['ativo'] = isset($admin['ativo']) && $admin['ativo'] ? 1 : 0;

        if($admin['senha'] == '') {
            unset($admin['senha']);
        } else {
            $admin['senha'] = password_hash($admin['senha'], PASSWORD_BCRYPT);

        }
        return $adminDAO->update($admin);

    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao atualizar o administrador: ' . $th->getMessage(),
        ];
    }
}
?>