<?php
require_once "db.php";

$portfolio_id = $_GET['pid'] ?? null;
if (!$portfolio_id) die("Portfolio not found");


$stmt = $conn->prepare("
    SELECT pd.*
    FROM portfolio_details pd
    WHERE pd.portfolio_id = ?
");

$stmt->bind_param("i", $portfolio_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    die("Portfolio details not found");
}


$education = [];
$res = $conn->query("SELECT * FROM education WHERE portfolio_id=$portfolio_id");
while ($row = $res->fetch_assoc()) $education[] = $row;


$skills = [];
$res = $conn->query("SELECT skill_name FROM skills WHERE portfolio_id=$portfolio_id");
while ($row = $res->fetch_assoc()) $skills[] = $row['skill_name'];


$projects = [];
$res = $conn->query("SELECT * FROM projects WHERE portfolio_id=$portfolio_id");
while ($row = $res->fetch_assoc()) $projects[] = $row;


$experience = [];
$res = $conn->query("SELECT * FROM experience WHERE portfolio_id=$portfolio_id");
while ($row = $res->fetch_assoc()) $experience[] = $row;


$certificates = [];
$res = $conn->query("SELECT * FROM certificates WHERE portfolio_id=$portfolio_id");
while ($row = $res->fetch_assoc()) $certificates[] = $row;

$achievements = [];
$res = $conn->query("SELECT * FROM achievements WHERE portfolio_id=$portfolio_id");
while ($row = $res->fetch_assoc()) $achievements[] = $row;
?>

<html lang="en">
<head>
<meta charset="UTF-8">
<title>Garima | Portfolio</title>
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

html { scroll-behavior: smooth; }

body {
  font-family: Arial, sans-serif;
  background: #001f3f;
  color: #f0f0f0;
  overflow-x: hidden;
}

/* Canvas */
#canvas {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: -2;
}

/* Navbar */
nav {
  position: fixed;
  width: 100%;
  padding: 15px;
  text-align: center;
  background: rgba(0,0,0,0.6);
  backdrop-filter: blur(8px);
  z-index: 10;
  overflow-x: auto;
  white-space: nowrap;
}

nav a {
  color: #00ffff;
  margin: 0 20px;
  text-decoration: none;
  font-weight: bold;
  transition: 0.3s;
}

nav a:hover,
nav a.active {
  color: white;
  text-shadow: 0 0 10px #00ffff;
}

/* Sections */
section {
  min-height: 100vh;
  padding: 120px 20px 80px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
}
.name{
  margin-top: 20px;
  display: flex;
  flex-direction: column;
}
/* About layout */
.about-container {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: center;
  gap: 40px;
  max-width: 1000px;
}

/* Profile image */
.profile-img {
  width: 260px;
  height: 260px;
  border-radius: 50%;
  
  background: #001f3f;
  border: 3px solid #00ffff;
  box-shadow: 0 0 25px #00ffff;
}

/* Card */
.card {
  background: rgba(255,255,255,0.08);
  backdrop-filter: blur(10px);
  padding: 30px;
  border-radius: 12px;
  border: 1px solid rgba(255,255,255,0.2);
  max-width: 500px;
}
#white{
  color:white;
}
h1 {
  color: #00ffff;
  margin-bottom: 15px;
}

h2 {
  color: #00ffff;
  margin-bottom: 20px;
}

.skills-section{
  display:flex;
  flex-direction:column;
  justify-content:center;
  align-items:center;
  min-height:100vh;
}

/* PC BODY */
.pc{
  position:relative;
  padding:20px;
}

/* SCREEN */
.screen{
  width:800px;
  height:450px;
  background:#001f3f;
  opacity:0.9;
  border-radius:15px;
  border: 1px solid #00f7ff;
  padding:40px;
  position:relative;
  overflow: hidden;
  
  
}

