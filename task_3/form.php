<?php

// теперь уже идет валидация посредством PHP
// $_POST - один из суперглобальных массивов, инициализирован всегда
if (empty($_POST['name'])) {
    // создаем HTTP header
    header("HTTP/1.1 400 Name is not set");
    // exit() завершает выполнение скрипта
    exit();
}
// встроенная в php функция для валидации email
if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    header("HTTP/1.1 400 Mail is not set or is invalid");
    exit();
}
if (empty($_POST['year'])) {
    header("HTTP/1.1 400 Year is not set");
    exit();
}
if (empty($_POST['limbs'])) {
    header("HTTP/1.1 400 Limbs number is not set");
    exit();
}
// здесь применяем isset (проверка на NULL), т.к. empty выдаст true на '0', а у нас gender = {0, 1}
if (!isset($_POST['gender']) || ($_POST['gender'] != 0 && $_POST['gender'] != 1)) {
    header("HTTP/1.1 400 Gender is not set or is invalid");
    exit();
}


$user = 'u53004';
$pass = '1535364';
// экземпляр класса PHP Data Objects для взаимодействия с бд
// :: - scope resolution operator, обратиться к полю класса
// в кач-ве значения он должен быть true для постоянного соединения с бд
// => исп-тся для задания значений массива []
$db = new PDO('mysql:host=localhost;dbname=u53004', $user, $pass, [PDO::ATTR_PERSISTENT => true]);

try {
    // вызываем (-> - аналог . в Java для доступа к полям и методам класса)
    // создаем с помощью prepare подготовленный стейтмент (шаблон SQL-запроса)
    // prepare() вернет PDOStatement
    $stmt = $db -> prepare("INSERT INTO Person (p_name, mail, year, gender, limbs_num, biography) VALUES (:name, :mail, :year, :gender, :limbs_num, :biography);");
    // у PDOStatement есть метод execute, передаем массив
    // execute вернет true|false
    $stmtErr = $stmt -> execute(['name' => $_POST['name'], 'mail' => $_POST['email'] , 'year' => $_POST['year'], 'gender' => $_POST['gender'], 'limbs_num' => $_POST['limbs'], 'biography' => $_POST['biography']]);
    if (!$stmtErr) {
        // в случае ошибки
        header("HTTP/1.1 500 Some server issue");
        exit();
    }
    // с помощью метода lastInsertId() получаем в виде строки id последнего вставленного человека
    $strId = $db -> lastInsertId();
    if (isset($_POST['powers'])) {
        foreach ($_POST['powers'] as $item) {
            switch ($item) {
                // заполняем Person_Ability - в зав-и от суперсилы a_id отличается
                case "Invincibility":
                    $stmt = $db -> prepare("INSERT INTO Person_Ability (p_id, a_id) VALUES (:p_id, :a_id);");
                    $stmtErr = $stmt -> execute(['p_id' => intval($strId), 'a_id' => 1]);
                    break;
                case "Noclip":
                    $stmt = $db -> prepare("INSERT INTO Person_Ability (p_id, a_id) VALUES (:p_id, :a_id);");
                    $stmtErr = $stmt -> execute(['p_id' => intval($strId), 'a_id' => 3]);
                    break;
                case "Levitation":
                    $stmt = $db -> prepare("INSERT INTO Person_Ability (p_id, a_id) VALUES (:p_id, :a_id);");
                    $stmtErr = $stmt -> execute(['p_id' => intval($strId), 'a_id' => 2]);
                    break;
            }
            if (!$stmtErr) {
                header("HTTP/1.1 500 Some server issue");
                exit();
            }
        }
    }
}
catch (PDOException $e){
    header("HTTP/1.1 500 Some server issue");
    //print('Error : ' . $e->getMessage());
    exit();
}