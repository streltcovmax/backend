<?php
include 'db_credentials.php';

// Исправлено с GET 
$user_id = $_POST['user_id'];

if (!isset($user_id)) {
    echo "Ошибка: user_id не передан.";
    exit;
}

try {
    // Удаляем все записи о языках программирования, связанные с этим пользователем
    $stmt = $db->prepare("DELETE FROM UserProgrammingLanguages WHERE user_id = ?");
    $stmt->execute([$user_id]);

    // Теперь можно удалить пользователя
    $stmt = $db->prepare("DELETE FROM Users WHERE user_id = ?");
    $stmt->execute([$user_id]);

    header("Location: adminPage.php");
    exit;
} catch (PDOException $e) {
    echo "Ошибка удаления пользователя: " . $e->getMessage();
    exit;
}
?>
