document.addEventListener("DOMContentLoaded", () => {
    if(document.getElementById("courseList")){
        loadDashboard();
    }
    if(document.getElementById("addcourseForm")){
        handleAddCourse();
    }
    if(document.getElementById("editCourseForm")){
        loadCourseData();
        handleEditCourse();
    }
    if(document.getElementById("lessonForm")){
        handleAddLesson();
    }
});
function loadDashboard(){
    fetch("../php/get_courses.php") //should be filtered in php
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
function handleAddCourse(){
    const form=document.getElementById("addcourseForm");
    form.addEventListener("submit", function(e){
        e.preventDefault();

        const title=form.title.value.trim();
        const description=form.description.value.trim();
        const price=form.price.value.trim();

        if(!title || !description || !price){
            alert("All fields are required");
            return;
        }
        const formData=new FormData(form);
        fetch("../php/add_course.php", {
            method:"POST",
            body:formData
        })
            .then(response => response.json())
            .then(data => {
                if(data.success){
                    alert("Course added successfully");
                    form.reset();
                } else {
                    alert(data.message || "Failed to add course");
                }
            })
            .catch(() => alert("Error adding course"));
    });
}
function loadCourseData(){
    const params=new URLSearchParams(window.location.search);
    const id=params.get("id");
    if(!id) return;
    fetch(`../php/get_course.php?id=${id}`)
        ,then(response => response.json())
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
    const form=document.getElementById("editCourseForm");
    form.addEventListener("submit", function(e){
        e.preventDefault();
        const formData=new FormData(form);
        fetch("../php/edit_course.php", {
            method:"POST",
            body:formData
        })
            .then(response => response.json())
            .then(data => {
                if(data.success){
                    alert("Course updated successfully");
                } else {
                    alert(data.message || "Failed to update course");
                }
            })
            .catch(() => alert("Error updating course"));
    });
}
function handleAddLesson(){
    const form=document.getElementById("lessonForm");
    form.addEventListener("submit", function(e){
        e.preventDefault();
        const title=form.title.value.trim();
        const type = form.type.value;
        const url = form.video_url.value.trim();
        const file = form.video_file.files[0];
        if(!title){
            alert("Title is required");
            return;
        }
        if(type === "url" && !url){
            alert("Video URL is required");
            return;
        }
        if(type === "file" && !file){
            alert("Video file is required");
            return;
        }
        const formData=new FormData(form);
        fetch("../php/add_lesson.php", {
            method:"POST",
            body:formData
        })
            .then(response => response.json())
            .then(data => {
                if(data.success){
                    alert("Lesson added successfully");
                    form.reset();
                } else {
                    alert(data.message || "Failed to add lesson");
                }
            })
            .catch(() => alert("Error adding lesson"));
    });
}
function goEdit(id){
    window.location.href = `edit_course.html?id=${id}`;
}