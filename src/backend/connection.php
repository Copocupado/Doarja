<?php
    include 'config.php';

    $conn = new mysqli($host, $usuario, $senha, $banco);
    
    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

?>