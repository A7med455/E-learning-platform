document.addEventListener("DOMContentLoaded", () => {
    if(document.getElementById("courseList")){
        loadDashboard();
    }
    // FIXED: form ID matches HTML
    if(document.getElementById("add-course-form")){
        handleAddCourse();
    }
    // FIXED: form ID matches HTML
    if(document.getElementById("edit-course-form")){
        loadCourseData();
        handleEditCourse();
    }
    if(document.getElementById("lessonForm")){
        handleAddLesson();
    }
});

function loadDashboard(){
    fetch("../php/get_courses.php")
        .then(response => response.json())
        .then(data => {
            if(!data.success) return;
            const container=document.getElementById("courseList");
            container.innerHTML="";
            data.data.forEach(course => {
                const card=document.createElement("div");
                card.classList.add("card");
                card.innerHTML=`
            <h3>${course.title}</h3>
            <p>Status: ${course.status}</p>
            <button onclick="goEdit(${course.id})">Edit</button>
        `;
                container.appendChild(card);
            });
        })
        .catch(()=> alert("Failed to load courses"));
}

// FIXED: form now submits normally, JS only validates
function handleAddCourse(){
    const form=document.getElementById("add-course-form");
    form.addEventListener("submit", function(e){
        const title=form.title.value.trim();
        const description=form.description.value.trim();
        const price=form.price.value.trim();

        if(!title || !description || !price){
            e.preventDefault();
            alert("All fields are required");
            return;
        }
        // if valid, form submits to add_course.php automatically
    });
}

function loadCourseData(){
    const params=new URLSearchParams(window.location.search);
    const id=params.get("id");
    if(!id) return;
    // FIXED: comma changed to dot
    fetch(`../php/get_course.php?id=${id}`)
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

// FIXED: form now submits normally, JS only validates
function handleEditCourse(){
    const form=document.getElementById("edit-course-form");
    form.addEventListener("submit", function(e){
        const title=form.title.value.trim();
        const description=form.description.value.trim();
        const price=form.price.value.trim();

        if(!title || !description || !price){
            e.preventDefault();
            alert("All fields are required");
            return;
        }
        // if valid, form submits to edit_course.php automatically
    });
}

// FIXED: form now submits normally, JS only validates
function handleAddLesson(){
    const form=document.getElementById("lessonForm");
    form.addEventListener("submit", function(e){
        const title=form.title.value.trim();
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
        // if valid, form submits to add_lesson.php automatically
    });
}

function goEdit(id){
    window.location.href = `editcourse.html?id=${id}`;
}