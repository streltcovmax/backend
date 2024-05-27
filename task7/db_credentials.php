<?php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'u67405');
    define('DB_PASSWORD', '6654322');
    define('DB_NAME', 'u67405');
    try {
        $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD,
            [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    } catch (PDOException $e) {
        echo 'Подключение не удалось: ' . $e->getMessage();
        exit;
    }

?>