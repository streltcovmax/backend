<?php
    session_start();
    include 'db_credentials.php';
    
    // Проверяем, если форма была отправлена
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        if(isset($_POST['enterAcc'])) 
        {
            // Получаем введенные пользователем логин и пароль
            $login = $_POST['login'];
            $password = $_POST['password'];

            // Выполняем запрос к базе данных, чтобы найти пользователя с указанным логином
            $stmt = $db->prepare("SELECT * FROM Users WHERE username = ?");
            $stmt->execute([$login]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Если пользователь найден и хеш пароля совпадает, устанавливаем сессию для него
            if ($user && password_verify($password, $user['password'])) 
            {
                $_SESSION['username'] = $login;
                unset($_COOKIE['errors']);
                setcookie('errors', null, -1, '/');
                header("Location: form.php"); 
                exit();
            } 
            else 
            {
                // Пользователь не найден
                //header("Location: index.php");
                echo "<p style='color:red;'>", "Неправильный логин или пароль!";
                exit();
            }
        }
        elseif (isset($_POST['createAcc'])) 
        {
            session_destroy();
            unset($_COOKIE['username']);
            setcookie('username', null, -1, '/');
            header("Location: form.php");
            exit();
        }
    }
?>