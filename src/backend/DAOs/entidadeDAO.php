<?php
header("Access-Control-Allow-Origin: http://localhost:3000"); // Allow only this origin
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true"); // Allow credentials
header("Content-Type: application/json");

include_once __DIR__ . '/../baseDAO.php';

$entidadeDAO = new EntidadeDAO();

$jsonData = file_get_contents('php://input');
$requestData = json_decode($jsonData, true);

$postAction = isset($requestData['action']) ? $requestData['action'] : null;
$postData = isset($requestData['data']) ? $requestData['data'] : null;

$getAction = isset($_GET['action']) ? $_GET['action'] : null;
$getData = isset($_GET['data']) ? $_GET['data'] : null;

if (isset($getAction)) {

    switch ($getAction) {
        case 'read':
            $response = getEntidade($getData);
            echo json_encode($response);
            break;

        case 'isItem':
            $response = isItem();
            echo json_encode($response);
            break;

        case 'fetchAll':
            $response = getAllEntidades();
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
            $response = addEntidade($postData);
            echo json_encode($response);
            break;
        case 'delete':
            $response = deleteEntidade($postData);
            echo json_encode($response);
            break;
        case 'update':
            $response = updateEntidade($postData);
            echo json_encode($response);
            break;
    }
}

function getEntidade($entidade)
{
    global $entidadeDAO;

    try {
        $password = null;
        if(isset($entidade['senha'])){
            $password = $entidade['senha'];
            unset($entidade['senha']);
        }

        $response = $entidadeDAO->read($entidade);

        if (!$response['success'])
            return $response;

        $entidadeList = $response['message'];

        if (empty($entidadeList)) {
            return [
                'success' => false,
                'message' => 'Nenhuma entidade encontrada com essas credenciais'
            ];
        }

        $entidade = $entidadeList[0];

        if(isset($password)){
            $response = verifyPasswords($entidade['senha'], $password);
            if (!$response['success'])
                return $response;
        }

        return [
            'success' => true,
            'message' => $entidade
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
    global $entidadeDAO;

    try {
        return $entidadeDAO->isItem();
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao verificar se o usuário é um entidade: ' . $th->getMessage(),
        ];
    }
}

function getAllEntidades()
{
    global $entidadeDAO;
    try {
        return $entidadeDAO->fetchAll();
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Erro ao obter todos os entidades: ' . $th->getMessage(),
        ];
    }
}

function addEntidade($entidade)
{
    global $entidadeDAO;
    try {

        if (isset($entidade['senha'])) {
            $entidade['senha'] = password_hash($entidade['senha'], PASSWORD_BCRYPT);
        } else {
            return [
                'success' => false,
                'message' => 'Entidade deve conter senha'
            ];
        }

        unset($entidade['id']);
        return $entidadeDAO->create($entidade);
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao adicionar a entidade: ' . $th->getMessage(),
        ];
    }
}
function deleteEntidade($entidade)
{
    
    global $entidadeDAO;
    try {

        return $entidadeDAO->delete($entidade);

    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao excluir a entidade: ' . $th->getMessage(),
        ];
    }
}

function updateEntidade($entidade)
{
    global $entidadeDAO;
    try {

        if($entidade['senha'] == '') {
            unset($entidade['senha']);
        } else {
            $entidade['senha'] = password_hash($entidade['senha'], PASSWORD_BCRYPT);
        }

        return $entidadeDAO->update($entidade);

    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao atualizar a entidade: ' . $th->getMessage(),
        ];
    }
}
?>