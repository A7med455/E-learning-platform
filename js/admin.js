document.addEventListener("DOMContentLoaded", function () {

    // Load admin dashboard stats
    if (document.getElementById("student-count")) {
        loadDashboardStats();
        loadRecentCourses();
    }

    // Load all users
    if (document.getElementById("users-table-body")) {
        loadUsers();
        document.getElementById("search").addEventListener("input", loadUsers);
        document.getElementById("role-filter").addEventListener("change", loadUsers);
    }

    // Load pending courses
    if (document.getElementById("pending-container")) {
        loadPendingCourses();
    }
});


// ── Load dashboard stats ─────────────────────────────

function loadDashboardStats() {
    fetch("admin/admin_get_users.php?role=student")
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("student-count").innerText = data.data.length;
            }
        });

    fetch("admin/admin_get_users.php?role=instructor")
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("instructor-count").innerText = data.data.length;
            }
        });

    fetch("php/get_courses.php")
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("course-count").innerText = data.data.length;
            }
        });

    fetch("php/get_courses.php?status=pending")
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("pending-count").innerText = data.data.length;
            }
        });
}

// ── Load recent courses ──────────────────────────────

function loadRecentCourses() {
    fetch("php/get_courses.php")
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById("recent-courses");
            if (!data.success || data.data.length === 0) {
                container.innerHTML = "<p>No courses yet.</p>";
                return;
            }

            const recent = data.data.slice(0, 5);

            let tableHTML = `
                <table>
                    <thead>
                        <tr><th>Course</th><th>Status</th></tr>
                    </thead>
                    <tbody>
            `;

            recent.forEach(course => {
                tableHTML += `
                    <tr>
                        <td>${course.title}</td>
                        <td><span class="status ${course.status}">${course.status}</span></td>
                    </tr>
                `;
            });

            tableHTML += `</tbody></table>`;
            container.innerHTML = tableHTML;
        })
        .catch(err => console.error(err));
}


// ── Load all users → render table ─────────────────────

function loadUsers() {
    const search = document.getElementById("search").value.trim();
    const role   = document.getElementById("role-filter").value;

    fetch("admin/admin_get_users.php?search=" + search + "&role=" + role)
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
                    <td>
                        <form method="POST" action="admin/admin_delete_user.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?')">
                            <input type="hidden" name="user_id" value="${user.id}">
                            <button type="submit" class="btn-delete">Delete</button>
                        </form>
                    </td>
                </tr>
            `).join("");
        })
        .catch(err => console.error(err));
}


// ── Load pending courses ──────────────────────────────

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
                    <form method="POST" action="admin/admin_approve.php" style="display:inline;">
                        <input type="hidden" name="course_id" value="${course.id}">
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn-approve">Approve</button>
                    </form>
                    <form method="POST" action="admin/admin_approve.php" style="display:inline;">
                        <input type="hidden" name="course_id" value="${course.id}">
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn-reject">Reject</button>
                    </form>
                </div>
            `).join("");
        })
        .catch(err => console.error(err));
}