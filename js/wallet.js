// Load balance on page load
document.addEventListener("DOMContentLoaded", function () {
    loadBalance();
});

function loadBalance() {
    fetch("php/get_balance.php")
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("balance").innerText = "Balance: " + data.balance + " EGP";
            }
        });
}

// Validate form before submitting
document.getElementById("walletForm").addEventListener("submit", function (e) {

    // get values
    const cardNumber = document.querySelector("[name='card_number']").value.trim();
    const cvv = document.querySelector("[name='cvv']").value.trim();
    const expiry = document.querySelector("[name='expiry']").value;
    const amount = document.querySelector("[name='amount']").value;

    // check all fields filled
    if (!cardNumber || !cvv || !expiry || !amount) {
        e.preventDefault();
        alert("All fields are required");
        return;
    }

    // check card is 16 digits
    if (!/^\d{16}$/.test(cardNumber)) {
        e.preventDefault();
        alert("Card number must be 16 digits");
        return;
    }

    // check cvv is 3 digits
    if (!/^\d{3}$/.test(cvv)) {
        e.preventDefault();
        alert("CVV must be 3 digits");
        return;
    }

    // check expiry is not in the past
    const today = new Date();
    const selectedDate = new Date(expiry + "-01");
    if (selectedDate < today) {
        e.preventDefault();
        alert("Card is expired");
        return;
    }

    // check amount is valid
    if (amount <= 0) {
        e.preventDefault();
        alert("Enter valid amount");
        return;
    }

    // if all good, form submits to topup.php automatically — no fetch needed
});