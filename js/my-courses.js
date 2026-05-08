document.addEventListener("DOMContentLoaded", () => {

    fetch('php/get_my_courses.php')
        .then(response => response.json())
        .then(data => {

            const coursesContainer = document.getElementById("courses-container");
            if (!data.success) {
                coursesContainer.innerHTML =
                    `<p>${data.message}</p>`;
                return;
            }
            if (data.data.length === 0) {
                coursesContainer.innerHTML =
                    `<p>No enrolled courses yet.</p>`;
                return;
            }
            let coursesHTML = "";
            data.data.forEach(course => {
                const image = course.image_url
                    ? course.image_url
                    : 'https://via.placeholder.com/250x160?text=No+Image';
                coursesHTML += `
                    <div class="course-card">
                        <img src="${image}" alt="${course.title}">
                        <div class="course-card-body">
                            <h4>${course.title}</h4>
                            <p>Category: ${course.category}</p>
                            <a href="watch-lesson.html?course_id=${course.id}">
                                Watch Lessons
                            </a>
                        </div>
                    </div>
                `;
            });
            coursesContainer.innerHTML = coursesHTML;
        })
        .catch(error => {
            console.error(error);
        });
});