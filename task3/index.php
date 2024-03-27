<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Задание 3</title>
  </head>
  <body>
    

<?php

header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!empty($_GET['save'])) {

    print('Спасибо, результаты сохранены.');
  }
  include('form.php');
  exit();
}


function errp($error){
  print("<div class='messageError'>$error</div>");
  exit();
}

function val_empty($val, $name, $o = 0){
  if(empty($val)){
    if($o == 0){
      errp("Заполните поле $name.<br/>");
    }
    if($o == 1){
      errp("Выберите $name.<br/>");
    }
    if($o == 2){
      errp("ознакомьтесь с контрактом<br/>");
    }
    exit();
  }
}

$errors = '';
$name = (isset($_POST['name']) ? $_POST['name'] : '');
$number = (isset($_POST['number']) ? $_POST['number'] : '');
$email = (isset($_POST['email']) ? $_POST['email'] : '');
$data = (isset($_POST['data']) ? strtotime($_POST['data']) : '');
$radio = (isset($_POST['radio']) ? $_POST['radio'] : '');
$lang = (isset($_POST['lang']) ? $_POST['lang'] : '');
$biography = (isset($_POST['biography']) ? $_POST['biography'] : '');
$check_mark = (isset($_POST['check_mark']) ? $_POST['check_mark'] : '');


$number = preg_replace('/\D/', '', $number);
  
$langs = ($lang != '') ? implode(", ", $lang) : [];

val_empty($name, "имя");
val_empty($number, "телефон");
val_empty($email, "email");
val_empty($data, "дата");
val_empty($radio, "пол", 1);
val_empty($lang, "языки", 1);
val_empty($biography, "биография");
val_empty($check_mark, "ознакомлен", 2);

if(empty($name)){
  print('пустое поле фио');
}

if(strlen($name) > 255){
  $errors = 'Длина поля "ФИО" > 255 символов';
}
elseif(count(explode(" ", $name)) < 2){
  $errors = 'Неверный формат ФИО';
} 
elseif(strlen($number) != 11){
  $errors = 'Неверное значение поля "Телефон"';
}
elseif(strlen($email) > 255){
  $errors = 'Длина поля "email" > 255 символов';
}
elseif(!preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/', $email)){
  $errors = 'Неверное значение поля "email"';
}
elseif(!is_numeric($data) || strtotime("now") < $data){
  $errors = 'Укажите корректно дату';
}
elseif($radio != "m" && $radio != "f"){
  $errors = 'Укажите пол';
}
elseif(count($lang) == 0){
  $errors = 'Укажите языки';
}

if ($errors != '') {
  errp($errors);
}

$db = new PDO('mysql:host=localhost;dbname=u67405', 'u67405', '6654322',
     [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$inQuery = implode(',', array_fill(0, count($lang), '?'));


try {
  $dbLangs = $db->prepare("SELECT id, name FROM languages WHERE name IN ($inQuery)");
  foreach ($lang as $key => $value) {
    $dbLangs->bindValue(($key+1), $value);
  }
  $dbLangs->execute();
  $languages = $dbLangs->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}

echo $dbLangs->rowCount().'**'.count($lang);

if($dbLangs->rowCount() != count($lang)){
  $errors = 'Неверно выбраны языки';
}
elseif(strlen($biography) > 65535){
  $errors = 'Длина поля "Биография" > 65 535 символов';
}

if ($errors != '') {
  errp($errors);
}

try {
  $stmt = $db->prepare("INSERT INTO form_data (name, number, email, data, radio, biography) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->execute([$name, $number, $email, $data, $radio, $biography]);
  $fid = $db->lastInsertId();
  $stmt1 = $db->prepare("INSERT INTO form_data_lang (id_form, id_lang) VALUES (?, ?)");
  foreach($languages as $row){
      $stmt1->execute([$fid, $row['id']]);
  }
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}

header('Location: ?save=1');