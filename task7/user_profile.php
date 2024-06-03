<?php
    include __DIR__.'/db_credentials.php';

    adminCheck($db);

    $user_id = isset($_GET['user_id']) ? htmlspecialchars($_GET['user_id']) : '';

    if($user_id)
    {
        $stmt = $db->prepare("SELECT * FROM Users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo "Пользователь не найден.";
            exit();
        }
    
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            function test_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

            $new_fullname = test_input($_POST['fullname']);
            $new_phone = test_input($_POST['phone']);
            $new_email = test_input($_POST['email']);
    
            $stmt = $db->prepare("UPDATE Users SET fullname = ?, phone = ?, email = ? WHERE user_id = ?");
            $stmt->execute([$new_fullname, $new_phone, $new_email, $user_id]);
            header("Location: user_profile.php?user_id=$user_id");
            
            exit();
        }
    }
    else
    {
        header("Location: adminPage.php");
        exit;
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Данные пользователя</title>
    </head>
    <body>
        <h1>Редактировать данные пользователя</h1>
        <form action="" method="POST">
            <label>Имя:</label>
            <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>">
            <label>Телефон:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
            <button type="submit">Сохранить изменения</button>
        </form>
    </body>
</html>
