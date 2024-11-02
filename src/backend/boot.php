<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

    include 'config.php';

    $conn = new mysqli($host, $usuario, $senha);

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    $conn->query("CREATE SCHEMA IF NOT EXISTS $banco");
    $conn->select_db($banco);

    $sql = "CREATE TABLE IF NOT EXISTS admins (
            email VARCHAR(255) PRIMARY KEY,
            senha VARCHAR(255) NOT NULL,
            nome VARCHAR(255) NOT NULL,
            ativo BOOLEAN NOT NULL,
            foto_de_perfil VARCHAR(255) NOT NULL
        )";

    if ($conn->query($sql) === TRUE) {
        echo "Tabela 'admins' criada com sucesso.";
    } else {
        echo "Erro ao criar a tabela: " . $conn->error;
    }

    $email = 'root';
    $password = 'root';
    $name = 'Root';
    $active = true;

    $result = $conn->query("SELECT * FROM admins WHERE email = '$email'");

    if ($result->num_rows == 0) {
        $imageFilePath = '/src/assets/root-user-pfp.png';
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insertSql = "INSERT INTO admins (email, senha, nome, ativo, foto_de_perfil) VALUES ('$email', '$hashedPassword', '$name', $active, '$imageFilePath')";
        $conn->query($insertSql) === TRUE;
    }

    $conn->close();
?>
