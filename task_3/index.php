<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Задание 3</title>
        <link rel="stylesheet" href="style.css">
        <meta name="viewport" content="width=device-width initial-scale=1"> 
        <script src="script.js" defer></script>
    </head>
    <body>
        <form action="form.php" method="POST">
            <label>
                Имя:<br>
                <input name="name"
                  placeholder="Имя" required>
              </label><br>
        
              <label>
                E-mail:<br>
                <input name="email"
                  type="email"
                  placeholder="e-mail" required>
              </label><br>
        
              <label>
                Год рождения:<br>
                <select name="year">
                    <?php 
                    for ($i = 1923; $i <= 2023; $i++) {
                      printf('<option value="%d">%d год</option>', $i, $i);
                    }
                    ?>
                  </select>
              </label><br>
              
              Пол: <br>
              <label><input type="radio"
                name="gender" value="0" required>
                Мужской</label>
              <label><input type="radio"
                name="gender" value="1" required>
                Женский</label><br>
        
              Количество: <br>
              <label><input type="radio"
                name="limbs" value="1" required>
                1</label>
              <label><input type="radio"
                name="limbs" value="2" required>
                2</label>
              <label><input type="radio"
                name="limbs" value="3" required>
                3</label>
              <label><input type="radio" checked
                name="limbs" value="4" required>
                4</label><br>
        
            <label>
                Суперсилы:
                <br>
                <select name="powers[]"
                  multiple="multiple">
                  <option value="Invincibility">Бессмертие</option>
                  <option value="Noclip">Хождение сквозь стены</option>
                  <option value="Levitation">Левитация</option>
                </select>
              </label><br>
        
              <label>
                Биография:<br>
                <textarea name="biography"></textarea>
              </label><br>
        
              Согласие c лицензионным соглашением:<br>
              <label><input type="checkbox"
                name="check" required>
                Да</label><br>
        
              <input type="submit" value="Отправить">
            </form>
    </body>
</html>