let allCourses = [];
document.addEventListener('DOMContentLoaded', function () {
    loadCourses();
    document.getElementById('searchInput').addEventListener('input', filterCourses);
    document.getElementById('categoryFilter').addEventListener('change', filterCourses);
});
function loadCourses() {
    fetch('php/get_courses.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allCourses = data.data;
                renderCourses(allCourses);
            } else {
                document.getElementById('coursesContainer').innerHTML =
                    '<p class="text-danger">Failed to load courses.</p>';
            }
        })
        .catch(error => {
            console.error('Error loading courses:', error);
        });
}
function filterCourses() {
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const selectedCategory = document.getElementById('categoryFilter').value;

    const filtered = allCourses.filter(course => {
        const matchesSearch = course.title.toLowerCase().includes(searchText);
        const matchesCategory = selectedCategory === '' || course.category === selectedCategory;
        return matchesSearch && matchesCategory;
    });
    renderCourses(filtered);
}
function renderCourses(courses) {
    const container = document.getElementById('coursesContainer');
    const noResults = document.getElementById('noResults');
    if (courses.length === 0) {
        container.innerHTML = '';
        noResults.classList.remove('d-none');
        return;
    }
    noResults.classList.add('d-none');
    container.innerHTML = courses.map(course => {
        const image = course.image_url
            ? course.image_url
            : 'https://via.placeholder.com/300x180?text=No+Image';
        const price = parseFloat(course.price) === 0
            ? '<span class="text-success fw-bold">Free</span>'
            : `<span class="fw-bold">$${parseFloat(course.price).toFixed(2)}</span>`;
        return `
      <div class="col-sm-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
          <img src="${image}" class="card-img-top" alt="${course.title}" style="height: 180px; object-fit: cover;">
          <div class="card-body d-flex flex-column">
      <span class="badge bg-secondary mb-2" style="width: fit-content;">${course.category}</span>
            <h5 class="card-title">${course.title}</h5>
            <p class="card-text text-muted small flex-grow-1">${course.description.substring(0, 100)}...</p>
   <div class="d-flex justify-content-between align-items-center mt-3">
              ${price}
              <a href="course-detail.html?id=${course.id}" class="btn btn-primary btn-sm">View Course</a>
            </div>
          </div>
        </div>
      </div>
    `;
    }).join('');
}