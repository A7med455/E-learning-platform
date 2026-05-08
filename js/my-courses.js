document.addEventListener("DOMContentLoaded", () => {

    fetch('php/get_my_courses.php')
        .then(response => response.json())
        .then(data => {

            const coursesContainer = document.getElementById("courses-container");
            // if error
            if (!data.success) {
                coursesContainer.innerHTML =
                    `<p>${data.message}</p>`;
                return;
            }
            // if no courses
            if (data.data.length === 0) {
                coursesContainer.innerHTML =
                    `<p>No enrolled courses yet.</p>`;
                return;
            }
            // render courses
            let coursesHTML = "";
            data.data.forEach(course => {
                coursesHTML += `
                    <div class="course-card">
                        <img src="${course.image_url}" alt="${course.title}" width="250">
                        <h3>${course.title}</h3>
                        <p>Category: ${course.category}</p>
                        <a href="watch-lesson.html?course_id=${course.id}">
                            Watch Lessons
                        </a>
                    </div>
                `;
            });
            coursesContainer.innerHTML = coursesHTML;
        })
        .catch(error => {
            console.error(error);
        });
});