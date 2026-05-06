document.addEventListener("DOMContentLoaded",function(){
    const form=document.getElementById("walletForm");
    const balanceText=document.getElementById("balance");
    loadBalance(); ///MUST BE GETBALANCE.PHP///
    function loadBalance(){
        fetch("php/get_balance.php")
            .then(res => res.json())
            .then(data=>{
                if(data.success){
                    balanceText.innerText="Balance: " + data.balance + " EGP";
                }
                else{
                    alert(data.message);
                }
            })
            .catch(()=>alert("Error loading balance"));
    }

    form.addEventListener("submit",function(e){
        e.preventDefault();
// get values
        const cardNumber=form.card_number.value.trim();
        const cvv=form.cvv.value.trim();
        const expiry=form.expiry.value;
        const amount = form.amount.value;
//validation
        if(!cardNumber || !cvv || !expiry || !amount){
            alert("All fields are required");
            return;
        }
//check card is 16 digits
        if (!/^\d{16}$/.test(cardNumber)) {
            alert("Card number must be 16 digits");
            return;
        }
//check cvv 3 digits
        if(!/^\d{3}$/.test(cvv)){
            alert("CVV must be 3 digits");
            return;
        }
// check for expiry
        const today= new Date();
        const selectedDate=new Date(expiry +"-01");

        if(selectedDate<today){
            alert("card is expired");
            return;
        }
//check for amount
        if(amount<=0){
            alert("enter valid amount");
            return;
        }
// send it to php
        const formData=new FormData(form);
        fetch("php/topup.php", {
            method:"POST",
            body: formData
        })
            .then(res=>res.json())
            .then(data =>{
                if(data.success){
                    alert("Top Up successful");
                    loadBalance();
                    form.reset();
                } else{
                    alert(data.message);
                }
            })
            .catch(err=>{
                console.error(err);
                alert("something went wrong");
            });
    });
});