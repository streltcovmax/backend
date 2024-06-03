<?php
    session_start();
    include 'db_credentials.php';
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        if(isset($_POST['enterAcc'])) 
        {
            $login = $_POST['login'];
            $password = $_POST['password'];

            $stmt = $db->prepare("SELECT * FROM Users WHERE username = ?");
            $stmt->execute([$login]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) 
            {
                $_SESSION['username'] = $login;
                if ($user['admin'] == 1) 
                {
                    header("Location: adminPage.php"); 
                    exit();
                } 
                else 
                {
                    unset($_COOKIE['errors']);
                    setcookie('errors', null, -1, '/');
                    header("Location: form.php"); 
                    exit();
                }
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