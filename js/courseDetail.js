document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);
    const courseId = params.get('id');
    if (!courseId) {
        document.getElementById('courseDetail').innerHTML =
            '<p class="text-danger">No course specified.</p>';
        return;
    }
    loadCourse(courseId);
});
function loadCourse(id) {
    fetch(`php/get_course.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderCourse(data.data);
            } else {
                document.getElementById('courseDetail').innerHTML =
                    '<p class="text-danger">Course not found.</p>';
            }
        })
        .catch(error => {
            console.error('Error loading course:', error);
        });
}
function renderCourse(course) {
    const image = course.image_url
        ? course.image_url
        : 'https://via.placeholder.com/600x300?text=No+Image';
    const price = parseFloat(course.price) === 0
        ? 'Free'
        : `$${parseFloat(course.price).toFixed(2)}`;
    document.getElementById('courseDetail').innerHTML = `
    <div class="row g-4">
      <div class="col-md-7">
        <img src="${image}" class="img-fluid rounded mb-3" alt="${course.title}" style="width: 100%; max-height: 320px; object-fit: cover;">
        <h2 class="fw-bold">${course.title}</h2>
        <span class="badge bg-secondary mb-3">${course.category}</span>
        <p class="text-muted">${course.description}</p>
      </div>
      <div class="col-md-5">
        <div class="card border-0 shadow p-4">
          <h3 class="fw-bold text-primary mb-1">${price}</h3>
          <p class="text-muted small mb-3">One-time payment. Lifetime access.</p>
          <button class="btn btn-primary w-100 mb-2" onclick="purchaseCourse(${course.id})">
            Enroll Now
          </button>
          <a href="courses.html" class="btn btn-outline-secondary w-100">Back to Courses</a>
          <hr/>
          <ul class="list-unstyled small text-muted">
            <li>✔ Full course access</li>
            <li>✔ Watch lessons anytime</li>
            <li>✔ Taught by a real instructor</li>
          </ul>
        </div>
      </div>
    </div>
  `;
}
function purchaseCourse(courseId) {
    const formData = new FormData();
    formData.append('course_id', courseId);
    fetch('php/purchase.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Enrolled successfully! Go to My Courses to start learning.', 'bg-success');
            } else {
                showToast(data.message || 'Could not complete purchase.', 'bg-danger');
            }
        })
        .catch(error => {
            console.error('Purchase error:', error);
            showToast('Something went wrong. Try again.', 'bg-danger');
        });
}
//pop up noti//
function showToast(message, bgClass) {
    const toastEl = document.getElementById('toastMsg');
    const toastText = document.getElementById('toastText');
    toastEl.className = `toast align-items-center text-white border-0 ${bgClass}`;
    toastText.textContent = message;
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}