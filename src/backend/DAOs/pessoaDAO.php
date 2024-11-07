<?php
header("Access-Control-Allow-Origin: http://localhost:3000"); // Allow only this origin
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true"); // Allow credentials
header("Content-Type: application/json");

include_once __DIR__ . '/../baseDAO.php';

$pessoaDAO = new pessoaDAO();

$jsonData = file_get_contents('php://input');
$requestData = json_decode($jsonData, true);

$postAction = isset($requestData['action']) ? $requestData['action'] : null;
$postData = isset($requestData['data']) ? $requestData['data'] : null;

$getAction = isset($_GET['action']) ? $_GET['action'] : null;
$getData = isset($_GET['data']) ? $_GET['data'] : null;


if (isset($getAction)) {

    switch ($getAction) {
        case 'read':
            $response = getAllpessoas($getData);
            echo json_encode($response);
            break;

        case 'isPessoa':
            $response = isPessoa();
            echo json_encode($response);
            break;

        case 'fetchAll':
            $response = getAllpessoas($getData ?? []);
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
            $response = addPessoa($postData);
            echo json_encode($response);
            break;
        case 'delete':
            $response = deletePessoa($postData);
            echo json_encode($response);
            break;
        case 'update':
            $response = updatePessoa($postData);
            echo json_encode($response);
            break;
    }
}

function getPessoa($pessoa)
{
    global $pessoaDAO;

    try {
        $password = $pessoa['senha'];
        unset($pessoa['senha']);

        $response = $pessoaDAO->read($pessoa);

        if (!$response['success'])
            return $response;

        $pessoaList = $response['message'];

        if (empty($pessoaList)) {
            return [
                'success' => false,
                'message' => 'Nenhuma pessoa encontrada com essas credenciais'
            ];
        }

        $pessoa = $pessoaList[0];

        $response = verifyPasswords($pessoa['senha'], $password);
        if (!$response['success'])
            return $response;

        return [
            'success' => true,
            'message' => $pessoa
        ];
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Exception: ' . $th->getMessage(),
        ];
    }
}

function isPessoa()
{
    global $pessoaDAO;

    try {
        return $pessoaDAO->isPessoa();
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao verificar se o usuário é um pessoa: ' . $th->getMessage(),
        ];
    }
}

function getAllPessoas($pessoa)
{
    global $pessoaDAO;
    try {
        return $pessoaDAO->read($pessoa);
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Erro ao obter todos os pessoas: ' . $th->getMessage(),
        ];
    }
}

function addPessoa($pessoa)
{
    global $pessoaDAO;
    try {
        return $pessoaDAO->create($pessoa);
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao adicionar a pessoa: ' . $th->getMessage(),
        ];
    }
}
function deletePessoa($pessoa)
{
    global $pessoaDAO;
    try {

        $pessoa['disponivel'] = isset($pessoa['disponivel']) && $pessoa['disponivel'] == 'Disponível' ? 1 : 0;
        return $pessoaDAO->delete($pessoa);

    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao excluir a pessoa: ' . $th->getMessage(),
        ];
    }
}

function updatePessoa($pessoa)
{
    global $pessoaDAO;
    try {

        $pessoa['disponivel'] = isset($pessoa['disponivel']) && $pessoa['disponivel'] == 'Disponível' ? 1 : 0;
        return $pessoaDAO->update($pessoa);

    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Falha ao atualizar a pessoa: ' . $th->getMessage(),
        ];
    }
}
?>