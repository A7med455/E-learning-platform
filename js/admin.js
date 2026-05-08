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

    // FIXED: admin files are in admin/ folder, not php/
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
                        <!-- FIXED: delete using form POST instead of fetch -->
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
                    <!-- FIXED: approve/reject using forms instead of fetch -->
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