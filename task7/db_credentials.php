<?php
    define('DB_HOST', 'localhost'); // Хост базы данных
    define('DB_USER', 'u67405'); // Логин для подключения к базе данных
    define('DB_PASSWORD', '6654322'); // Пароль для подключения к базе данных
    define('DB_NAME', 'u67405'); // Имя базы данных
    try 
    {
        $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD,
            [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        
    } 
    catch (PDOException $e) 
    {
        echo 'Подключение не удалось: ' . htmlspecialchars($e->getMessage());
        exit;
    }
   
    function adminCheck($db)
    {
        if(empty($_SESSION['username']))
        {
            echo "ВЫ НЕ АВТОРИЗОВАНЫ";
            exit();
        }
        if(!empty($_SESSION['username']))
        {
            $username = $_SESSION['username'];
            $stmt = $db->prepare("SELECT * FROM Users WHERE username = ? and admin = 1");
            $stmt->execute([$username]);
            $userAdmin = $stmt->fetch(PDO::FETCH_ASSOC);
            if(empty($userAdmin))
            {
                echo "ВЫ НЕ АДМИНИСТРАТОР";
                exit();
            }
        }
    }
?>