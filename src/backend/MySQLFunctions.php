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
    function verifyPasswords($hashedPassword, $password){
        return password_verify($password, $hashedPassword);
    }
?>