// ── LOGIN FORM ────────────────────────────
const loginForm = document.getElementById('loginForm');

// only run this if we are on the login page
if (loginForm) {

    loginForm.addEventListener('submit', function(e) {

        // get what the user typed
        const email    = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        // check nothing is empty
        if (email === '' || password === '') {
            e.preventDefault();   // stop form from submitting
            document.getElementById('error-msg').textContent = 'Please fill all fields.';
            document.getElementById('error-msg').style.display = 'block';
            return;
        }
        // if all good, form submits to login.php automatically — no fetch needed
    });
}


// ── SIGNUP FORM ────────────────────────────
const signupForm = document.getElementById('signupForm');

// only run this if we are on the signup page
if (signupForm) {

    signupForm.addEventListener('submit', function(e) {

        // get what the user typed
        const fname    = document.getElementById('fname').value;
        const lname    = document.getElementById('lname').value;
        const age      = document.getElementById('age').value;
        const email    = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const role     = document.getElementById('role').value;

        // check nothing is empty
        if (!fname || !lname || !age || !email || !password || !role) {
            e.preventDefault();
            document.getElementById('error-msg').textContent = 'Please fill all fields.';
            document.getElementById('error-msg').style.display = 'block';
            return;
        }

        // check password length
        if (password.length < 6) {
            e.preventDefault();
            document.getElementById('error-msg').textContent = 'Password must be at least 6 characters.';
            document.getElementById('error-msg').style.display = 'block';
            return;
        }
        // if all good, form submits to signup.php automatically
    });
}