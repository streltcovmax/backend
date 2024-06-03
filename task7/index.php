<?php
    session_start();
    ini_set('display_errors', 'Off');
    // header("Location: index.php"); 

    // Проверяем, если пользователь не вошел в систему, перенаправляем его на страницу авторизации
    //if (!isset($_SESSION['username'])) {
    //    header("Location: form.php");
    //    exit();
    //}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/css/main.css">
        <title>Авторизация</title>
    </head>
    <body>
        <form action="login.php" method="POST">
            <input type="text" name="login" placeholder="Введите свой логин">
            <input type="password" name="password" placeholder="Введите пароль">
            <button type="submit" name="enterAcc">Войти</button><br>
            <button type="submit" name="createAcc">Создать аккаунт</button>
            
        </form>
    </body>
</html>