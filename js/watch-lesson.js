const params = new URLSearchParams(window.location.search);
const courseId = params.get("course_id");

fetch('php/get_lessons.php?course_id=' + courseId)
    .then(response => response.json())
    .then(data => {

        if (!data.success) {
            document.getElementById("video-container").innerHTML =
                `<p>${data.message}</p>`;
            return;
        }

        const lesson = data.data[0];

        document.getElementById("lesson-title").innerText =
            lesson.title;

        const container = document.getElementById("video-container");

        if (lesson.video_name && lesson.video_name !== "") {
            container.innerHTML = `
                <video controls width="100%">
                    <source src="uploads/${lesson.video_name}" type="video/mp4">
                    Your browser does not support video.
                </video>
            `;
        }
        else if (lesson.video_url && lesson.video_url !== "") {
            container.innerHTML = `
                <iframe
                    width="100%"
                    height="400"
                    src="${lesson.video_url}"
                    frameborder="0"
                    allowfullscreen>
                </iframe>
            `;
        }
        else {
            container.innerHTML = `<p>No lesson video found.</p>`;
        }

    })
    .catch(error => {
        console.error(error);
    });