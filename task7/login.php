<?php
    session_start();
    include __DIR__.'/db_credentials.php';

    $loginReg = '/^[a-zA-Z_0-9]+$/';
    $passReg = '/^[a-zA-Z0-9]+$/';
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        if(isset($_POST['enterAcc'])) 
        {
            $login = $_POST['login'];
            $password = $_POST['password'];

            if(!preg_match($loginReg,$login) or !preg_match($passReg,$password))
            {
                echo "НЕКОРРЕКТНЫЕ СИМВОЛЫ";
                exit();
            }

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