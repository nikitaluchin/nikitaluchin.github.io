"use strict"; // JS исполняется в современном режиме (нет модулей, поэтому автоматом не включен)

function onClick() {
    let amount = document.getElementsByName("amount"); // NodeList
    let cost = document.getElementsByName("cost");
    let result = document.getElementById("result");
    const re = /\D/; // \D - нашло НЕ цифру. ну а шаблоны для regex пишутся в /.../
    if (isNaN(parseInt(amount[0].value)) || isNaN(parseInt(cost[0].value)))
        result.innerHTML = "Можно не надо так делать...";
    // === - так безопаснее проверять: без автопреобразования типов
    else if ((amount[0].value.match(re) || cost[0].value.match(re)) === null)
        result.innerHTML = "Я вам насчитал тут: " + parseInt(amount[0].value) * parseInt(cost[0].value);
    else
        // сработает при "123@", например
        result.innerHTML = "А я хочу цифры!";
}

window.addEventListener("DOMContentLoaded", function (event) {
    let btn = document.getElementById("btn-calc");
    btn.addEventListener("click", onClick);
});