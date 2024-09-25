<?php
// Conectar ao banco de dados
$host = 'localhost';
$db = 'plantigtech';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Preparar uma consulta para evitar SQL Injection
    $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($senha_hash);
        $stmt->fetch();

        // Verificar se a senha inserida corresponde ao hash
        if (password_verify($senha, $senha_hash)) {
            echo "Login bem-sucedido!";
            header("Location: paginaprincipal.html");
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "Usuário não encontrado!";
    }

    $stmt->close();
}