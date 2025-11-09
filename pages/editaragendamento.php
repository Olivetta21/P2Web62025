<?php
session_start();
if(!isset($_SESSION["usuario"])){
    header("Location: login.php");
}

include("../database/connection.php");

$database = Database::getInstance();

$erro = "";
$agendamento = null;
if (isset($_GET["id"])) {
    $agendamento_id = $_GET["id"];

    $res = $database->read("agendamentos", ["id" => $agendamento_id, "usuario_id" => $_SESSION["usuario"]], 1);
    if ($res["success"] && count($res["data"]) > 0) {
        $agendamento = $res["data"][0];
    } else {
        $erro = "Erro ao buscar agendamento.";
    }

}

if ($_POST) {
    $data = $_POST["data_hora"] ?? "";
    $descricao = $_POST["descricao"] ?? "";

    if (empty($data) || empty($descricao)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        try {
            $res = $database->update("agendamentos", [
                "data_hora" => $data,
                "descricao" => $descricao
            ], ["id" => $agendamento["id"], "usuario_id" => $_SESSION["usuario"]]);
            if ($res["success"]) {
                header("Location: home.php");
                exit();
            } else {
                $erro = "Erro ao atualizar agendamento.";
            }
        } catch (\Throwable $th) {
            $erro = "Erro ao atualizar agendamento.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar agendamento</title>    
    <link rel="stylesheet" href="styles/globalstyles.css">
    <link rel="stylesheet" href="styles/forms.css">
    <link rel="stylesheet" href="styles/botao.css">
</head>

<body>
    <div class="form-container">
        <form method="POST">
            <h2>Editar Agendamento</h2>
            <div class="form-group">
                <?php if (!empty($erro)) : ?>
                    <div class="error-message"><?php echo $erro; ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="id">Editando agendamento ID:</label>
                <input type="text" id="id" name="id" value="<?php echo $agendamento["id"] ?? ""; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="data_hora">Data e Hora:</label>
                <input type="datetime-local" id="data_hora" name="data_hora" value="<?php echo $agendamento["data_hora"] ?? ""; ?>" required>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <input type="text" id="descricao" name="descricao" required value="<?php echo $agendamento["descricao"] ?? ""; ?>" required>
            </div>

            <button type="submit" class="btn login">Editar Agendamento</button>
        </form>
    </div>
</body>

</html>