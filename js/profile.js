// Load profile data on page load
document.addEventListener("DOMContentLoaded", function () {

    // FIXED: path
    fetch('php/get_profile.php')
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (!data.success) {
                document.getElementById('messageArea').innerHTML =
                    '<p style="color:red;">Failed to load profile.</p>';
                return;
            }
            document.getElementById('fname').value = data.data.fname;
            document.getElementById('lname').value = data.data.lname;
            document.getElementById('email').value = data.data.email;
            document.getElementById('age').value = data.data.age;
            document.getElementById('roleDisplay').innerText = data.data.role;

            if (data.data.role === 'student') {
                document.getElementById('walletLinkBox').style.display = 'block';
            }
            if (data.data.role === 'admin') {
                document.getElementById('adminLinkBox').style.display = 'block';
            }
            if (data.data.role === 'instructor') {
                document.getElementById('instructorLinkBox').style.display = 'block';
            }
        });
});