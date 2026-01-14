const display = document.getElementById("display");

function append(value) {
    display.value += value;
}

function clearDisplay() {
    display.value = "";
}

function backspace() {
    display.value = display.value.slice(0, -1);
}

function calculate() {
    try {
        display.value = eval(display.value);
    } catch {
        display.value = "Error";
    }
}

function func(type) {
    try {
        let value = parseFloat(display.value);
        if (isNaN(value)) return;

        switch (type) {
            case "sin":
                display.value = Math.sin(value * Math.PI / 180);
                break;
            case "cos":
                display.value = Math.cos(value * Math.PI / 180);
                break;
            case "tan":
                display.value = Math.tan(value * Math.PI / 180);
                break;
            case "sqrt":
                display.value = Math.sqrt(value);
                break;
            case "log":
                display.value = Math.log10(value);
                break;
            case "ln":
                display.value = Math.log(value);
                break;
        }
    } catch {
        display.value = "Error";
    }
}

function power() {
    display.value = Math.pow(parseFloat(display.value), 2);
}

function pi() {
    display.value = Math.PI;
}
