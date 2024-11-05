<?php
header("Access-Control-Allow-Origin: http://localhost:3000"); // Allow only this origin
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true"); // Allow credentials
header("Content-Type: application/json");

include_once __DIR__ . '/../baseDAO.php';

$itemDAO = new itemDAO();

$jsonData = file_get_contents('php://input');
$requestData = json_decode($jsonData, true);

$postAction = isset($requestData['action']) ? $requestData['action'] : null;
$postData = isset($requestData['data']) ? $requestData['data'] : null;

$getAction = isset($_GET['action']) ? $_GET['action'] : null;
$getData = isset($_GET['data']) ? $_GET['data'] : null;

if (isset($getAction)) {

    switch ($getAction) {
        case 'read':
            $response = getAllitems($getData);
            echo json_encode($response);
            break;

        case 'isItem':
            $response = isItem();
            echo json_encode($response);
            break;

        case 'fetchAll':
            $response = getAllitems($getData);
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
            $response = addItem($postData);
            echo json_encode($response);
            break;
        case 'delete':
            $response = deleteItem($postData);
            echo json_encode($response);
            break;
        case 'update':
            $response = updateItem($postData);
            echo json_encode($response);
            break;
    }
}

function getItem($item)
{
    global $itemDAO;

    try {
        $password = $item['senha'];
        unset($item['senha']);

        $response = $itemDAO->read($item);

        if (!$response['success'])
            return $response;

        $itemList = $response['message'];

        if (empty($itemList)) {
            return [
                'success' => false,
                'message' => 'Nenhuma item encontrada com essas credenciais'
            ];
        }

        $item = $itemList[0];

        $response = verifyPasswords($item['senha'], $password);
        if (!$response['success'])
            return $response;

        return [
            'success' => true,
            'message' => $item
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
    global $itemDAO;

    try {
        return $itemDAO->isItem();
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao verificar se o usuário é um item: ' . $th->getMessage(),
        ];
    }
}

function getAllItems($item)
{
    global $itemDAO;
    try {
        return $itemDAO->read($item);
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Erro ao obter todos os items: ' . $th->getMessage(),
        ];
    }
}

function addItem($item)
{
    global $itemDAO;
    try {
        $item['disponivel'] = isset($item['disponivel']) && $item['disponivel'] ? 1 : 0;
        return $itemDAO->create($item);
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao adicionar a item: ' . $th->getMessage(),
        ];
    }
}
function deleteItem($item)
{
    global $itemDAO;
    try {

        $item['disponivel'] = isset($item['disponivel']) && $item['disponivel'] == 'Disponível' ? 1 : 0;
        return $itemDAO->delete($item);

    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao excluir a item: ' . $th->getMessage(),
        ];
    }
}

function updateItem($item)
{
    global $itemDAO;
    try {

        $item['disponivel'] = isset($item['disponivel']) && $item['disponivel'] == 'Disponível' ? 1 : 0;
        return $itemDAO->update($item);

    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao atualizar a item: ' . $th->getMessage(),
        ];
    }
}
?>