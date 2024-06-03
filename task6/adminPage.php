<?php
    session_start();

    include __DIR__.'/db_credentials.php';

    adminCheck($db);

    try {

        $dbFD = $db->query("SELECT * FROM Users");

        $stmt = $db->query("
            SELECT lang_id, COUNT(user_id) AS num_users
            FROM UserProgrammingLanguages
            GROUP BY lang_id
        ");
        $statistics = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 
    catch (PDOException $e) {
        echo "Ошибка: " . htmlspecialchars($e->getMessage());
        die();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="./libs/js/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" href="./assets/css/admin.css">
    <title>Страница администратора</title>
</head>
<body>

<h1>Данные пользователей</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Email</th>
                <th>Дата рождения</th>
                <th>Пол</th>
                <th>Биография</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            
            <?php
                while($row = $dbFD->fetch(PDO::FETCH_ASSOC)){
                    echo '<tr data-id='.$row['user_id'].'>
                    <td>'.$row['user_id'].'</td>
                    <td>'.$row['fullname'].'</td>
                    <td>'.$row['phone'].'</td>
                    <td>'.$row['email'].'</td>
                    <td>'.$row['dob'].'</td>
                    <td>'.$row['gender'].'</td>
                    <td>'.$row['bio'].'</td>
                    <td>';
                    
                    echo '</td>
                    <td><a href="user_profile.php?user_id='.$row['user_id'].'" target="_blank">Редактировать</a></td>
                    <td><button class="remove">Удалить</button></td>
                    <td colspan="10" class="form_del hidden">Форма удалена</td>
                </tr>';
                }
            ?>

        </tbody>
    </table>
    <h1>Статистика по языкам программирования</h1>
    <table>
        <thead>
            <tr>
                <th>Язык программирования</th>
                <th>Количество пользователей</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($statistics as $row): ?>
                <tr>
                    <td><?= $row['lang_id'] ?></td>
                    <td><?= $row['num_users'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <script src="del.js"></script>
</body>
</html>