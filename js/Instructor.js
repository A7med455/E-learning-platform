document.addEventListener("DOMContentLoaded", () => {
    if(document.getElementById("courseList")){
        loadDashboard();
    }
    if(document.getElementById("add-course-form")){
        handleAddCourse();
    }
    if(document.getElementById("edit-course-form")){
        loadCourseData();
        handleEditCourse();
    }
    if(document.getElementById("lessonForm")){
        handleAddLesson();
    }
});

function loadDashboard(){
    fetch("php/get_courses.php")
        .then(response => response.json())
        .then(data => {
            if(!data.success) return;
            const container = document.getElementById("courseList");
            container.innerHTML = "";

            document.getElementById("course-count").innerText = data.data.length;

            data.data.forEach(course => {
                const div = document.createElement("div");
                div.classList.add("course-item");
                div.innerHTML = `
                    <div>
                        <strong>${course.title}</strong>
                        <span class="status ${course.status}">${course.status}</span>
                    </div>
                    <div>
                        <a href="addLesson.html" class="btn btn-edit" style="text-decoration:none;font-size:13px;">+ Lesson</a>
                        <button class="btn btn-edit" onclick="goEdit(${course.id})">Edit</button>
                        <form method="POST" action="php/deleteCourse.php" style="display:inline;" onsubmit="return confirm('Delete this course?')">
                            <input type="hidden" name="course_id" value="${course.id}">
                            <button type="submit" class="btn btn-delete">Delete</button>
                        </form>
                    </div>
                `;
                container.appendChild(div);
            });
        })
        .catch(() => alert("Failed to load courses"));
}

function handleAddCourse(){
    const form = document.getElementById("add-course-form");
    form.addEventListener("submit", function(e){
        const title = form.title.value.trim();
        const description = form.description.value.trim();
        const price = form.price.value.trim();

        if(!title || !description || !price){
            e.preventDefault();
            alert("All fields are required");
            return;
        }
    });
}

function loadCourseData(){
    const params = new URLSearchParams(window.location.search);
    const id = params.get("id");
    if(!id) return;
    fetch("php/get_course.php?id=" + id)
        .then(response => response.json())
        .then(data => {
            if(!data.success) return;
            const c = data.data;
            document.querySelector("input[name='title']").value = c.title;
            document.querySelector("textarea[name='description']").value = c.description;
            document.querySelector("input[name='price']").value = c.price;
            document.querySelector("input[name='image_url']").value = c.image_url;
            document.querySelector("input[name='category']").value = c.category;
        });
}

function handleEditCourse(){
    const form = document.getElementById("edit-course-form");
    form.addEventListener("submit", function(e){
        const title = form.title.value.trim();
        const description = form.description.value.trim();
        const price = form.price.value.trim();

        if(!title || !description || !price){
            e.preventDefault();
            alert("All fields are required");
            return;
        }
    });
}

function handleAddLesson(){
    const form = document.getElementById("lessonForm");
    form.addEventListener("submit", function(e){
        const title = form.title.value.trim();
        const type = form.type.value;
        const url = form.video_url.value.trim();
        const file = form.video_file.files[0];

        if(!title){
            e.preventDefault();
            alert("Title is required");
            return;
        }
        if(type === "url" && !url){
            e.preventDefault();
            alert("Video URL is required");
            return;
        }
        if(type === "file" && !file){
            e.preventDefault();
            alert("Video file is required");
            return;
        }
    });
}

function goEdit(id){
    window.location.href = "editcourse.html?id=" + id;
}