<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Portfolio Builder</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f2f2f2;
}
.container {
    width: 75%;
    margin: auto;
    background: white;
    padding: 25px;
}
h1 {
    text-align: center;
}
h2 {
    border-bottom: 2px solid #ddd;
    padding-bottom: 5px;
    margin-top: 30px;
}
label {
    display: block;
    margin-top: 10px;
}
input, textarea {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
}
button {
    margin-top: 15px;
    padding: 8px 15px;
    background: #052a62;
    color: white;
    border: none;
    cursor: pointer;
}
button:hover {
    background: #095297;
}
.block {
    border: 1px solid #ccc;
    padding: 15px;
    margin-top: 15px;
}
.remove-btn {
    background: crimson;
}
hr {
    margin-top: 15px;
}
* {
    box-sizing: border-box;
    font-family: "Segoe UI", sans-serif;
}

body {
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #0bbcd6, #0077b6, #023047);
    background-size: 300% 300%;
    animation: gradientMove 12s ease infinite;
    min-height: 100vh;
}
.metallic-text {
    font-weight: 600;
    color: transparent;

    background: linear-gradient(
        120deg,
        #228196,
        #1ebed3,
        #17d2e3,
        #41a8c8,
        #2d9bac
    );

    background-size: 200% auto;
    background-clip: text;
    -webkit-background-clip: text;

    letter-spacing: 0.5px;

    animation: metalShine 3s linear infinite;
}
@keyframes metalShine {
    to {
        background-position: 200% center;
    }
}

