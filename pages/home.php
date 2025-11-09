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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    Vocês está logado!!!! <a href="?logout=1"> Sair</a>
</body>
</html>