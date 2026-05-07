document.addEventListener("DOMContentLoaded", function () {

    // Load all users: fetch admin_get_users.php → render table
    if (document.getElementById("users-table-body")) {
        loadUsers();
        document.getElementById("search").addEventListener("input", loadUsers);
        document.getElementById("role-filter").addEventListener("change", loadUsers);
    }

    // Load pending courses: fetch get_courses with status filter
    if (document.getElementById("pending-container")) {
        loadPendingCourses();
    }
});


// ── Load all users → render table ─────────────────────────────

function loadUsers() {
    const search = document.getElementById("search").value.trim();
    const role   = document.getElementById("role-filter").value;

    fetch("php/admin_get_users.php?search=" + search + "&role=" + role)
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById("users-table-body");

            if (!data.success) {
                tbody.innerHTML = `<tr><td colspan="7">${data.message}</td></tr>`;
                return;
            }

            if (data.data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7">No users found.</td></tr>`;
                return;
            }

            tbody.innerHTML = data.data.map(user => `
                <tr id="user-row-${user.id}">
                    <td>${user.id}</td>
                    <td>${user.fname} ${user.lname}</td>
                    <td>${user.email}</td>
                    <td>${user.age}</td>
                    <td>${user.role}</td>
                    <td>${user.status == 1 ? "Active" : "Inactive"}</td>
                    <td><button onclick="deleteUser(${user.id})">Delete</button></td>
                </tr>
            `).join("");
        })
        .catch(err => console.error(err));
}


// ── Delete user: POST → remove row from table ─────────────────

function deleteUser(userId) {
    if (!confirm("Are you sure you want to delete this user?")) return;

    const formData = new FormData();
    formData.append("user_id", userId);

    fetch("php/admin_delete_user.php", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("user-row-" + userId).remove();
            } else {
                alert(data.message);
            }
        })
        .catch(err => console.error(err));
}


// ── Load pending courses: fetch get_courses with status filter ─

function loadPendingCourses() {
    fetch("php/get_courses.php?status=pending")
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById("pending-container");

            if (!data.success || data.data.length === 0) {
                container.innerHTML = "<p>No pending courses.</p>";
                return;
            }

            container.innerHTML = data.data.map(course => `
                <div class="course-card" id="pending-card-${course.id}">
                    <h3>${course.title}</h3>
                    <p>${course.description}</p>
                    <button onclick="reviewCourse(${course.id}, 'approved')">Approve</button>
                    <button onclick="reviewCourse(${course.id}, 'rejected')">Reject</button>
                </div>
            `).join("");
        })
        .catch(err => console.error(err));
}


// ── Approve / Reject: POST to admin_approve.php with status ───

function reviewCourse(courseId, status) {
    const formData = new FormData();
    formData.append("course_id", courseId);
    formData.append("status", status);

    fetch("php/admin_approve.php", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("pending-card-" + courseId).remove();
            } else {
                alert(data.message);
            }
        })
        .catch(err => console.error(err));
}