const params = new URLSearchParams(window.location.search);
const courseId = params.get("course_id");

// FETCH LESSONS
fetch(`php/get_lessons.php?course_id=${courseId}`)
    .then(response => response.json())
    .then(data => {

        // ACCESS DENIED OR ERROR
        if (!data.success) {
            document.getElementById("video-container").innerHTML =
                `<p>${data.message}</p>`;
            return;
        }

        // GET FIRST LESSON
        const lesson = data.data[0];

        // SET TITLE
        document.getElementById("lesson-title").innerText =
            lesson.title;

        const container =
            document.getElementById("video-container");

        // IF VIDEO FILE EXISTS
        if (lesson.video_name && lesson.video_name !== "") {
            container.innerHTML = `
                <video controls width="700">
                    <source src="uploads/${lesson.video_name}" type="video/mp4">
                    Your browser does not support video.
                </video>
            `;
        }

        // OTHERWISE USE YOUTUBE / URL
        else if (lesson.video_url && lesson.video_url !== "") {
            container.innerHTML = `
                <iframe
                    width="700"
                    height="400"
                    src="${lesson.video_url}"
                    frameborder="0"
                    allowfullscreen>
                </iframe>
            `;
        }

        // NO VIDEO
        else {
            container.innerHTML =
                `<p>No lesson video found.</p>`;
        }

    })
    .catch(error => {
        console.error(error);
    });