.stand{
  width:150px;
  height:20px;
  background:#00f7ff;
  margin:15px auto 0;
  border-radius:10px;
  border: 5px solid #00f7ff;
  box-shadow:0 0 15px #00f7ff;
}
.skills-title{
  color:#00ffff;
  font-size:40px;
  margin-bottom:30px;

}
.screen::before{
  content:"";
  position:absolute;
  inset:-3px; /* slightly outside border */
  border-radius:15px;

  background: linear-gradient(
      120deg,
      transparent 20%,
      transparent 40%,
      white 50%,
      transparent 60%,
      transparent 80%
  );

  animation: borderMove 4s linear infinite;
}

.screen::after{
  content:"";
  position:absolute;
  inset:2px;
  background:#001f3f; /* your screen color */
  border-radius:13px;
}
@keyframes borderMove{
  0%{
    transform: translateX(-100%);
  }
  100%{
    transform: translateX(100%);
  }
}
.screen::before{
  filter: blur(10px);
}
.skills-grid{
  position:relative;
  z-index:2; /* above glow mask */

  display:grid;
  grid-template-columns: repeat(auto-fit, minmax(160px,1fr));
  gap:20px;

  width:100%;
  height:100%;
  align-content:start;
}
.skill-item{
  display:flex;
  align-items:center;
  gap:10px;

  padding:12px 16px;
  border-radius:10px;

  background:rgba(0,255,255,0.05);
  border:1px solid rgba(0,255,255,0.25);

  font-size:15px;
  color:#00ffff;

  transition:0.3s;
}
.skill-item::before{
  content:"⚙";
  margin-right:8px;
  color:#00ffff;
  
}
.skill-item:hover{
  transform:translateY(-3px);
  box-shadow:
      0 0 10px #00ffff,
      inset 0 0 10px rgba(0,255,255,0.3);
}
.projects-section {
    padding: 100px 10%;
    position: relative;
    display: flex;
    flex-direction: column;
}

.section-title {
    text-align: center;
    font-size: 32px;
    color: cyan;
    margin-bottom: 60px;
}

.projects-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 40px;
}

/* Glass Card */
.project-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(0, 255, 255, 0.2);
    border-radius: 20px;
    padding: 30px;
    color: white;
    transition: 0.4s ease;
}

/* Glow on hover */
.project-card:hover {
    transform: translateY(-10px);
    border-color: cyan;
    box-shadow: 0 0 10px cyan,
            0 0 20px rgba(0,255,255,0.5),
            0 0 40px rgba(0,255,255,0.3);
}

.project-card h3 {
    margin-bottom: 15px;
}

.project-desc {
    font-size: 14px;
    opacity: 0.8;
    margin-bottom: 20px;
}

/* Tech stack pills */
.tech-stack {
    margin-bottom: 20px;
}

.tech-stack span {
    display: inline-block;
    padding: 6px 12px;
    margin: 5px 5px 0 0;
    border-radius: 20px;
    background: rgba(0,255,255,0.1);
    border: 1px solid rgba(0,255,255,0.3);
    font-size: 12px;
}

/* Link */
.project-link {
    color: cyan;
    text-decoration: none;
    font-size: 14px;
}

