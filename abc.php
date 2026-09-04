<?php
session_start();

if(!isset($_SESSION['auth_id'])){
    header("Location: login.php");
    exit();
}

require_once "db.php";

$portfolio_id = $_GET['portfolio_id'] ?? null;
$error = $_SESSION['save_error'] ?? '';
unset($_SESSION['save_error']);

if(!$portfolio_id || !ctype_digit((string)$portfolio_id)){
    die("Portfolio not found");
}

$portfolio_id = (int)$portfolio_id;

$stmt = $conn->prepare("
    SELECT *
    FROM portfolio_details
    WHERE portfolio_id = ?
");
$stmt->bind_param("i", $portfolio_id);
$stmt->execute();
$details = $stmt->get_result()->fetch_assoc();
$stmt->close();

if(!$details){
    die("Portfolio details not found");
}

$education = [];

$stmt = $conn->prepare("
    SELECT *
    FROM education
    WHERE portfolio_id = ?
");
$stmt->bind_param("i", $portfolio_id);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
    $education[] = $row;
}

$stmt->close();

$skills = [];

$stmt = $conn->prepare("
    SELECT skill_name
    FROM skills
    WHERE portfolio_id = ?
");
$stmt->bind_param("i", $portfolio_id);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
    $skills[] = $row['skill_name'];
}

$stmt->close();

$projects = [];

$stmt = $conn->prepare("
    SELECT *
    FROM projects
    WHERE portfolio_id = ?
");
$stmt->bind_param("i", $portfolio_id);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
    $projects[] = $row;
}

$stmt->close();

$experience = [];

$stmt = $conn->prepare("
    SELECT *
    FROM experience
    WHERE portfolio_id = ?
");
$stmt->bind_param("i", $portfolio_id);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
    $experience[] = $row;
}

$stmt->close();

$certificates = [];

$stmt = $conn->prepare("
    SELECT *
    FROM certificates
    WHERE portfolio_id = ?
    ORDER BY id
");
$stmt->bind_param("i", $portfolio_id);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
    $certificates[] = $row;
}

$stmt->close();

$achievements = [];

$stmt = $conn->prepare("
    SELECT *
    FROM achievements
    WHERE portfolio_id = ?
");
$stmt->bind_param("i", $portfolio_id);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
    $achievements[] = $row;
}

$stmt->close();
?>

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
    text-align: center;
    margin-top: 45px;
    color: #083188;
    width: auto;
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

<?php if (!empty($error)): ?>
<div style="background:#ffe5e5; border:1px solid #ff4d4d; color:#a30000;
            padding:14px 18px; border-radius:10px; margin-bottom:20px;">
    ⚠️ <?php echo htmlspecialchars($error); ?>
</div>
<?php endif; ?>

<form method="POST" action="save.php" enctype="multipart/form-data">
<input type="hidden" name="portfolio_id" value="<?php echo $portfolio_id; ?>">

<h2 class="metallic-text">Personal Details</h2>
<div class="block">
<input type="text" name="name"
       value="<?php echo htmlspecialchars($details['name']); ?>">

<label>Email</label>
<input type="email" name="email"
       value="<?php echo htmlspecialchars($details['email']); ?>">

<label>Phone</label>
<input type="text" name="phone"
       value="<?php echo htmlspecialchars($details['phone']); ?>">

<label>Address</label>
<textarea name="address"><?php echo htmlspecialchars($details['address']); ?></textarea>

<label>Institution / College</label>
<input type="text" name="inst"
       value="<?php echo htmlspecialchars($details['institution']); ?>">
</div>

<h2 class="metallic-text">More About Yourself</h2>
<div class="block">
<label>Linkedin Profile Link</label>
<input type="url" name="lik"
       value="<?php echo htmlspecialchars($details['linkedin']); ?>">

<label>GitHub Profile Link</label>
<input type="url" name="gith"
       value="<?php echo htmlspecialchars($details['github']); ?>">

<label>Add Your Resume</label>
<input type="file" name="res">

<label>Add Your Picture</label>
<input type="file" name="pic">

<label>Tell us about yourself</label>
<textarea name="about" rows="6"
placeholder="Write a short professional summary..."><?php echo htmlspecialchars($details['about']); ?></textarea>
</div>

<h2 class="metallic-text">Education</h2>
<div id="education-container">

<?php if(!empty($education)): ?>

<?php foreach($education as $e): ?>

<div class="block">

<label>Degree / Class</label>
<input type="text"
       name="degree[]"
       value="<?php echo htmlspecialchars($e['degree']); ?>">

<label>Institute</label>
<input type="text"
       name="edu_institute[]"
       value="<?php echo htmlspecialchars($e['institute']); ?>">

<label>Duration</label>
<input type="text"
       name="edu_year[]"
       value="<?php echo htmlspecialchars($e['duration']); ?>">

<label>Score / CGPA</label>
<input type="text"
       name="edu_score[]"
       value="<?php echo htmlspecialchars($e['score']); ?>">

</div>

<?php endforeach; ?>

<?php else: ?>

<div class="block">

<label>Degree / Class</label>
<input type="text" name="degree[]">

<label>Institute</label>
<input type="text" name="edu_institute[]">

<label>Duration</label>
<input type="text" name="edu_year[]">

<label>Score / CGPA</label>
<input type="text" name="edu_score[]">

</div>

<?php endif; ?>

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

<?php if(!empty($projects)): ?>

<?php foreach($projects as $p): ?>

<div class="block">

<label>Project Title</label>
<input type="text"
       name="project_title[]"
       value="<?php echo htmlspecialchars($p['title']); ?>">

<label>Description</label>
<textarea name="project_desc[]"><?php echo htmlspecialchars($p['description']); ?></textarea>

<label>Tech Stack</label>
<input type="text"
       name="tech_stack[]"
       value="<?php echo htmlspecialchars($p['tech_stack']); ?>">

<label>Project Link</label>
<input type="url"
       name="project_link[]"
       value="<?php echo htmlspecialchars($p['project_link']); ?>">

</div>

<?php endforeach; ?>

<?php else: ?>

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

<?php endif; ?>

</div>

<button type="button" onclick="addProject()">➕ Add Project</button>

<h2 class="metallic-text">Experience</h2>
<div id="experience-container">

<?php if(!empty($experience)): ?>

<?php foreach($experience as $ex): ?>

<div class="block">

<label>Company Name</label>
<input type="text"
       name="company[]"
       value="<?php echo htmlspecialchars($ex['company']); ?>">

<label>Role</label>
<input type="text"
       name="role[]"
       value="<?php echo htmlspecialchars($ex['role']); ?>">

<label>Duration</label>
<input type="text"
       name="duration[]"
       value="<?php echo htmlspecialchars($ex['duration']); ?>">

<label>Description</label>
<textarea name="exp_desc[]"><?php echo htmlspecialchars($ex['description']); ?></textarea>

</div>

<?php endforeach; ?>

<?php else: ?>

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

<?php endif; ?>

</div>

<button type="button" onclick="addExperience()">➕ Add Experience</button>

<h2 class="metallic-text">Certificates</h2>

<div id="certificate-container">

<?php if(!empty($certificates)): ?>

<?php foreach($certificates as $c): ?>

<div class="block">

<input type="hidden" name="cert_id[]" value="<?php echo (int)$c['id']; ?>">

<label>Certificate Name</label>

<input type="text"
       name="cert_name[]"
       value="<?php echo htmlspecialchars($c['certificate_name']); ?>">

<label>Upload Certificate</label>

<input type="file" name="cert_file[]">

<?php if(!empty($c['certificate_file'])): ?>

<p style="margin-top:10px;">
    Existing certificate:
    <a href="<?php echo htmlspecialchars($c['certificate_file']); ?>"
       target="_blank">
        View Certificate
    </a>
</p>

<?php endif; ?>

</div>

<?php endforeach; ?>

<?php else: ?>

<div class="block">

<input type="hidden" name="cert_id[]" value="">

<label>Certificate Name</label>
<input type="text" name="cert_name[]">

<label>Upload Certificate</label>
<input type="file" name="cert_file[]">

</div>

<?php endif; ?>

</div>

<button type="button" onclick="addCertificate()">➕ Add Certificate</button>

<h2 class="metallic-text">Achievements</h2>

<div id="achievement-container">

<?php if(!empty($achievements)): ?>

<?php foreach($achievements as $a): ?>

<div class="block">

<label>Achievement Title</label>

<input type="text"
       name="ach_title[]"
       value="<?php echo htmlspecialchars($a['title']); ?>">

<label>Description</label>

<textarea name="ach_desc[]"><?php echo htmlspecialchars($a['description']); ?></textarea>

</div>

<?php endforeach; ?>

<?php else: ?>

<div class="block">

<label>Achievement Title</label>
<input type="text" name="ach_title[]">

<label>Description</label>
<textarea name="ach_desc[]"></textarea>

</div>

<?php endif; ?>

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
            <input type="hidden" name="cert_id[]" value="">

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

let skills = <?php echo json_encode($skills); ?>;

function displaySkills() {
    const list = document.getElementById("skillsList");
    list.innerHTML = "";

    skills.forEach(s => {
        const chip = document.createElement("div");
        chip.className = "skill-chip";

        const text = document.createElement("span");
        text.textContent = s;

        const remove = document.createElement("span");
        remove.textContent = "×";
        remove.onclick = () => removeSkill(s);

        chip.appendChild(text);
        chip.appendChild(remove);
        list.appendChild(chip);
    });
}

displaySkills();

function addSkill() {
    const skillInput = document.getElementById("skillInput");
    const skill = skillInput.value.trim();

    if(skill && !skills.includes(skill)) {
        skills.push(skill);
        displaySkills();
        skillInput.value = "";
    }
}

function removeSkill(skill) {
    skills = skills.filter(s => s !== skill);
    displaySkills();
}

document.querySelector("form").addEventListener("submit", function(e) {
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