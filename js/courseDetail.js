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
    fetch('php/get_course.php?id=' + id)
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
        : 'EGP ' + parseFloat(course.price).toFixed(2);
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
          <form action="php/purchase.php" method="POST">
              <input type="hidden" name="course_id" value="${course.id}">
              <button type="submit" class="btn btn-primary w-100 mb-2">
                  Enroll Now
              </button>
          </form>
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