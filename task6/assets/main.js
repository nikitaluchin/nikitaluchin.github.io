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
    } else if (checkAmount === null && checkCost === null) {
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

// event в арг-е function не пишем, т.к. не исп-тся
window.addEventListener("DOMContentLoaded", function () {
    let purchase = document.getElementById("purchase");
    let prodAmount = document.getElementById("amount");
    let fieldAmount = document.getElementsByName("prod-amount");
    let price;
    const priceList = {
        artem: [1500, 3000],
        maxim: 1000,
        yarik: [5000, 10000]
    };

    let select = document.getElementsByName("prod-type");
    select[0].addEventListener("change", function (event) {
        let selected = event.target;
        let radios = document.getElementById("prod-radios");
        let checkbox = document.getElementById("prod-checkbox");

        let radioBtns = document.querySelectorAll("input[name=size]");
        radioBtns.forEach(function (radioBtn) {
            if (radioBtn.checked) {
                radioBtn.checked = false;
            }
        });

        let africa = document.getElementsByName("africa");
        if (africa[0].checked) {
            africa[0].checked = false;
        }

        prodAmount.style.display = "none";
        fieldAmount[0].value = "";

        purchase.innerHTML = "";

        if (selected.value === "0") {
            prodAmount.style.display = "block";
            radios.style.display = "none";
            checkbox.style.display = "none";
        } else if (selected.value === "1") {
            radios.style.display = "block";
            checkbox.style.display = "none";
        } else {
            prodAmount.style.display = "block";
            radios.style.display = "none";
            checkbox.style.display = "block";
        }
    });

    let radioBtns = document.querySelectorAll("input[name=size]");
    radioBtns.forEach(function (radioBtn) {
        radioBtn.addEventListener("change", function (event) {
            prodAmount.style.display = "block";

            let target = event.target;
            let amount = parseInt(fieldAmount[0].value);

            if (fieldAmount[0].value !== "") {
                purchase.style.color = MY_COLOR;
                if (target.value === "0") {
                    price = amount * priceList.artem[1];
                    purchase.innerHTML = "Цена Артемов: " + price;
                } else {
                    price = amount * priceList.artem[0];
                    purchase.innerHTML = "Цена Артемов: " + price;
                }
            }
        });
    });

    let africa = document.getElementsByName("africa");
    africa[0].addEventListener("change", function (event) {
        let target = event.target;
        let amount = parseInt(fieldAmount[0].value);

        if (fieldAmount[0].value !== "") {
            purchase.style.color = MY_COLOR;
            if (target.checked) {
                price = amount * priceList.yarik[1];
                purchase.innerHTML = "Цена Яфриканцев: " + price;
            } else {
                price = amount * priceList.yarik[0];
                purchase.innerHTML = "Цена Яриков: " + price;
            }
        }
    });

    fieldAmount[0].addEventListener("input", function (event) {
        let target = event.target;

        const re = /\D/; // \D - нашло НЕ цифру. ну а шаблоны для regex пишутся в /.../
        let checkAmount = target.value.match(re);

        if (checkAmount === null) {
            if (target.value !== "") {
                purchase.style.color = MY_COLOR;

                let amount = parseInt(target.value);

                if (select[0].value === "0") {
                    price = amount * priceList.maxim;
                    purchase.innerHTML = "Цена Максимов: " + price;
                } else if (select[0].value === "1") {
                    if (radioBtns[0].checked) {
                        price = amount * priceList.artem[1];
                        purchase.innerHTML = "Цена Артемов: " + price;
                    } else {
                        price = amount * priceList.artem[0];
                        purchase.innerHTML = "Цена Артемов: " + price;
                    }
                } else {
                    if (africa[0].checked) {
                        price = amount * priceList.yarik[1];
                        purchase.innerHTML = "Цена Яфриканцев: " + price;
                    } else {
                        price = amount * priceList.yarik[0];
                        purchase.innerHTML = "Цена Яриков: " + price;
                    }
                }
            } else {
                purchase.innerHTML = "";
            }
        } else {
            purchase.style.color = "red";
            purchase.innerHTML = "Не делайте так";
        }
    });

    let btn = document.getElementById("btn-calc");
    btn.addEventListener("click", onClick);
});