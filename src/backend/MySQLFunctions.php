<?php
    include 'connection.php';
    function getEntry($table, $column, $value) {
        global $conn;

        $table = $conn->real_escape_string($table);
        $column = $conn->real_escape_string($column);
        $value = $conn->real_escape_string($value);
    
        $query = "SELECT * FROM $table WHERE $column = '$value'";
    
        $result = $conn->query($query);
    
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    function getTable($table) {
        global $conn;

        $table = $conn->real_escape_string($table);
    
        $query = "SELECT * FROM $table";
    
        $result = $conn->query($query);
    
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    function addEntry($table, $data) {
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
                throw new Exception('Erro ao inserir o documento' . $conn->error);
            }
        } catch (Throwable $th) {
            return [
                'success' => false,
                'message' => 'Exception: ' . $th->getMessage(),
            ];
        }
    }

    function deleteEntry($table, $field, $value) {
        try {
            global $conn;
    
            $table = $conn->real_escape_string($table);
            $field = $conn->real_escape_string($field);
            $value = $conn->real_escape_string($value);
    
            $query = "DELETE FROM $table WHERE $field = '$value'";
    
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

    function updateEntry($table, $whereField, $whereValue, $data) {
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
    
            // Formulate the UPDATE query
            $query = "UPDATE $table SET " . implode(', ', $updates) . " WHERE $whereField = '$whereValue'";
    
            // Execute the query
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
    
    function verifyPasswords($hashedPassword, $password){
        return password_verify($password, $hashedPassword);
    }
?>