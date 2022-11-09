const MY_COLOR = "#1E4AD6";

window.addEventListener("DOMContentLoaded", function() {
    /*
    либо когда вызвались history.back(), history.forward(),
    либо клавиши назад-вперед в самом браузере - срабатывает
    popstate
    */
    window.addEventListener("popstate", show);
    let buttonFormShow = document.getElementById("buttonFormShow");
    buttonFormShow.addEventListener("click", function () {
        // страница обновляется и добавляется объект history.state
        // причем меняется URL сайта - как сказано в задании
        history.pushState({"form": true}, "", "?form=true");
        show();
    });
    // чтобы при перезагрузки страницы форма все равно показывалась
    show();
    let inputs = document.querySelectorAll(".to-storage");
    // при каждом изменении поля обновляем localStorage
    inputs.forEach(function(input) {input.addEventListener("input", save);} );
    let formButton = document.getElementById("formSubmit");
    formButton.addEventListener("click", send);
});

// либо отображает, либо скрывает попап
function show() {
    let popup = document.getElementById("popup");
    if (history.state != null && history.state.form === true) {
        popup.style.display = "block";
        let inputs = document.querySelectorAll(".to-storage");
        // восстанавливаем данные из localStorage
        inputs.forEach(function(input) {input.value = localStorage.getItem(input.id);} );
    }
    // при нажатии кнопки назад в браузере history.state станет null (как он был изначально)
    else {
        popup.style.display = "none";
    }
}

function save() {
    localStorage.setItem(this.id, this.value);
}

function send(){
    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let message = document.getElementById("message").value;
    let checkbox = document.getElementById("checkbox");
    let result = document.getElementById("result");
    if (name != "" && message != "" && checkbox.checked && validateEmail(email) != null) {
        let request = new XMLHttpRequest();
 
        request.open('POST', 'https://formcarry.com/s/jkNxqbdMD');
        // первое - то, что отправляю. второе - то, что хочу получить
        request.setRequestHeader('Content-Type', 'application/json');
        request.setRequestHeader('Accept', 'application/json');
        let popupForm = {"name": name, "email": email, "message": message};
        request.send(JSON.stringify(popupForm));
        let nameObj = document.getElementById("name");
        nameObj.value = "";
        let emailObj = document.getElementById("email");
        emailObj.value = "";
        let messageObj = document.getElementById("message");
        messageObj.value = "";
        checkbox.checked = false;
        // каждый раз, когда меняется состояние readystate - сработает функция
        // 0 - создали инстанс
        // 1 - вызвали request.open()
        // 2 - сработал request.send()
        // 3 - загрузка
        // 4 - ответ получен
        request.onreadystatechange = function() {
            if (this.readyState == 4) {
                localStorage.clear();
                result.style.color = MY_COLOR;
                result.innerHTML = "Отправлено";
            }
        }
    }
    else {
        result.style.color = "red";
        result.innerHTML = "Заполните меня полностью и отметьте чекбокс :3";
    }
}

const validateEmail = (email) => {
    return String(email)
      .toLowerCase()
      .match(
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    );
};