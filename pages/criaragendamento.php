<?php
session_start();
if(!isset($_SESSION["usuario"])){
    header("Location: login.php");
}

include("../database/connection.php");

$database = Database::getInstance();

$erro = "";
if ($_POST) {
    $data = $_POST["data_hora"] ?? "";
    $descricao = $_POST["descricao"] ?? "";

    if (empty($data) || empty($descricao)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        try {
            $res = $database->create("agendamentos", [
                "usuario_id" => $_SESSION["usuario"],
                "data_hora" => $data,
                "descricao" => $descricao
            ]);
            if ($res["success"]) {
                header("Location: home.php");
                exit();
            } else {
                $erro = "Erro ao criar agendamento.";
            }
        } catch (\Throwable $th) {
            $erro = "Erro ao criar agendamento.";
        }
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar agendamento</title>    
    <link rel="stylesheet" href="styles/globalstyles.css">
    <link rel="stylesheet" href="styles/forms.css">
    <link rel="stylesheet" href="styles/botao.css">
</head>

<body>
    <div class="form-container">
        <form method="POST">
            <h2>Criar Agendamento</h2>
            <div class="form-group">
                <?php if (!empty($erro)) : ?>
                    <div class="error-message"><?php echo $erro; ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="data_hora">Data e Hora:</label>
                <input type="datetime-local" id="data_hora" name="data_hora" required>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <input type="text" id="descricao" name="descricao" required>
            </div>

            <button type="submit" class="btn login">Criar Agendamento</button>
        </form>
    </div>
</body>

</html>