.project-link:hover {
    text-shadow: 0 0 10px cyan;
}
.project-card {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

/* =========================
   EXPERIENCE LOG (STATIC)
========================= */

.experience-log-section {
  padding: 100px 10%;
  text-align: center;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.log-title {
  font-size: 32px;
  color: cyan;
  margin-bottom: 50px;
  letter-spacing: 2px;
}

/* Terminal box */
.log-container {
  max-width: 900px;
  margin: auto;
  background: rgba(0,0,0,0.35);
  border-radius: 16px;
  padding: 30px;
  font-family: "Courier New", monospace;
  border: 1px solid rgba(0,255,255,0.25);
  box-shadow: 0 0 20px rgba(0,255,255,0.2);
  position: relative;
  overflow: hidden;
}

/* moving glow like codolio */
.log-container::before {
  content: "";
  position: absolute;
  top: 0;
  left: -120%;
  width: 60%;
  height: 100%;
  background: linear-gradient(
    120deg,
    transparent,
    rgba(0,255,255,0.3),
    transparent
  );
  animation: scanMove 6s linear infinite;
}

@keyframes scanMove {
  from { left: -120%; }
  to { left: 120%; }
}

/* log item */
.log-entry {
  text-align: left;
  padding: 20px 0;
  border-bottom: 1px dashed rgba(255,255,255,0.2);
  transition: 0.3s;
}

.log-entry:last-child {
  border-bottom: none;
}

.log-entry:hover {
  background: rgba(255,255,255,0.05);
  padding-left: 12px;
}

/* header line */
.log-header {
  font-size: 18px;
  color: #00ffff;
  margin-bottom: 6px;
}

.prompt {
  color: #ff7ad9;
  margin-right: 8px;
}

.role {
  font-weight: bold;
}

/* content */
.log-body p {
  margin: 4px 0;
  color: #eaeaea;
}

.label {
  color: #ff7ad9;
  font-weight: bold;
}

.desc {
  margin-top: 8px;
  line-height: 1.6;
  opacity: 0.9;
}

/* =========================
   EDUCATION TIMELINE
========================= */

.education-section {
  padding: 100px 10%;
  min-height: 100vh;
  text-align: center;
}

.edu-title {
  font-size: 32px;
  color: cyan;
  margin-bottom: 60px;
  letter-spacing: 2px;
}

/* timeline line */
.edu-timeline {
  position: relative;
  max-width: 800px;
  margin: auto;
  padding-left: 40px;
}

.edu-timeline::before {
  content: "";
  position: absolute;
  left: 10px;
  top: 0;
  width: 2px;
  height: 100%;
  background: linear-gradient(
    to bottom,
    transparent,
    cyan,
    transparent
  );
  box-shadow: 0 0 12px cyan;
}

/* each entry */
.edu-item {
  position: relative;
  margin-bottom: 50px;
  text-align: left;
}

/* glowing node */
.edu-dot {
  position: absolute;
  left: -2px;
  top: 10px;
  width: 18px;
  height: 18px;
  background: #001f3f;
  border: 2px solid cyan;
  border-radius: 50%;
  box-shadow: 0 0 12px cyan;
}

/* card */
.edu-card {
  background: rgba(255,255,255,0.06);
  backdrop-filter: blur(10px);
  padding: 22px 26px;
  border-radius: 14px;
  border: 1px solid rgba(0,255,255,0.25);
  transition: 0.3s;
}

.edu-card:hover {
  transform: translateX(10px);
  box-shadow:
    0 0 10px cyan,
    0 0 25px rgba(0,255,255,0.4);
}

/* text styling */
.edu-card h3 {
  color: #00ffff;
  margin-bottom: 6px;
}

.edu-inst {
  opacity: 0.85;
  margin-bottom: 10px;
}

/* bottom row */
.edu-meta {
  display: flex;
  justify-content: space-between;
  font-size: 14px;
  opacity: 0.9;
}

.score {
  color: #ff7ad9;
  font-weight: bold;
}

/* =========================
   CERTIFICATION VAULT
========================= */

.cert-section {
  padding: 100px 10%;
  text-align: center;
  min-height: 100vh;
  display: flex;
  flex-direction:column;
}

.cert-title {
  font-size: 32px;
  color: cyan;
  margin-bottom: 60px;
  letter-spacing: 2px;
}

/* GRID */
.cert-grid {
  
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 40px;
}

/* CARD */
.cert-item {
  width: 260px;
  max-width: 100%;
  position: relative;
  border-radius: 16px;
  overflow: hidden;
  cursor: pointer;
  background: rgba(255,255,255,0.05);
  border: 1px solid rgba(0,255,255,0.25);
  transition: 0.4s ease;
}

/* IMAGE */
.cert-item img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  display: block;
  transition: 0.5s;
}

/* holographic glow */
.cert-item::before {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(
      120deg,
      transparent,
      rgba(0,255,255,0.4),
      transparent
  );
  opacity: 0;
  transition: 0.5s;
}

/* overlay text */
.cert-overlay {
  position: absolute;
  bottom: 0;
  width: 100%;
  padding: 15px;
  background: linear-gradient(
      to top,
      rgba(0,0,0,0.8),
      transparent
  );
  color: white;
  font-size: 14px;
  text-align: left;
}

/* HOVER EFFECT */
.cert-item:hover {
  transform: translateY(-12px) scale(1.03);
  box-shadow:
    0 0 15px cyan,
    0 0 40px rgba(0,255,255,0.4);
}

.cert-item:hover::before {
  opacity: 1;
}

.cert-item:hover img {
  transform: scale(1.1);
}

/* =========================
   ACHIEVEMENTS SECTION
========================= */

.achievements-section {
  padding: 6rem 10%;
  text-align: center;
}

.section-title {
  font-size: 2.6rem;
  color: cyan;
  margin-bottom: 3rem;
}

/* container */
.achievements-container {
  display: flex;
  flex-direction: column;
  gap: 2rem;
  max-width: 900px;
  margin: auto;
}

/* achievement row */
.achievement-item {
  display: flex;
  align-items: center;
  gap: 1.8rem;
  padding: 1.6rem 2rem;
  border-radius: 18px;
  position: relative;

  background: rgba(255,255,255,0.04);
  backdrop-filter: blur(10px);

  border: 1px solid rgba(0,255,255,0.2);

  transition: 0.35s ease;
}

/* glow border */
.achievement-item::before {
  content: "";
  position: absolute;
  inset: 0;
  border-radius: inherit;
  padding: 2px;

  background: linear-gradient(90deg,#00fff0,#9d00ff);

  -webkit-mask:
    linear-gradient(#000 0 0) content-box,
    linear-gradient(#000 0 0);
  -webkit-mask-composite: xor;
  mask-composite: exclude;

  opacity: 0.6;
}

/* hover lift */
.achievement-item:hover {
  transform: translateY(-6px) scale(1.01);
}

/* ================= TROPHY ================= */

.trophy-glow {
  min-width: 50px;
  height: 50px;
  border-radius: 50%;

  display: flex;
  align-items: center;
  justify-content: center;

  font-size: 1.8rem;
  color: #ffd700;

  background: radial-gradient(circle, rgba(255,215,0,0.25), transparent);

  box-shadow:
    0 0 15px rgba(255,215,0,0.7),
    0 0 35px rgba(255,215,0,0.4);

  animation: trophyPulse 2.5s infinite ease-in-out;
}

/* glow animation */
@keyframes trophyPulse {
  0%,100% {
    box-shadow:
      0 0 15px rgba(255,215,0,0.7),
      0 0 35px rgba(255,215,0,0.4);
  }
  50% {
    box-shadow:
      0 0 25px rgba(255,215,0,1),
      0 0 55px rgba(255,215,0,0.7);
  }
}

/* text */
.achievement-info {
  text-align: left;
}

.achievement-info h3 {
  font-size: 1.25rem;
  margin-bottom: 0.4rem;
}

.achievement-info p {
  opacity: 0.85;
  line-height: 1.6;
}

.contact-section {
  text-align: center;
  padding: 100px 20px;
  display: flex;
  flex-direction:column;
}

.contact-icons {
  display: flex;
  justify-content: center;
  gap: 35px;
  margin: 40px 0;
  
}

.contact-icons a {
  font-size: 28px;
  color: cyan;
  width: 65px;
  height: 65px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  border: 1px solid rgba(0,255,255,0.4);
  background: rgba(255,255,255,0.05);
  transition: 0.3s;
}

.contact-icons a:hover {
  transform: translateY(-6px);
  box-shadow: 0 0 20px cyan;
}

.contact-info p {
  margin: 8px 0;
  opacity: 0.85;
}

.resume-btn {
  display: inline-block;
  margin-top: 35px;
  padding: 14px 28px;
  color: cyan;
  border: 1px solid cyan;
  background: rgba(255,255,255,0.05);
  text-decoration: none;
  border-radius: 30px;
  transition: 0.3s;
}

.resume-btn:hover {
  background: cyan;
  color: #001f3f;
  box-shadow: 0 0 20px cyan;
}

</style>
</head>

<body>
<body>

<canvas id="canvas"></canvas>

<!-- ================= NAV ================= -->
<nav>
  <a href="#about">About</a>
  <a href="#skills">Skills</a>
  <a href="#projects">Projects</a>
  <a href="#experience">Experience</a>
  <a href="#education">Education</a>
  <a href="#certifications">Certifications</a>
  <a href="#achievements">Achievements</a>
  <a href="#contact">Contact</a>
</nav>


<!-- ================= ABOUT ================= -->
<section id="about">
<div class="about-container reveal">

<div class="left-side">
  <img src="<?= htmlspecialchars($user['photo']) ?>" class="profile-img">
  <h1 class="name"><?= htmlspecialchars($user['name']) ?></h1>
</div>

<div class="right-side">

  <div class="welcome-text">
    <h1><span id="white">Welcome to my</span> Portfolio</h1>
  </div>

  <div class="card about-card">
    <p><?= nl2br(htmlspecialchars($user['about'])) ?></p>
  </div>

</div>
</div>
</section>



<!-- ================= SKILLS ================= -->
<section id="skills" class="skills-section">

<h2 class="skills-title">My Skills</h2>

<div class="pc">
<div class="screen">

<div class="skills-grid">
<?php foreach($skills as $skill){ ?>
  <div class="skill-item"><?= htmlspecialchars($skill) ?></div>
<?php } ?>
</div>

</div>
<div class="stand"></div>
</div>

</section>



<!-- ================= PROJECTS ================= -->
<section id="projects" class="projects-section">

<h2 class="section-title">Projects</h2>

<div class="projects-container">

<?php foreach($projects as $p){ ?>
<div class="project-card">

<h3><?= htmlspecialchars($p['title']) ?></h3>

<p class="project-desc">
<?= htmlspecialchars($p['description']) ?>
</p>

<div class="tech-stack">
<?php foreach(explode(',', $p['tech_stack']) as $tech){ ?>
  <span><?= htmlspecialchars(trim($tech)) ?></span>
<?php } ?>
</div>

<?php if(!empty($p['project_link'])){ ?>
<a href="<?= htmlspecialchars($p['project_link']) ?>"
   target="_blank"
   class="project-link">
View Project →
</a>
<?php } ?>

</div>
<?php } ?>

</div>
</section>



<!-- ================= EXPERIENCE ================= -->
<section id="experience" class="experience-log-section">

<h2 class="log-title">EXPERIENCE</h2>

<div class="log-container">

<?php foreach($experience as $ex){ ?>
<div class="log-entry">

<div class="log-header">
<span class="prompt">></span>
<span class="role"><?= htmlspecialchars($ex['role']) ?></span>
</div>

<div class="log-body">
<p><span class="label">COMPANY:</span> <?= htmlspecialchars($ex['company']) ?></p>
<p><span class="label">DURATION:</span> <?= htmlspecialchars($ex['duration']) ?></p>

<p class="desc">
<?= htmlspecialchars($ex['description']) ?>
</p>
</div>

</div>
<?php } ?>

</div>
</section>



<!-- ================= EDUCATION ================= -->
<section id="education" class="education-section">

<h2 class="edu-title">ACADEMIC RECORD</h2>

<div class="edu-timeline">

<?php foreach($education as $e){ ?>
<div class="edu-item">

<div class="edu-dot"></div>

<div class="edu-card">
<h3><?= htmlspecialchars($e['degree']) ?></h3>
<p class="edu-inst"><?= htmlspecialchars($e['institute']) ?></p>

<div class="edu-meta">
<span><?= htmlspecialchars($e['duration']) ?></span>
<span class="score"><?= htmlspecialchars($e['score']) ?></span>
</div>
</div>

</div>
<?php } ?>

</div>
</section>



<!-- ================= CERTIFICATIONS ================= -->
<section id="certifications" class="cert-section">

<h2 class="cert-title">CERTIFICATIONS</h2>

<div class="cert-grid">

<?php foreach($certificates as $c){ ?>
<div class="cert-item">
  <a href="<?= htmlspecialchars($c['certificate_file']) ?>"
     target="_blank"
     rel="noopener noreferrer">

    <img src="<?= htmlspecialchars($c['certificate_file']) ?>">

    <div class="cert-overlay">
      <p><?= htmlspecialchars($c['certificate_name']) ?></p>
    </div>

  </a>
</div>
<?php } ?>

</div>
</section>



<!-- ================= ACHIEVEMENTS ================= -->
<section id="achievements" class="achievements-section">

<h2 class="section-title">Achievements</h2>

<div class="achievements-container">

<?php foreach($achievements as $a){ ?>
<div class="achievement-item">

<div class="trophy-glow">
<i class="fa-solid fa-trophy"></i>
</div>

<div class="achievement-info">
<h3><?= htmlspecialchars($a['title']) ?></h3>
<p><?= htmlspecialchars($a['description']) ?></p>
</div>

</div>
<?php } ?>

</div>
</section>



<!-- ================= CONTACT ================= -->
<section id="contact" class="contact-section">

<h2 class="section-title">Let's Connect</h2>

<div class="contact-icons">

<a href="<?= htmlspecialchars($user['github']) ?>" target="_blank">
<i class="fab fa-github"></i>
</a>

<a href="<?= htmlspecialchars($user['linkedin']) ?>" target="_blank">
<i class="fab fa-linkedin"></i>
</a>

<a href="mailto:<?= htmlspecialchars($user['email']) ?>">
<i class="fas fa-envelope"></i>
</a>

<a href="tel:<?= htmlspecialchars($user['phone']) ?>">
<i class="fas fa-phone"></i>
</a>

</div>

<div class="contact-info">
<p>📍 <?= htmlspecialchars($user['address']) ?></p>
<p>✉ <?= htmlspecialchars($user['email']) ?></p>
<p>📞 <?= htmlspecialchars($user['phone']) ?></p>
</div>

<a href="<?= htmlspecialchars($user['resume']) ?>"
   download
   class="resume-btn">
Download Resume
</a>

</section>

<script>
/* Particle Background */
const canvas = document.getElementById("canvas");
const ctx = canvas.getContext("2d");

function resizeCanvas(){
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
}
resizeCanvas();
window.addEventListener("resize", resizeCanvas);

const dots = [];
for(let i=0;i<100;i++){
  dots.push({
    x: Math.random()*canvas.width,
    y: Math.random()*canvas.height,
    dx: (Math.random()-0.5)*0.5,
    dy: (Math.random()-0.5)*0.5,
    r:2
  });
}

function draw(){
  ctx.clearRect(0,0,canvas.width,canvas.height);
  dots.forEach((dot,i)=>{
    ctx.beginPath();
    ctx.arc(dot.x,dot.y,dot.r,0,Math.PI*2);
    ctx.fillStyle="white";
    ctx.fill();

    dot.x+=dot.dx;
    dot.y+=dot.dy;

    if(dot.x<0||dot.x>canvas.width) dot.dx*=-1;
    if(dot.y<0||dot.y>canvas.height) dot.dy*=-1;

    for(let j=i+1;j<dots.length;j++){
      let dx=dot.x-dots[j].x;
      let dy=dot.y-dots[j].y;
      let dist=Math.sqrt(dx*dx+dy*dy);
      if(dist<120){
        ctx.beginPath();
        ctx.strokeStyle="rgba(0,255,255,0.15)";
        ctx.moveTo(dot.x,dot.y);
        ctx.lineTo(dots[j].x,dots[j].y);
        ctx.stroke();
      }
    }
  });
  requestAnimationFrame(draw);
}
draw();

/* Reveal on scroll */
function revealOnScroll(){
  document.querySelectorAll(".reveal").forEach(el=>{
    if(el.getBoundingClientRect().top < window.innerHeight-100){
      el.classList.add("active");
    }
  });
}
window.addEventListener("scroll", revealOnScroll);
window.addEventListener("load", revealOnScroll);


</script>

</body>
</html>
<?php if(isset($_GET['preview'])): ?>
    <div style="text-align:center; padding:20px; background:#000;">
        <a href="save_template.php?pid=<?php echo $portfolio_id; ?>&template=temp-4"
           style="padding:12px 25px;
                  background:#39e6d6;
                  color:#000;
                  font-weight:bold;
                  border-radius:8px;
                  text-decoration:none;">
            💾 Save This Template
        </a>
    </div>
<?php endif; ?>