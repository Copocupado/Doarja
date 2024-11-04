<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

session_start();

$jsonData = file_get_contents('php://input');
$requestData = json_decode($jsonData, true);

$postAction = isset($requestData['action']) ? $requestData['action'] : null;
$postData = isset($requestData['data']) ? $requestData['data'] : null;

$getAction = isset($_GET['action']) ? $_GET['action'] : null;
$getData = isset($_GET['data']) ? $_GET['data'] : null;

if (isset($getAction)) {
    switch ($getAction) {
        case 'getSessionData':
            $data = getSessionData();
            echo json_encode($data);
            break;
    }
}

if (isset($postAction)) {
    switch ($postAction) {
        case 'saveSessionData':
            $result = saveSessionData($postData);
            echo json_encode($result);
            break;
        case 'destroySession':
            $result = destroySession();
            echo json_encode($result);
            break;
    }
}

function saveSessionData($data)
{
    try {
        $_SESSION['data'] = $data;
        return [
            'success' => true,
            'message' => 'Dados salvos com sucesso'
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Erro ao salvar os dados: ' . $e->getMessage()
        ];
    }
}

function getSessionData()
{
    try {
        if (isset($_SESSION['data'])) {
            return [
                'success' => true,
                'message' => $_SESSION['data']
            ];
        } else {
            return [
                'success' => false,
                'message' => 'No session data found.'
            ];
        }
    } catch (\Throwable $th) {
        return [
            'success' => false,
            'message' => 'Erro ao obter os dados da sessao: ' . $th->getMessage()
        ];
    }
}

function destroySession()
{
    try {
        $_SESSION['data'] = null;
        session_unset();
        session_destroy();
        return [
            'success' => true,
            'message' => 'Sessao destruida com sucesso'
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Erro ao destruir a sessao: ' . $e->getMessage()
        ];
    }
}
?>