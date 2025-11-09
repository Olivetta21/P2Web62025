<?php 
    session_start();
    if(!isset($_SESSION["usuario"])){
        header("Location: login.php");
    }

    if(isset($_GET["logout"])){
        session_destroy();
        header("Location: login.php");
        exit();
    }

    include("../database/connection.php");

    $database = Database::getInstance();

    $erro = "";
    if ($_POST) {
        $tipo = $_POST["tipo"] ?? "";
        $agendamento_id = $_POST["agendamento_id"] ?? "";

        switch ($tipo) {
            case 'criar_agendamento':
                header("Location: criaragendamento.php");
                break;
            case 'editar_agendamento':
                header("Location: editaragendamento.php?id=" . $agendamento_id);
                break;
            case 'deletar_agendamento':
                $database->delete("agendamentos", ["id" => $agendamento_id, "usuario_id" => $_SESSION["usuario"]]);
                break;
        }
    }

    $agendamentos = [];
    try {
        $res = $database->read("agendamentos", ["usuario_id" => $_SESSION["usuario"]]);
        if ($res["success"]) {
            $agendamentos = $res["data"];
            usort($agendamentos, function($a, $b) {
                return strtotime($a["data_hora"]) - strtotime($b["data_hora"]);
            });
        } else {
            $erro = "Erro ao buscar agendamentos.";
        }
    } catch (\Throwable $th) {
        $erro = "Email no sistema.";
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/homepage.css">
    <link rel="stylesheet" href="styles/botao.css">
</head>
<body>
    <h1 class="header"> 
        <p> Agendamentos </p>
        <form method="POST">
            <input type="hidden" name="tipo" value="criar_agendamento">
            <button type="submit" class="btn">Criar Agendamento</button>
            <a href="?logout=1"> Logoff </a>
        </form>
    </h1>
    <div class="agendamentos-container">
        <?php foreach($agendamentos as $agendamento): ?>
            <div class="agendamento-item">
                <p>ID: <?php echo $agendamento["id"]; ?></p>
                <p>Data e Hora: <?php echo $agendamento["data_hora"]; ?></p>
                <p>Descrição: <?php echo $agendamento["descricao"]; ?></p>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="tipo" value="editar_agendamento">
                    <input type="hidden" name="agendamento_id" value="<?php echo $agendamento["id"]; ?>">
                    <button type="submit" class="btn edit">Editar</button>
                </form>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="tipo" value="deletar_agendamento">
                    <input type="hidden" name="agendamento_id" value="<?php echo $agendamento["id"]; ?>">
                    <button type="submit" class="btn delete">Deletar</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>