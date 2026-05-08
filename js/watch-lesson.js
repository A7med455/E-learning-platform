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

        // Build lesson list and video player
        let html = '<div style="display:flex;gap:20px;flex-wrap:wrap;">';

        // Left side - video player
        html += '<div style="flex:2;min-width:400px;">';
        html += '<h3 id="current-lesson-title">' + data.data[0].title + '</h3>';
        html += '<div id="video-player"></div>';
        html += '</div>';

        // Right side - lesson list
        html += '<div style="flex:1;min-width:200px;">';
        html += '<h4>Lessons</h4>';
        html += '<ul style="list-style:none;padding:0;">';

        data.data.forEach((lesson, index) => {
            html += `
                <li style="padding:8px;margin-bottom:5px;background:#f0f4ff;border-radius:5px;cursor:pointer;"
                    onclick="playLesson(${index})" id="lesson-item-${index}">
                    ${index + 1}. ${lesson.title}
                </li>
            `;
        });

        html += '</ul></div></div>';

        document.getElementById("video-container").innerHTML = html;

        // Store lessons globally
        window.allLessons = data.data;

        // Play first lesson
        playLesson(0);

    })
    .catch(error => {
        console.error(error);
    });

function playLesson(index) {
    const lesson = window.allLessons[index];

    // Update title
    document.getElementById("current-lesson-title").innerText = lesson.title;

    // Highlight current lesson
    document.querySelectorAll('[id^="lesson-item-"]').forEach(el => {
        el.style.background = '#f0f4ff';
        el.style.fontWeight = 'normal';
    });
    const currentItem = document.getElementById("lesson-item-" + index);
    if (currentItem) {
        currentItem.style.background = '#2e7cf6';
        currentItem.style.color = 'white';
        currentItem.style.fontWeight = 'bold';
    }

    const player = document.getElementById("video-player");

    if (lesson.video_name && lesson.video_name !== "") {
        player.innerHTML = `
            <video controls width="100%">
                <source src="uploads/${lesson.video_name}" type="video/mp4">
                Your browser does not support video.
            </video>
        `;
    } else if (lesson.video_url && lesson.video_url !== "") {
        player.innerHTML = `
            <iframe width="100%" height="400" src="${lesson.video_url}" frameborder="0" allowfullscreen></iframe>
        `;
    } else {
        player.innerHTML = `<p>No video available for this lesson.</p>`;
    }
}