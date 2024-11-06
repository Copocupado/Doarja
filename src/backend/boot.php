<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include 'config.php';

$response = [
    "success" => true,
    "message" => "Banco de dados incializado com sucesso"
];

$conn = new mysqli($host, $usuario, $senha);

if ($conn->connect_error) {
    $response["success"] = false;
    $response["message"] = "Erro de conexão: " . $conn->connect_error;
    echo json_encode($response);
    exit();
}

$conn->query("CREATE SCHEMA IF NOT EXISTS $banco");
$conn->select_db($banco);

$sql = "
    CREATE TABLE IF NOT EXISTS admins (
        email VARCHAR(255) PRIMARY KEY,
        senha VARCHAR(255) NOT NULL,
        nome VARCHAR(255) NOT NULL,
        ativo BOOLEAN NOT NULL,
        foto_de_perfil VARCHAR(255) NOT NULL
    )
";

if ($conn->query($sql) !== TRUE) {
    $response["success"] = false;
    $response["message"] = "Erro ao criar a tabela 'admins': " . $conn->error;
} 

$sql = "
    CREATE TABLE IF NOT EXISTS entidades (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(255) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL,
        endereco VARCHAR(255) NOT NULL,
        telefone VARCHAR(15) NOT NULL
    )
";

if ($conn->query($sql) !== TRUE) {
    $response["success"] = false;
    $response["message"] = "Erro ao criar a tabela 'entidades': " . $conn->error;
}

$sql = "
    CREATE TABLE IF NOT EXISTS itens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        idEntidade INT,
        descricao VARCHAR(255) NOT NULL,
        quantidade INT NOT NULL,
        disponivel BOOLEAN NOT NULL,
        FOREIGN KEY (idEntidade) REFERENCES entidades(id) ON DELETE CASCADE
    )
";

if ($conn->query($sql) !== TRUE) {
    $response["success"] = false;
    $response["message"] = "Erro ao criar a tabela 'itens': " . $conn->error;
}

$sql = "
    CREATE TABLE IF NOT EXISTS pedidos (
        id INT PRIMARY KEY AUTO_INCREMENT,
        idEntidade INT NOT NULL,
        idItem INT NOT NULL,
        quantidade INT NOT NULL,
        nomeRecebedor VARCHAR(255) NOT NULL,
        telefoneRecebedor VARCHAR(20) NOT NULL,
        data DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (idEntidade) REFERENCES entidades(id),
        FOREIGN KEY (idItem) REFERENCES itens(id)
    )
";

if ($conn->query($sql) !== TRUE) {
    $response["success"] = false;
    $response["message"] = "Erro ao criar a tabela 'pedidos': " . $conn->error;
}

else {
    $email = 'root';
    $password = 'root';
    $name = 'Root';
    $active = true;

    $result = $conn->query("SELECT * FROM admins WHERE email = '$email'");

    if ($result->num_rows == 0) {
        $imageFilePath = '/src/assets/root-user-pfp.png';
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insertSql = "INSERT INTO admins (email, senha, nome, ativo, foto_de_perfil) VALUES ('$email', '$hashedPassword', '$name', $active, '$imageFilePath')";

        if ($conn->query($insertSql) !== TRUE) {
            $response["success"] = false;
            $response["message"] = "Erro ao adicionar o administrador 'root': " . $conn->error;
        }
    }
}

$conn->close();

echo json_encode($response);
?>