let form = document.querySelector("form");
// сначала происходит валидация HTML, в случае успеха происходит событие "submit"
form.addEventListener("submit", function (event) {
    event.preventDefault(); // убрать дефолтное поведение браузера
    // экземпляр XHR позволяет без перезагрузки страницы отправлять
    // HTTP-запросы и получать ответы
    let xhr = new XMLHttpRequest();
    // данные методом отправляются на form.php
    xhr.open('POST', 'form.php');
    // встроенный класс для хранения данных формы
    // передаем ему форму, он собирает с нее данные
    let data = new FormData(form);
    xhr.send(data);
    // событие 'onreadystatechange' вызывается, когда меняется state у xhr
    // задаем обработчик события - будет вызываться function
    xhr.onreadystatechange = function () {
        // если ответ полностью загружен (4)
        if (xhr.readyState === 4) {
            // напр-р, выведет 'OK'
            alert(xhr.statusText);
            // 200 это 'OK'
            if (xhr.status === 200) {
                // очищаем форму, если все хорошо
                form.reset();
            }
        }
    };
});