<?php
    include 'formhandler.php';

    $errors = isset($_COOKIE['errors']) ? json_decode($_COOKIE['errors'], true) : [];

    echo isset($_COOKIE['errors']);


    if(!empty($_SESSION['username']))
    {
        if(isset($_COOKIE['username']) and $_SESSION['username'] == $_COOKIE['username'])
        {
            //БРАТЬ ИЗ COOKIE ДАННЫЕ ФОРМЫ
            $fullname_cookie = isset($_COOKIE['fullname']) ? $_COOKIE['fullname'] : '';
            $phone_cookie = isset($_COOKIE['phone']) ? $_COOKIE['phone'] : '';
            $email_cookie = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';
            $bio_cookie = isset($_COOKIE['bio']) ? $_COOKIE['bio'] : '';
            $dob_cookie = isset($_COOKIE['dob']) ? $_COOKIE['dob'] : '';
            $radio1 = isset($_COOKIE['radio1']);
            $radio2 = isset($_COOKIE['radio2']);
            $contact_cookie = isset($_COOKIE['contact']);
            $languages_cookie = isset($_COOKIE['selected_languages']) ? json_decode($_COOKIE['selected_languages'], true) : [];
        }
        else
        {
            //БРАТЬ ИЗ БД ДАННЫЕ ФОРМЫ
            $stmt = $db->prepare("SELECT * FROM Users WHERE username = ?");
            $stmt->execute([$_SESSION["username"]]);
            $fet = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
            $user_id = $fet['user_id'];
            $fullname_cookie = $fet['fullname'];
            $phone_cookie = $fet['phone'];
            $email_cookie = $fet['email'];
            $bio_cookie = $fet['bio'];
            $dob_cookie = $fet['dob'];
            if($fet['gender'] == 'male')
            {
                $radio1 = true;
                $radio2 = false;
            }
            else
            {
                $radio1 = false;
                $radio2 = true;
            }
            $stmt = $db->prepare("SELECT lang_id FROM UserProgrammingLanguages WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $languages_cookie = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $contact_cookie = true;
        }
    }
    else
    {
        //НОВЫЙ ПОЛЬЗОВАТЕЛЬ
        $fullname_cookie = '';
        $phone_cookie = '';
        $email_cookie = '';
        $bio_cookie = '';
        $dob_cookie = '';
        $radio1 = '';
        $radio2 = '';
        $contact_cookie = '';
        $languages_cookie = [];
    }


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="assets/css/style.css" type="text/css" />
    <title>Форма</title>
</head>

<body class="form-body">
    <div class="form-container">
        <h2>Форма</h2>
        <form method="POST" id="myForm" action="formhandler.php">
            <div class="form-group input-control">
                <label for="fullname" class="form-label">ФИО:</label>
                <input type="text" id="fullname" name="fullname" class="form-input"
                    value="<?php echo htmlspecialchars($fullname_cookie); ?>"
                    <?php echo isset($errors['fullname']) ? 'style="border-color: red;"' : ''; ?> />

                <?php echo isset($errors['fullname']) ? $errors['fullname'] : ''; ?>
            </div>
            <div class=" form-group input-control">
                <label for="phone" class="form-label">Телефон:</label>
                <input type="tel" id="phone" name="phone" class="form-input"
                    value="<?php echo htmlspecialchars($phone_cookie); ?>"
                    <?php echo isset($errors['phone']) ? 'style="border-color: red;"' : ''; ?> />
                <?php echo isset($errors['phone']) ? $errors['phone'] : ''; ?>
            </div>
            <div class="form-group input-control">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" id="email" name="email" class="form-input"
                    value="<?php echo htmlspecialchars($email_cookie); ?>"
                    <?php echo isset($errors['email']) ? 'style="border-color: red;"' : ''; ?> />
                <?php echo isset($errors['email']) ? $errors['email'] : ''; ?>
            </div>
            <div class="form-group input-control">
                <label for="dob" class="form-label">Дата рождения:</label>
                <input type="date" id="dob" name="dob" class="form-input"
                    value="<?php echo htmlspecialchars($dob_cookie); ?>"
                    <?php echo isset($errors['dob']) ? 'style="border-color: red;"' : ''; ?> />
                <?php echo isset($errors['dob']) ? $errors['dob'] : ''; ?>
            </div>
            <div class="form-group input-control">
                <label class="form-label">Пол:</label>
                <input type="radio" id="male" name="gender" <?php if($radio1 == 1) echo 'checked'; ?> value="male" class="form-radio">
                 <label for="male">Мужской</label>
                <input type="radio" id="female" name="gender" <?php if($radio2 == 1) echo 'checked'; ?> value="female" class="form-radio">
                    <label for="female">Женский</label>
                <?php echo isset($errors['gender']) ? $errors['gender'] : ''; ?>
            </div>
            <div class="form-group input-control">

                <label for="languages" class="form-label">Любимый язык программирования:</label>
                <select id="languages" name="languages[]" class="form-input" multiple
                    <?php echo isset($errors['languages']) ? 'style="border-color: red;"' : ''; ?>>
                    <option value="Pascal" <?php echo in_array('Pascal', $languages_cookie) ? 'selected' : ''; ?>>Pascal
                    </option>
                    <option value="C" <?php echo in_array('C', $languages_cookie) ? 'selected' : ''; ?>>C</option>
                    <option value="C++" <?php echo in_array('C++', $languages_cookie) ? 'selected' : ''; ?>>C++</option>
                    <option value="JavaScript"
                        <?php echo in_array('JavaScript', $languages_cookie) ? 'selected' : ''; ?>>JavaScript</option>
                    <option value="PHP" <?php echo in_array('PHP', $languages_cookie) ? 'selected' : ''; ?>>PHP</option>
                    <option value="Python" <?php echo in_array('Python', $languages_cookie) ? 'selected' : ''; ?>>Python
                    </option>
                    <option value="Java" <?php echo in_array('Java', $languages_cookie) ? 'selected' : ''; ?>>Java
                    </option>
                    <option value="Haskell" <?php echo in_array('Haskell', $languages_cookie) ? 'selected' : ''; ?>>
                        Haskell</option>
                    <option value="Clojure" <?php echo in_array('Clojure', $languages_cookie) ? 'selected' : ''; ?>>
                        Clojure</option>
                    <option value="Prolog" <?php echo in_array('Prolog', $languages_cookie) ? 'selected' : ''; ?>>Prolog
                    </option>
                    <option value="Scala" <?php echo in_array('Scala', $languages_cookie) ? 'selected' : ''; ?>>Scala
                    </option>
                </select>
                <?php echo isset($errors['languages']) ? $errors['languages'] : ''; ?>
            </div>
            <div class="form-group input-control">
                <label for="bio" class="form-label">Биография:</label><br />
                <textarea id="bio" name="bio" class="form-input"
                    <?php echo isset($errors['bio']) ? 'style="border-color: red;"' : ''; ?>><?php echo htmlspecialchars($bio_cookie); ?></textarea>
                <?php echo isset($errors['bio']) ? $errors['bio'] : ''; ?>
            </div>
            <div class="form-group input-control">
                <input type="checkbox" id="contact" name="contact" class="form-checkbox" <?php if($contact_cookie == 1) echo 'checked'; ?>
                    <label for="contact" class="form-label">С контрактом ознакомлен</label>
                <?php echo isset($errors['contact']) ? $errors['contact'] : ''; ?>
            </div>
            <input type="submit" class="form-button" value="Отправить" />
        </form>

    </div>

</body>

</html>