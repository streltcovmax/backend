<?php
    include __DIR__.'/db_credentials.php';

    $user_id = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT);

    if ($user_id === null || $user_id === false) {
        echo "Ошибка: некорректный user_id.";
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
    } 
    catch (PDOException $e) {
        echo "Ошибка удаления пользователя: " . htmlspecialchars($e->getMessage());
        exit;
    }
?>
