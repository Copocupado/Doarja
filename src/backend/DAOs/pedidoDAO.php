<?php
header("Access-Control-Allow-Origin: http://localhost:3000"); // Allow only this origin
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true"); // Allow credentials
header("Content-Type: application/json");

include_once __DIR__ . '/../baseDAO.php';

$pedidoDAO = new pedidoDAO();

$jsonData = file_get_contents('php://input');
$requestData = json_decode($jsonData, true);

$postAction = isset($requestData['action']) ? $requestData['action'] : null;
$postData = isset($requestData['data']) ? $requestData['data'] : null;

$getAction = isset($_GET['action']) ? $_GET['action'] : null;
$getData = isset($_GET['data']) ? $_GET['data'] : null;

if (isset($getAction)) {

    switch ($getAction) {
        case 'read':
            $response = getAllpedidos($getData);
            echo json_encode($response);
            break;

        case 'isPedido':
            $response = isPedido();
            echo json_encode($response);
            break;

        case 'fetchAll':
            $response = getAllpedidos($getData);
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
            $response = addPedido($postData);
            echo json_encode($response);
            break;
        case 'delete':
            $response = deletePedido($postData);
            echo json_encode($response);
            break;
        case 'update':
            $response = updatePedido($postData);
            echo json_encode($response);
            break;
    }
}

function getPedido($pedido)
{
    global $pedidoDAO;

    try {
        $password = $pedido['senha'];
        unset($pedido['senha']);

        $response = $pedidoDAO->read($pedido);

        if (!$response['success'])
            return $response;

        $pedidoList = $response['message'];

        if (empty($pedidoList)) {
            return [
                'success' => false,
                'message' => 'Nenhuma pedido encontrada com essas credenciais'
            ];
        }

        $pedido = $pedidoList[0];

        $response = verifyPasswords($pedido['senha'], $password);
        if (!$response['success'])
            return $response;

        return [
            'success' => true,
            'message' => $pedido
        ];
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Exception: ' . $th->getMessage(),
        ];
    }
}

function isPedido()
{
    global $pedidoDAO;

    try {
        return $pedidoDAO->isPedido();
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao verificar se o usuário é um pedido: ' . $th->getMessage(),
        ];
    }
}

function getAllPedidos($pedido)
{
    global $pedidoDAO;
    try {
        return $pedidoDAO->read($pedido);
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Erro ao obter todos os pedidos: ' . $th->getMessage(),
        ];
    }
}

function addPedido($pedido)
{
    global $pedidoDAO;
    try {
        $pedido['disponivel'] = isset($pedido['disponivel']) && $pedido['disponivel'] ? 1 : 0;
        return $pedidoDAO->create($pedido);
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao adicionar a pedido: ' . $th->getMessage(),
        ];
    }
}
function deletePedido($pedido)
{
    global $pedidoDAO;
    try {

        $pedido['disponivel'] = isset($pedido['disponivel']) && $pedido['disponivel'] == 'Disponível' ? 1 : 0;
        return $pedidoDAO->delete($pedido);

    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao excluir a pedido: ' . $th->getMessage(),
        ];
    }
}

function updatePedido($pedido)
{
    global $pedidoDAO;
    try {

        $pedido['disponivel'] = isset($pedido['disponivel']) && $pedido['disponivel'] == 'Disponível' ? 1 : 0;
        return $pedidoDAO->update($pedido);

    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao atualizar a pedido: ' . $th->getMessage(),
        ];
    }
}
?>