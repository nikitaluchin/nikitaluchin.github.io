function onClick() {
    let amount = document.getElementsByName("amount"); // NodeList
    let cost = document.getElementsByName("cost");
    let result = document.getElementById("result");
    const re = /\D/; // \D - нашло НЕ цифру. ну а шаблоны для regex пишутся в /.../
    let checkAmount = amount[0].value.match(re);
    let checkCost = cost[0].value.match(re);
    if (parseInt(amount[0].value).isNaN() || parseInt(cost[0].value).isNaN()) {
        result.innerHTML = "Можно не надо так делать...";
    } else if ((checkAmount || checkCost) === null) {
        // === - так безопаснее проверять: без автопреобразования типов
        let intAmount = parseInt(amount[0].value);
        let intCost = parseInt(cost[0].value);
        result.innerHTML = "Я вам насчитал тут: " + intAmount * intCost;
    } else {
        // сработает при "123@", например
        result.innerHTML = "А я хочу цифры!";
    }
}

window.addEventListener("DOMContentLoaded", function () {
    let btn = document.getElementById("btn-calc");
    btn.addEventListener("click", onClick);
});