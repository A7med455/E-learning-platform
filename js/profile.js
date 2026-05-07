// profile.js

fetch('../php/get_profile.php')
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        document.getElementById('fname').value = data.data.fname;
        document.getElementById('lname').value = data.data.lname;
        document.getElementById('email').value = data.data.email;
        document.getElementById('age').value = data.data.age;
        //2 datas because the php puts the user info in a 'data' key, so the first data is an object and the second is the key
    });

document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();

    var formData = new FormData();
    formData.append('fname', document.getElementById('fname').value);
    formData.append('lname', document.getElementById('lname').value);
    formData.append('email', document.getElementById('email').value);
    formData.append('age', document.getElementById('age').value);
    formData.append('password', document.getElementById('password').value);

    fetch('../php/update_profile.php', {
        method: 'POST',
        body: formData
    })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                alert('Profile updated');
            } else {
                alert(data.message);
            }
        });
});