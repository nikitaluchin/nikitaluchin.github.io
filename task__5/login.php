<?php

/**
 * Файл login.php для неавторизованного пользователя выводит форму логина.
 * При отправке формы проверяет логин/пароль и создает сессию,
 * записывает в нее логин и id пользователя.
 * После авторизации пользователь перенаправляется на главную страницу
 * для изменения ранее введенных данных.
 **/

// Отправляем браузеру правильную кодировку,
// файл login.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// создается $_SESSION (пустая)
// Сессия нужна для сокрытия данных на сервере и для доступа
// к ним нужен ключ PHPSESSID
// Сессия хранится на сервере в файле
session_start();

// В суперглобальном массиве $_SESSION хранятся переменные сессии
// Будем сохранять туда логин после успешной авторизации


// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // isset только на NULL выдаст false
    // 'exit' мб только 1 или NULL, потому проверим просто наличие
    if (isset($_GET['exit'])){
        // удаляем $_SESSION
        session_destroy();
        foreach ($_COOKIE as $item => $value) {
            // удаляем куки
            setcookie($item, '', 1);
        }
        // перезагружаем страницу
        header('Location: ./login.php');
        // на всякий случай выйду
        exit();
    }

    // если мы залогинены
    if (!empty($_SESSION['login'])) {
        print('<div>Вы авторизованы как '. $_SESSION['login'] . ', uid ' . $_SESSION['uid'] . '</div>');
        print('<a href="./login.php?exit=1">Выйти</a>
               <a href="./">Главная страница</a>');
        exit();
    } else {
    
    print('<form action="./login.php" method="POST">
           <input name="login" required>
           <input name="pass" required>
           <input type="submit" value="Войти">
           </form>
           <a href="./">Главная страница</a>');
    }
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию
else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = 'u53012';
    $pass = '2656986';
    $db = new PDO('mysql:host=localhost;dbname=u53012', $user, $pass, [PDO::ATTR_PERSISTENT => true]);
    $stmt = $db->prepare("SELECT * FROM Person WHERE p_login = :p_login AND p_pass = :p_pass;");
    // Используем функцию adler32. hash() обработает пароль и выдаст хэш
    $stmtErr = $stmt->execute(['p_login' => $_POST['login'], 'p_pass' => hash("adler32", $_POST['pass'])]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    // если $result пустой
    if (!$result) {
        print("Пользователя с таким логином и паролем нетъ");
        print('<p><a href="./login.php">попробовать еще раз</a></p>');
        exit();
    }

    // Если нашли такого пользователя, то записываем данные в $_SESSION
    $_SESSION['login'] = $_POST['login'];
    $_SESSION['uid'] = $result['p_id'];

    // ставим куку на месяц, через месяц будут удалены (в задании попросили)
    setcookie('name_value', $result['p_name'], time() + 30 * 24 * 60 * 60);
    setcookie('email_value', $result['mail'], time() + 30 * 24 * 60 * 60);
    setcookie('year_value', $result['year'], time() + 30 * 24 * 60 * 60);
    setcookie('gender_value', $result['gender'], time() + 30 * 24 * 60 * 60);
    setcookie('limbs_value', $result['limbs_num'], time() + 30 * 24 * 60 * 60);
    setcookie('biography_value', $result['biography'], time() + 30 * 24 * 60 * 60);
    $stmt = $db->prepare("SELECT * FROM Person_Ability WHERE p_id = :p_id;");
    $stmtErr = $stmt->execute(['p_id' => $_SESSION['uid']]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    /*
    setcookie('Invincibility_value', '', 100000);
    setcookie('Noclip_value', '', 100000);
    setcookie('Levitation_value', '', 100000);
    */
    $stmt = $db->prepare("SELECT * FROM Ability;");
    $stmtErr =  $stmt -> execute();
    $abilities = $stmt->fetchAll();
    // вспомнил, что после выхода из аккаунта удаляются все куки
    // foreach ($abilities as $ability) {
    //     setcookie($ability['a_name'].'_value', '', 100000);
    // }

    // если у пользователя есть способности 
    if ($result) {
        foreach ($result as $item) {
            // делаем это, т.к. нужно вытащить a_name у способности, несмотря на то, что
            // данные уже лежат в бд и прошли проверку при добавлении
            // (до этого нужно было проверять, есть ли вообще такая способность)
            foreach ($abilities as $ability) {
                if ($ability['a_id'] == $item['a_id']) {
                    setcookie($ability['a_name'].'_value', '1', time() + 30 * 24 * 60 * 60);
                    break;
                }
            }
            // старая версия для 3х способностей
            /*
            switch ($item['a_id']) {
                case 1:
                    setcookie('Invincibility_value', '1', time() + 30 * 24 * 60 * 60);
                    break;
                case 3:
                    setcookie('Noclip_value', '1', time() + 30 * 24 * 60 * 60);
                    break;
                case 2:
                    setcookie('Levitation_value', '1', time() + 30 * 24 * 60 * 60);
                    break;
            }
            */
        }
    }


    // Делаем перенаправление.
    header('Location: ./login.php');
}