@keyframes gradientMove {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.container {
    width: 75%;
    margin: 50px auto;
    background: #fbfbfb;
    padding: 35px 45px;
    border-radius: 20px;
    box-shadow: 0 25px 60px rgba(6, 101, 133, 0.27);
    animation: fadeIn 1s ease;
}


@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

h1 {
    text-align: center;
    color: #098497;
    margin-bottom: 35px;
}

h2 {
    text-align:center;
    margin-top: 45px;
    color: #083188;
    width:auto;
    
    padding: 5px;
}

label {
    display: block;
    margin-top: 14px;
    font-weight: 600;
    color: #023047;
}

input, textarea {
    width: 100%;
    padding: 11px 13px;
    margin-top: 6px;
    border-radius: 10px;
    border: 1px solid #bde0fe;
    font-size: 15px;
    background: #e8f9fb;
    transition: 0.3s ease;
}

input:focus,
textarea:focus {
    outline: none;
    border-color: #0077b6;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(0, 119, 182, 0.25);
}

textarea {
    resize: vertical;
}


.block {
    margin-top: 22px;
    padding: 22px;
    background: linear-gradient(135deg, #2bf3fa, #5bbbff);
    border-radius: 16px;
    border: 1px solid #90dbf4;
    box-shadow: 0 10px 25px rgba(0, 180, 216, 0.15);
    transition: 0.3s ease;
}


.block:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(0, 180, 216, 0.3);
}

button {
    margin-top: 22px;
    padding: 11px 20px;
    background: linear-gradient(135deg, #0077b6, #00b4d8);
    color: white;
    font-size: 15px;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    transition: 0.35s ease;
}
.skills-wrapper {
   
    gap: 10px;
    margin-top: 10px;
}

#skillsList {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 15px;
}

.skill-chip {
    background: linear-gradient(135deg, #0077b6, #00b4d8);
    color: white;
    padding: 8px 14px;
    border-radius: 20px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 6px 15px rgba(0,180,216,0.4);
}

.skill-chip span {
    cursor: pointer;
    font-weight: bold;
}

button:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 25px rgba(0, 180, 216, 0.5);
}

button[type="submit"] {
    width: 100%;
    font-size: 18px;
    padding: 15px;
    margin-top: 45px;
}

hr {
    border: none;
    height: 1px;
    background: #90dbf4;
    margin-top: 25px;
}

</style>
</head>

<body>

<div class="container">
<h1 class="metallic-text">Portfolio / Resume Builder</h1>

<form method="POST" action="save.php" enctype="multipart/form-data">



<h2 class="metallic-text">Personal Details</h2>
<div class="block">
<label>Name</label>
<input type="text" name="name">

<label>Email</label>
<input type="email" name="email">

<label>Phone</label>
<input type="text" name="phone">

<label>Address</label>
<textarea name="address"></textarea>

<label>Institution / College</label>
<input type="text" name="inst">
</div>

<h2 class="metallic-text">More About Yourself</h2> 
<div class="block">
<label>Linkedin Profile Link</label>
<input type="url" name="lik">

<label>GitHub Profile Link</label>
<input type="url" name="gith">

<label>Add Your Resume</label>
<input type="file" name="res">

<label>Add Your Picture</label>
<input type="file" name="pic">

<label>Tell us about yourself</label>
<textarea name="about" rows="6" placeholder="Write a short professional summary..."></textarea>
</div>

<h2 class="metallic-text">Education</h2>
<div id="education-container">
    <div class="block">
        <label>Degree / Class</label>
        <input type="text" name="degree[]" placeholder="Bachelor's of Computer Science and Engineering">

        <label>Institute</label>
        <input type="text" name="edu_institute[]">

        <label>Duration</label>
        <input type="text" name="edu_year[]" placeholder="2024 / 2024-2025">

        <label>Score / CGPA</label>
        <input type="text" name="edu_score[]">

        
    </div>
</div>
<button type="button" onclick="addEducation()">➕ Add Education</button>


<h2 class="metallic-text">Skills</h2>

<div class="skills-wrapper">
    <input type="text" id="skillInput" placeholder="Enter a skill (e.g. JavaScript)">
    <button type="button" onclick="addSkill()">➕ Add</button>
</div>

<div id="skillsList"></div>


<h2 class="metallic-text">Projects</h2>
<div id="projects-container">
    <div class="block">
        <label>Project Title</label>
        <input type="text" name="project_title[]">

        <label>Description</label>
        <textarea name="project_desc[]"></textarea>

        <label>Tech Stack</label>
        <input type="text" name="tech_stack[]" placeholder="HTML, CSS, JavaScript">

        <label>Project Link</label>
        <input type="url" name="project_link[]" placeholder="GitHub Link" >
    </div>
</div>
<button type="button" onclick="addProject()">➕ Add Project</button>


<h2 class="metallic-text">Experience</h2>
<div id="experience-container">
    <div class="block">
        <label>Company Name</label>
        <input type="text" name="company[]">

        <label>Role</label>
        <input type="text" name="role[]">

        <label>Duration</label>
        <input type="text" name="duration[]">

        <label>Description</label>
        <textarea name="exp_desc[]"></textarea>
    </div>
</div>
<button type="button" onclick="addExperience()">➕ Add Experience</button>


<h2 class="metallic-text">Certificates</h2>

<div id="certificate-container">
    <div class="block">
        <label>Certificate Name</label>
        <input type="text" name="cert_name[]">

        <label>Upload Certificate</label>
        <input type="file" name="cert_file[]">
    </div>
</div>

<button type="button" onclick="addCertificate()">➕ Add Certificate</button>

<h2 class="metallic-text">Achievements</h2>

<div id="achievement-container">
    <div class="block">
        <label>Achievement Title</label>
        <input type="text" name="ach_title[]">

        <label>Description</label>
        <textarea name="ach_desc[]" placeholder="Hackathon winner, scholarship, competition rank, etc."></textarea>
    </div>
</div>

<button type="button" onclick="addAchievement()">➕ Add Achievement</button>

<br><br>
<button type="submit">Generate Portfolio</button>

</form>
</div>


<script>
function addEducation() {
    document.getElementById("education-container").insertAdjacentHTML("beforeend", `
        <div class="block">
            <label>Degree / Class</label>
            <input type="text" name="degree[]">

            <label>Institute</label>
            <input type="text" name="edu_institute[]">

            <label>Year</label>
            <input type="text" name="edu_year[]">

            <label>Score / CGPA</label>
            <input type="text" name="edu_score[]">
        </div>
    `);
}

function addProject() {
    document.getElementById("projects-container").insertAdjacentHTML("beforeend", `
        <div class="block">
            <label>Project Title</label>
            <input type="text" name="project_title[]">

            <label>Description</label>
            <textarea name="project_desc[]"></textarea>

            <label>Tech Stack</label>
            <input type="text" name="tech_stack[]">

            <label>Project Link</label>
            <input type="url" name="project_link[]">
        </div>
    `);
}
function addCertificate() {
    document.getElementById("certificate-container").insertAdjacentHTML("beforeend", `
        <div class="block">
            <label>Certificate Name</label>
            <input type="text" name="cert_name[]">

            <label>Upload Certificate</label>
            <input type="file" name="cert_file[]">
        </div>
    `);
}

function addAchievement() {
    document.getElementById("achievement-container").insertAdjacentHTML("beforeend", `
        <div class="block">
            <label>Achievement Title</label>
            <input type="text" name="ach_title[]">

            <label>Description</label>
            <textarea name="ach_desc[]"></textarea>
        </div>
    `);
}
function addExperience() {
    document.getElementById("experience-container").insertAdjacentHTML("beforeend", `
        <div class="block">
            <label>Company Name</label>
            <input type="text" name="company[]">

            <label>Role</label>
            <input type="text" name="role[]">

            <label>Duration</label>
            <input type="text" name="duration[]">

            <label>Description</label>
            <textarea name="exp_desc[]"></textarea>
        </div>
    `);
}

let skills = [];

function addSkill() {
    const skillInput = document.getElementById("skillInput");
    const skill = skillInput.value.trim();
    if(skill && !skills.includes(skill)) {
        skills.push(skill);

        
        const skillChip = document.createElement("div");
        skillChip.className = "skill-chip";
        skillChip.innerHTML = `${skill} <span onclick="removeSkill('${skill}')">&times;</span>`;
        document.getElementById("skillsList").appendChild(skillChip);

        skillInput.value = "";
    }
}

function removeSkill(skill) {
    skills = skills.filter(s => s !== skill);
    const list = document.getElementById("skillsList");
    list.innerHTML = "";
    skills.forEach(s => {
        const chip = document.createElement("div");
        chip.className = "skill-chip";
        chip.innerHTML = `${s} <span onclick="removeSkill('${s}')">&times;</span>`;
        list.appendChild(chip);
    });
}


document.querySelector("form").addEventListener("submit", function(e){
    
    document.querySelectorAll('input[name="skills[]"]').forEach(el => el.remove());

    skills.forEach(s => {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "skills[]";
        input.value = s;
        this.appendChild(input);
    });
});
</script>

</body>
</html>
