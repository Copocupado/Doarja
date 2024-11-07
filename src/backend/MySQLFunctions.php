<?php
include 'connection.php';

function getEntry($table, $data, $orderBy = [])
{
    global $conn;

    $table = $conn->real_escape_string($table);
    
    $conditions = [];
    foreach ($data as $column => $value) {
        $column = $conn->real_escape_string($column);
        $value = $conn->real_escape_string($value);
        $conditions[] = "$column = '$value'";
    }

    $whereClause = implode(' AND ', $conditions);

    $orderByClause = '';
    if (!empty($orderBy)) {
        $orderByParts = [];
        foreach ($orderBy as $column => $direction) {
            $column = $conn->real_escape_string($column);
            $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
            $orderByParts[] = "$column $direction";
        }
        $orderByClause = ' ORDER BY ' . implode(', ', $orderByParts);
    }

    $query = "SELECT * FROM $table" . (count($conditions) > 0 ? " WHERE $whereClause" : '') . $orderByClause;

    $result = $conn->query($query);

    if ($result) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return [
            'success' => true,
            'message' => $data,
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Erro ao obter entrada: ' . $conn->error,
        ];
    }
}

function getTable($table)
{
    global $conn;

    $table = $conn->real_escape_string($table);

    $query = "SELECT * FROM $table";

    $result = $conn->query($query);

    if ($result) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return [
            'success' => true,
            'message' => $data,
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Erro ao obter tabela: ' . $conn->error,
        ];
    }
}

function addEntry($table, $data)
{
    try {
        global $conn;

        $table = $conn->real_escape_string($table);

        $columns = [];
        $values = [];

        foreach ($data as $column => $value) {
            $columns[] = $conn->real_escape_string($column);
            $values[] = "'" . $conn->real_escape_string($value) . "'";
        }

        $query = "INSERT INTO $table (" . implode(',', $columns) . ") VALUES (" . implode(',', $values) . ")";

        if ($conn->query($query) === TRUE) {
            return [
                'success' => true,
                'message' => 'Documento adicionado com sucesso com o ID: ' . $conn->insert_id,
            ];
        } else {
            throw new Exception('Erro ao inserir o documento: ' . $conn->error);
        }
    } catch (Throwable $th) {
        return [
            'success' => false,
            'message' => 'Exception: ' . $th->getMessage(),
        ];
    }
}

function deleteEntry($table, $data)
{
    try {
        global $conn;

        $table = $conn->real_escape_string($table);

        $conditions = [];
        foreach ($data as $field => $value) {
            $field = $conn->real_escape_string($field);
            $value = $conn->real_escape_string($value);
            $conditions[] = "$field = '$value'";
        }

        $whereClause = implode(' AND ', $conditions);

        $query = "DELETE FROM $table" . (count($conditions) > 0 ? " WHERE $whereClause" : '');

        if ($conn->query($query) === TRUE) {
            return [
                'success' => true,
                'message' => 'Documento removido com sucesso.',
            ];
        } else {
            throw new Exception('Erro ao remover o documento: ' . $conn->error);
        }
    } catch (Throwable $th) {
        return [
            'success' => false,
            'message' => 'Exception: ' . $th->getMessage(),
        ];
    }
}


function updateEntry($table, $whereField, $whereValue, $data)
{
    try {
        global $conn;

        $table = $conn->real_escape_string($table);
        $whereField = $conn->real_escape_string($whereField);
        $whereValue = $conn->real_escape_string($whereValue);

        $updates = [];
        foreach ($data as $column => $value) {
            $column = $conn->real_escape_string($column);
            $value = $conn->real_escape_string($value);
            $updates[] = "$column = '$value'";
        }

        $query = "UPDATE $table SET " . implode(', ', $updates) . " WHERE $whereField = '$whereValue'";

        if ($conn->query($query) === TRUE) {
            return [
                'success' => true,
                'message' => 'Documento atualizado com sucesso.',
            ];
        } else {
            throw new Exception('Erro ao atualizar o documento: ' . $conn->error);
        }
    } catch (Throwable $th) {
        return [
            'success' => false,
            'message' => 'Exception: ' . $th->getMessage(),
        ];
    }
}

function verifyPasswords($hashedPassword, $password)
{
    if (password_verify($password, $hashedPassword)) {
        return [
            'success' => true,
            'message' => 'Senha verificada com sucesso.',
        ];
    } else {
        return [
            'success' => false,
            'message' => 'As senhas não coincidem',
        ];
    }
}
?>