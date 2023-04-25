<?php
/**
 * Реализовать проверку заполнения обязательных полей формы в предыдущей
 * с использованием Cookies, а также заполнение формы по умолчанию ранее
 * введенными значениями.
 */

// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Массив для временного хранения сообщений пользователю.
    $messages = array();

    // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
    // Выдаем сообщение об успешном сохранении.
    if (!empty($_COOKIE['save'])) {
        // Удаляем куку, указывая время устаревания в прошлом.
        setcookie('save', '', 100000);
        // Если есть параметр save, то выводим сообщение пользователю.
        $messages[] = 'Спасибо, результаты сохранены.';
    }

    // Складываем признак ошибок в массив.
    $errors = array();
    $errors['name'] = !empty($_COOKIE['name_error']);
    $errors['email'] = !empty($_COOKIE['email_error']);
    $errors['year'] = !empty($_COOKIE['year_error']);
    $errors['gender'] = !empty($_COOKIE['gender_error']);
    $errors['limbs'] = !empty($_COOKIE['limbs_error']);
    $errors['check'] = !empty($_COOKIE['check_error']);
    // Выдаем сообщения об ошибках.
    if ($errors['name']) {
        // Удаляем куку, указывая время устаревания в прошлом.
        setcookie('name_error', '', 100000);
        // Выводим сообщение.
        $messages[] = '<div class="error-message">Заполните имя. Имя - одно слово с большой буквы</div>';
    }
    if ($errors['email']) {
        setcookie('email_error', '', 100000);
        $messages[] = '<div class="error-message">Правильно заполните email.</div>';
    }
    if ($errors['year']) {
        setcookie('year_error', '', 100000);
        $messages[] = '<div class="error-message">Заполните год. Он должен быть с 1900 по 2099</div>';
    }
    if ($errors['gender']) {
        setcookie('gender_error', '', 100000);
        $messages[] = '<div class="error-message">Заполните пол.</div>';
    }
    if ($errors['limbs']) {
        setcookie('limbs_error', '', 100000);
        $messages[] = '<div class="error-message">Заполните количество конечностей.</div>';
    }
    if ($errors['check']) {
        setcookie('check_error', '', 100000);
        $messages[] = '<div class="error-message">Заполните чекбокс.</div>';
    }

    // Складываем предыдущие значения полей в массив, если есть.
    $values = array();
    $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
    $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
    $values['year'] = empty($_COOKIE['year_value']) ? '' : $_COOKIE['year_value'];
    $values['gender'] = !isset($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value']; // использую !isset т к пол может равняться 0 и empty скажет что пол не указан
    $values['limbs'] = empty($_COOKIE['limbs_value']) ? '' : $_COOKIE['limbs_value'];
    $values['biography'] = empty($_COOKIE['biography_value']) ? '' : $_COOKIE['biography_value'];
    $values['check'] = !isset($_COOKIE['check_value']) ? '' : $_COOKIE['check_value'];
    $values['invincibility'] = !isset($_COOKIE['Invincibility_value']) ? '' : $_COOKIE['Invincibility_value'];
    $values['noclip'] = !isset($_COOKIE['Noclip_value']) ? '' : $_COOKIE['Noclip_value'];
    $values['levitation'] = !isset($_COOKIE['Levitation_value']) ? '' : $_COOKIE['Levitation_value'];


    // Включаем содержимое файла form.php.
    // В нем будут доступны переменные $messages, $errors и $values для вывода
    // сообщений, полей с ранее заполненными данными и признаками ошибок.
    include('form.php');
} // Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {
    $user = 'u53012';
    $pass = '2656986';
    $db = new PDO('mysql:host=localhost;dbname=u53012', $user, $pass, [PDO::ATTR_PERSISTENT => true]);
    // Проверяем ошибки.
    $errors = FALSE;
    if (empty($_POST['name']) || !preg_match('/^[A-Z][a-z]+$/AD', $_POST['name'])) {
        // Выдаем куку на день с флажком об ошибке в поле name.
        setcookie('name_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        // Сохраняем ранее введенное в форму значение на месяц.
        setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
    }
    if (empty($_POST['email']) || !preg_match("/^[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/", $_POST['email'])) {
        setcookie('email_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
    }
    if (empty($_POST['year']) || !preg_match("/^(19|20)\d{2}$/", $_POST['year'])) {
        setcookie('year_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('year_value', $_POST['year'], time() + 30 * 24 * 60 * 60);
    }
    if (!isset($_POST['gender']) || ($_POST['gender']!='0' && $_POST['gender']!='1')) {
        setcookie('gender_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('gender_value', $_POST['gender'], time() + 30 * 24 * 60 * 60);
    }
    if (!isset($_POST['limbs']) || !preg_match('/^[1234]$/AD', $_POST['limbs'])) {
        setcookie('limbs_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('limbs_value', $_POST['limbs'], time() + 30 * 24 * 60 * 60);
    }
    $stmt = $db->prepare("SELECT * FROM Ability;");
    $stmtErr =  $stmt -> execute();
    $abilities = $stmt->fetchAll();
    foreach ($abilities as $ability) {
        setcookie($ability['a_name'].'_value', '', 100000);
    }
    /*
    setcookie('Invincibility_value', '', 100000);
    setcookie('Noclip_value', '', 100000);
    setcookie('Levitation_value', '', 100000);
    */
    if (isset($_POST['powers'])) {
        foreach ($_POST['powers'] as $item) {
            foreach ($abilities as $ability) {
                if ($ability['a_name'] == $item) {
                    setcookie($item.'_value', '1', time() + 30 * 24 * 60 * 60);
                    break;
                }
            }
            /*
            switch ($item) {
                case "Invincibility":
                    setcookie('Invincibility_value', '1', time() + 30 * 24 * 60 * 60);
                    break;
                case "Noclip":
                    setcookie('Noclip_value', '1', time() + 30 * 24 * 60 * 60);
                    break;
                case "Levitation":
                    setcookie('Levitation_value', '1', time() + 30 * 24 * 60 * 60);
                    break;
            }
            */
        }
    }
    setcookie('biography_value', $_POST['biography'], time() + 30 * 24 * 60 * 60);
    if ($_POST['check']!="on") {
        setcookie('check_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('check_value', '1', time() + 30 * 24 * 60 * 60);
    }

    if ($errors) {
        // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
        header('Location: index.php');
        exit();
    }
    // Удаляем Cookies с признаками ошибок.
    setcookie('name_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('year_error', '', 100000);
    setcookie('gender_error', '', 100000);
    setcookie('limbs_error', '', 100000);


    // Сохранение в БД.

    try {
        $stmt = $db->prepare("INSERT INTO Person (p_name, mail, year, gender, limbs_num, biography) VALUES (:name, :mail, :year, :gender, :limbs_num, :biography);");
        $stmtErr =  $stmt -> execute(['name' => $_POST['name'],'mail' => $_POST['email'] , 'year' => $_POST['year'], 'gender' => $_POST['gender'], 'limbs_num' => $_POST['limbs'], 'biography' => $_POST['biography']]);
        if (!$stmtErr) {
            header("HTTP/1.1 500 Some server issue");
            exit();
        }
        $strId = $db->lastInsertId();
        if (isset($_POST['powers'])) {
            foreach ($_POST['powers'] as $item) {
                foreach ($abilities as $ability) {
                    if ($ability['a_name'] == $item) {
                        $stmt = $db->prepare("INSERT INTO Person_Ability (p_id, a_id) VALUES (:p_id, :a_id);");
                        $stmtErr = $stmt->execute(['p_id' => intval($strId), 'a_id' => $ability['a_id']]);
                        break;
                    }
                }
                /*
                switch ($item) {
                    case "Invincibility":
                        $stmt = $db->prepare("INSERT INTO Person_Ability (p_id, a_id) VALUES (:p_id, :a_id);");
                        $stmtErr = $stmt->execute(['p_id' => intval($strId), 'a_id' => 1]);
                        break;
                    case "Noclip":
                        $stmt = $db->prepare("INSERT INTO Person_Ability (p_id, a_id) VALUES (:p_id, :a_id);");
                        $stmtErr = $stmt->execute(['p_id' => intval($strId), 'a_id' => 3]);
                        break;
                    case "Levitation":
                        $stmt = $db->prepare("INSERT INTO Person_Ability (p_id, a_id) VALUES (:p_id, :a_id);");
                        $stmtErr = $stmt->execute(['p_id' => intval($strId), 'a_id' => 2]);
                        break;
                }
                */
                if (!$stmtErr) {
                    header("HTTP/1.1 500 Some server issue");
                    exit();
                }
            }
        }
    }
    catch(PDOException $e){
        header("HTTP/1.1 500 Some server issue");
        //print('Error : ' . $e->getMessage());
        exit();
    }

    // Сохраняем куку с признаком успешного сохранения.
    setcookie('save', '1');

    // Делаем перенаправление.
    header('Location: index.php');
}
