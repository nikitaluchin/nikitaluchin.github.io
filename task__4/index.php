<?php
/**
 * Реализовать проверку заполнения обязательных полей формы в предыдущей
 * с использованием Cookies, а также заполнение формы по умолчанию ранее
 * введенными значениями.
 */

// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Массив для временного хранения сообщений пользователю.
    $messages = array();

    // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
    // Куки - небольшой фрагмент данных, отправленный веб-сервером и хранимый на компьютере пользователя.
    // Веб-браузер всякий раз при попытке открыть страницу соответствующего сайта пересылает
    // этот фрагмент данных веб-серверу в составе HTTP-запроса

    // В $_COOKIE мы храним сообщения об ошибках,
    // а также пользовательские данные и 'save', если данные были сохранены

    // В конце после добавления данных в БД в куку 'save' будет записано '1'
    // Выдаем сообщение об успешном сохранении.
    if (!empty($_COOKIE['save'])) {
        // Удаляем куку, указывая время устаревания в прошлом
        // Третий параметр - время в секундах с начала эпохи (1 янв 1970),
        // когда кука должна помереть. Т.к. оно меньше чем текущее время, то
        // кука будет удалена сразу
        setcookie('save', '', 100000);
        // Если есть параметр save, то выводим сообщение пользователю
        $messages[] = '<div>Данные успешно сохранены</div>';
        // Записываем в кач-ве последнего элемента это значение
    }

    // Складываем признак ошибок в массив
    $errors = array();
    // Если значение непустое, т.е. есть ошибка, то empty
    // выдаст false, применится отрицание и в кач-ве значения будет записано true
    // Т.е. если есть ошибка name_error, то errors['name'] = true и т.д.
    $errors['name'] = !empty($_COOKIE['name_error']);
    $errors['email'] = !empty($_COOKIE['email_error']);
    $errors['year'] = !empty($_COOKIE['year_error']);
    $errors['gender'] = !empty($_COOKIE['gender_error']);
    $errors['limbs'] = !empty($_COOKIE['limbs_error']);
    $errors['check'] = !empty($_COOKIE['check_error']);
    // Выдаем сообщения об ошибках.
    if ($errors['name']) {
        // Удаляем куку, указывая время устаревания в прошлом
        setcookie('name_error', '', 100000);
        // Выводим сообщение
        $messages[] = '<div class="error-message">Имя - одно слово с БОЛЬШОЙ буквы</div>';
    }
    if ($errors['email']) {
        setcookie('email_error', '', 100000);
        $messages[] = '<div class="error-message">Неправильно указан email.</div>';
    }
    if ($errors['year']) {
        setcookie('year_error', '', 100000);
        $messages[] = '<div class="error-message">Укажите год. Допустимый диапазон с 1900 по 2099</div>';
    }
    if ($errors['gender']) {
        setcookie('gender_error', '', 100000);
        $messages[] = '<div class="error-message">Укажите пол</div>';
    }
    if ($errors['limbs']) {
        setcookie('limbs_error', '', 100000);
        $messages[] = '<div class="error-message">Укажите количество конечностей</div>';
    }
    if ($errors['check']) {
        setcookie('check_error', '', 100000);
        $messages[] = '<div class="error-message">Не прожат чекбокс</div>';
    }

    // Складываем предыдущие значения полей в массив, если они есть
    $values = array();
    // тернарный оператор: если значения не было, запишется пустая строка, иначе само значение
    $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
    $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
    $values['year'] = empty($_COOKIE['year_value']) ? '' : $_COOKIE['year_value'];
    // использую !isset т.к. пол (0 или 1) может равняться 0 и empty
    // на значение 0 выдаст 1, т.е. пол сочтется не указанным, а он указан (0)
    // isset вернет 0 только если там хранится null
    $values['gender'] = !isset($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value'];
    $values['limbs'] = empty($_COOKIE['limbs_value']) ? '' : $_COOKIE['limbs_value'];
    $values['biography'] = empty($_COOKIE['biography_value']) ? '' : $_COOKIE['biography_value'];
    $values['check'] = !isset($_COOKIE['check_value']) ? '' : $_COOKIE['check_value'];
    $values['invincibility'] = !isset($_COOKIE['Invincibility_value']) ? '' : $_COOKIE['Invincibility_value'];
    $values['noclip'] = !isset($_COOKIE['Noclip_value']) ? '' : $_COOKIE['Noclip_value'];
    $values['levitation'] = !isset($_COOKIE['Levitation_value']) ? '' : $_COOKIE['Levitation_value'];


    // Включаем содержимое файла form.php
    // В нем будут доступны переменные $messages, $errors и $values для вывода
    // сообщений, полей с ранее заполненными данными и признаками ошибок
    // (как бы исполнение кода продолжается на form.php)
    include('form.php');
} // Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл
else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = 'u53012';
    $pass = '2656986';
    $db = new PDO('mysql:host=localhost;dbname=u53012', $user, $pass, [PDO::ATTR_PERSISTENT => true]);
    // Проверяем ошибки
    $errors = FALSE;
    // / / - начало и конец регулярки
    // AD - проверит строку целиком на удовлетворение регулярки
    // ^, $ - начало и конец регулярки
    // [A-Z] - обязательно один символ от A до Z
    // [a-z]+ - любая непустая последовательность символов от a до z
    if (empty($_POST['name']) || !preg_match('/^[A-Z][a-z]+$/AD', $_POST['name'])) {
        // Выдаем куку на день с флажком об ошибке в поле name
        // (в задании указано делать на день здесь)
        setcookie('name_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
        // если будет ошибка, то выполнение скрипта необходимо приостановить (далее будет if)
    } else {
        // Сохраняем ранее введенное в форму значение на месяц
        // (т.е. если через месяц зайдем, то значения не увидим в форме)
        setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
    }
    if (empty($_POST['email']) || !preg_match("/^[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/", $_POST['email'])) {
        setcookie('email_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
    }
    // (|) - ИЛИ, т.е. проверка на удовлетворение либо правой части, либо левой  
    // \d - последовательность цифр, {2} - состоит из двух цифр
    if (empty($_POST['year']) || !preg_match("/^(19|20)\d{2}$/", $_POST['year'])) {
        setcookie('year_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('year_value', $_POST['year'], time() + 30 * 24 * 60 * 60);
    }
    // POST-запрос может быть отправлен, например, из консоли, в таком случае в
    // $_POST['gender'] м.б. какая-то бяка
    if (!isset($_POST['gender']) || ($_POST['gender']!='0' && $_POST['gender']!='1')) {
        setcookie('gender_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('gender_value', $_POST['gender'], time() + 30 * 24 * 60 * 60);
    }
    // одно число из множества {1,2,3,4}
    if (!isset($_POST['limbs']) || !preg_match('/^[1234]$/AD', $_POST['limbs'])) {
        setcookie('limbs_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('limbs_value', $_POST['limbs'], time() + 30 * 24 * 60 * 60);
    }
    // создаем подготовленное выражение для последующего запроса
    $stmt = $db->prepare("SELECT * FROM Ability;");
    // исполняем запрос
    $stmtErr =  $stmt->execute();
    // парсим оттуда все
    $abilities = $stmt->fetchAll();
    foreach ($abilities as $ability) {
        // конкатенируем и получаем, напр-р, Invincibility_value
        // удаляем куки, хранящие значениия всех способностей
        // все поля выше (конечности, и т.д.) чистить не нужно, ибо они есть всегда
        setcookie($ability['a_name'].'_value', '', 100000);
    }
    /*
    setcookie('Invincibility_value', '', 100000);
    setcookie('Noclip_value', '', 100000);
    setcookie('Levitation_value', '', 100000);
    */
    if (isset($_POST['powers'])) {
        // Проверяем, есть ли способность, которая была записана в $_POST['powers'], среди
        // допустимых способностей (запросом через консоль могли добавить туда дичь)
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
        // При наличии ошибок перезагружаем страницу и завершаем работу скрипта
        // Т.е. в базу данных ничего не будет добавлено
        header('Location: index.php');
        exit();
    }
    // Если мы не вышли строчкой выше (exit()), то у нас нет ошибок и мы чистим предыдущие ошибки
    setcookie('name_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('year_error', '', 100000);
    setcookie('gender_error', '', 100000);
    setcookie('limbs_error', '', 100000);


    // Сохранение в БД

    try {
        $stmt = $db->prepare("INSERT INTO Person (p_name, mail, year, gender, limbs_num, biography) VALUES (:name, :mail, :year, :gender, :limbs_num, :biography);");
        $stmtErr =  $stmt->execute(['name' => $_POST['name'],'mail' => $_POST['email'] , 'year' => $_POST['year'], 'gender' => $_POST['gender'], 'limbs_num' => $_POST['limbs'], 'biography' => $_POST['biography']]);
        if (!$stmtErr) {
            header("HTTP/1.1 500 Some server issue");
            exit();
        }
        // достали Id последнего добавленного юзера
        $strId = $db->lastInsertId();
        // Аналогичный цикл был выше, только добавляли в куки, а не в БД
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

    // Сохраняем куку с признаком успешного сохранения
    setcookie('save', '1');

    // Делаем перезагрузку страницы
    header('Location: index.php');
}
