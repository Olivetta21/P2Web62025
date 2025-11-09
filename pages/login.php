<?php
session_start();
if(isset($_SESSION["usuario"])){
    header("Location: home.php");
}

include("../database/connection.php");

$database = Database::getInstance();

$erro = "";
if ($_POST) {
    $email = $_POST["email"] ?? "";
    $senha = md5($_POST["senha"]) ?? "";

    if (empty($email) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        try {
            $usuario = $database->read("usuarios", ["email" => $email, "senha" => $senha], 1);
            if (count($usuario["data"]) > 0) {
                $_SESSION["usuario"] = $usuario["data"][0]["id"];
                header("Location: home.php");
                exit();
            } else {
                $erro = "Email ou senha incorretos.";
            }
        } catch (\Throwable $th) {
            $erro = "Email no sistema.";
        }
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>    
    <link rel="stylesheet" href="styles/forms.css">
    <link rel="stylesheet" href="styles/botao.css">
</head>

<body>
    <div class="form-container">
        <form method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>

            <button type="submit" class="btn login">Entrar</button>
            <a href="cadastrarusuario.php">Faça cadastro aqui</a>.
        </form>
    </div>
</body>

</html>