<?php
session_start();
include("../database/connection.php");

$database = Database::getInstance();

$erro = "";
$criadoComSucesso = false;
if ($_POST) {
    $nome = $_POST["nome"] ?? "";
    $email = $_POST["email"] ?? "";
    $senha = md5($_POST["senha"]) ?? "";

    if (empty(trim($nome)) || empty(trim($email)) || empty(trim($senha))) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        try {
            $resultado = $database->create("usuarios", ["nome" => $nome, "email" => $email, "senha" => $senha]);
            if ($resultado["success"]) {
                $criadoComSucesso = true;
            } else {
                $erro = "Erro ao criar usuário. Tente outro email";
            }
        } catch (\Throwable $th) {
            $erro = "erro no sistema.";
        }
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuario</title>
    <link rel="stylesheet" href="styles/globalstyles.css">
    <link rel="stylesheet" href="styles/forms.css">
    <link rel="stylesheet" href="styles/botao.css">
</head>

<body>
    <?php if ($criadoComSucesso): ?>
        <div class="info">Usuário criado com sucesso! <a href="login.php">Faça login aqui</a>.</div>
    <?php else: ?>
        <div class="form-container">
            <form method="POST">
                <h2>Cadastro de Usuário</h2>
                <div class="form-group">
                    <?php if (!empty($erro)) : ?>
                        <div class="error-message"><?php echo $erro; ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>

                <button type="submit" class="btn login">Criar</button>
            </form>
        </div>
    <?php endif; ?>
</body>

</html>