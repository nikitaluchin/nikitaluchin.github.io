const MY_COLOR = "#1E4AD6";

function onClick() {
    let amount = document.getElementsByName("amount"); // NodeList
    let cost = document.getElementsByName("cost");
    let result = document.getElementById("result");

    const re = /\D/; // \D - нашло НЕ цифру. ну а шаблоны для regex пишутся в /.../
    let checkAmount = amount[0].value.match(re);
    let checkCost = cost[0].value.match(re);

    // === - так безопаснее проверять: без автопреобразования типов
    if (amount[0].value === "" || cost[0].value === "") {
        result.style.color = "red";
        result.innerHTML = "Можно не надо так делать...";
    } else if ((checkAmount || checkCost) === null) {
        result.style.color = MY_COLOR;
        let intAmount = parseInt(amount[0].value);
        let intCost = parseInt(cost[0].value);
        result.innerHTML = "Я вам насчитал тут: " + intAmount * intCost;
    } else {
        // сработает при "123@", например
        result.style.color = "red";
        result.innerHTML = "Ну зачем вы так :(";
    }
}

window.addEventListener("DOMContentLoaded", function () {
    let btn = document.getElementById("btn-calc");
    btn.addEventListener("click", onClick);
});