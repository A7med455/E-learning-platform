

//for login form
// find the login form on the page
const loginForm = document.getElementById('loginForm');

// only run this if we are on the login page
if (loginForm) {

    // when the user clicks Login button
    loginForm.addEventListener('submit', function(e) {

        // stop the form from refreshing the page
        e.preventDefault();

        // get what the user typed
        const email    = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        // check nothing is empty
        if (email === '' || password === '') {
            document.getElementById('error-msg').textContent = 'Please fill all fields.';
            document.getElementById('error-msg').style.display = 'block';
            return;
        }

        // send to login.php
        const data = new FormData();
        data.append('email', email);
        data.append('password', password);

        fetch('php/login.php', { method: 'POST', body: data })
        .then(res => res.json())
        .then(function(response) {

            if (response.success) {
                // go to the right page based on role
                if (response.role === 'student')    window.location.href = 'home.php';
                if (response.role === 'instructor') window.location.href = 'instructor/dashboard.php';
                if (response.role === 'admin')      window.location.href = 'admin/dashboard.php';

            } else {
                // show the error from PHP
                document.getElementById('error-msg').textContent = response.message;
                document.getElementById('error-msg').style.display = 'block';
            }
        });
    });
}




//for signup form
// find the signup form on the page
const signupForm = document.getElementById('signupForm');

// only run this if we are on the signup page
if (signupForm) {

    // when the user clicks Sign Up button
    signupForm.addEventListener('submit', function(e) {

        // stop the form from refreshing the page
        e.preventDefault();

        // get what the user typed
        const fname    = document.getElementById('fname').value;
        const lname    = document.getElementById('lname').value;
        const age      = document.getElementById('age').value;
        const email    = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const role     = document.getElementById('role').value;

        // check nothing is empty
        if (!fname || !lname || !age || !email || !password || !role) {
            document.getElementById('error-msg').textContent = 'Please fill all fields.';
            document.getElementById('error-msg').style.display = 'block';
            return;
        }

        // check password length
        if (password.length < 6) {
            document.getElementById('error-msg').textContent = 'Password must be at least 6 characters.';
            document.getElementById('error-msg').style.display = 'block';
            return;
        }

        // send to signup.php
        const data = new FormData();
        data.append('fname', fname);
        data.append('lname', lname);
        data.append('age', age);
        data.append('email', email);
        data.append('password', password);
        data.append('role', role);

        fetch('php/signup.php', { method: 'POST', body: data })
        .then(res => res.json())
        .then(function(response) {

            if (response.success) {
                // go to login page after signup
                window.location.href = 'login.html';

            } else {
                // show the error from PHP
                document.getElementById('error-msg').textContent = response.message;
                document.getElementById('error-msg').style.display = 'block';
            }
        });
    });
}