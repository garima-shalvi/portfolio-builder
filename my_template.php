<?php
require_once "db.php";
$user_id=$_GET['user_id']??null;
if(!$user_id)die("User not found");

$stmt=$conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i",$user_id);
$stmt->execute();
$user=$stmt->get_result()->fetch_assoc();
$stmt->close();

$skills=[];
$res=$conn->query("SELECT skill_name FROM skills WHERE user_id=$user_id");
while($row=$res->fetch_assoc())$skills[]=$row['skill_name'];

$projects=[];
$res=$conn->query("SELECT * FROM projects WHERE user_id=$user_id");
while($row=$res->fetch_assoc())$projects[]=$row;

$experience=[];
$res=$conn->query("SELECT * FROM experience WHERE user_id=$user_id");
while($row=$res->fetch_assoc())$experience[]=$row;

$education=[];
$res=$conn->query("SELECT * FROM education WHERE user_id=$user_id");
while($row=$res->fetch_assoc())$education[]=$row;

$certificates=[];
$res=$conn->query("SELECT * FROM certificates WHERE user_id=$user_id");
while($row=$res->fetch_assoc())$certificates[]=$row;

$achievements=[];
$res=$conn->query("SELECT * FROM achievements WHERE user_id=$user_id");
while($row=$res->fetch_assoc())$achievements[]=$row;
?>
<html>
<head>
<style>
* {
margin: 0;
padding: 0;
box-sizing: border-box;
}
body{min-height: 100vh;
background: linear-gradient(135deg,#00736e,#6a00c9);
color: #ffffff;
 }
 .hero {position: relative;
min-height: 100vh;
margin: 0 auto;
display: flex;
align-items: center;
justify-content: space-between;
margin-left: 50px;
}
.hero-left {
flex: 1;
}


.hero-left h1 {
font-size: 3rem;
margin-bottom: 1rem;
margin-top:-10px;
}


.hero-left h1 span {
color: #ffaedb;
}


.hero-left p {
font-size: 1.2rem;
line-height: 1.7;
max-width: 500px;
}


.hero-right {
flex: 1;
display: flex;
flex-direction: column;
align-items: center;
}


.hero-right img {
width: 260px;
height: 260px;
object-fit: cover;
box-shadow: 0px 0px 20px lavender;
border-radius: 50%;
border: 4px solid rgba(255, 255, 255, 0.6);
margin-bottom: 1.5rem;
background-color: rgba(255,255,255,0.2);
}


.links {
display: flex;
gap: 1.2rem;
}


.links a {
width: 48px;
height: 48px;
display: flex;
align-items: center;
justify-content: center;
border-radius: 50%;
background: linear-gradient(90deg,#00a2ff, #ac5696);
color: #ffffff;
font-size: 1.4rem;
transition: all 0.3s ease;
text-decoration: none;
}


.links a:hover {
background: linear-gradient(90deg,#3a8ec4,#b0127c);
transform: translateY(-3px);
}
@media (max-width: 768px) {
.hero {
flex-direction: column;
text-align: center;
}
.hero-left h1 {
font-size: 2.4rem;
}
.hero-left p {
margin: 0 auto;
}
}
.actions {
margin-top: 2rem;
display: flex;
gap: 1rem;
flex-wrap: wrap;
}


.action-item button,
.download-btn {
min-width: 160px;
justify-content: center;
padding: 0.8rem 1rem;
border-radius: 30px;
border: none;
cursor: pointer;
background: linear-gradient(90deg,#00a2ff, #ac5696);
color: #fff;

font-size: 1rem;
display: flex;
align-items: center;
gap: 0.6rem;
transition: all 0.3s ease;
text-decoration: none;
}


.action-item button:hover,
.download-btn:hover {
background: linear-gradient(90deg,#3a8ec4,#b0127c);
}

.info {
margin-top: 0.4rem;
padding-left: 1.8rem;
font-size: 0.95rem;
display: none;
opacity: 0.9;
}
.scroll-indicator {
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
}

.scroll-indicator span {
  display: block;
  width: 26px;
  height: 40px;
  border: 2px solid rgba(255,255,255,0.8);
  border-radius: 20px;
  position: relative;
}

.scroll-indicator span::after {
  content: '';
  width: 6px;
  height: 6px;
  background: white;
  border-radius: 50%;
  position: absolute;
  top: 8px;
  left: 50%;
  transform: translateX(-50%);
  animation: scrollDot 1.6s infinite;
}

@keyframes scrollDot {
  0% { opacity: 0; top: 8px; }
  30% { opacity: 1; }
  100% { opacity: 0; top: 22px; }
}
.about-section {
  width: 100%;
  
  padding: 5rem 10%;
  

  transform: scaleY(0);          
  transform-origin: top;         
  transition: transform 0.9s ease;
}


.about-section h2 {
  font-size: 2.5rem;
  margin-bottom: 1.5rem;
  color: #ffaedb;;
}

.about-section p {
  font-size: 1.2rem;
  max-width: 700px;
  line-height: 1.8;
}


.about-section.show {
  transform: scaleY(1);
}
.about-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 4rem;
  width:100%;
}

.about-text {
  flex: 1;
}

.about-illustration {
  flex: 1;
  display: flex;
  justify-content: center;
}

.about-illustration img ,.section-illustration img{
  width: 260px;      
  opacity: 0.9;
  animation: float 4s ease-in-out infinite;
}


@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

.skills-section {
  
  padding: 5rem 8%;
  text-align: center;
}

.skills-section h2 {
  font-size: 2.6rem;
  color: #ffaedb;
  margin-bottom: 3rem;
}


.skills-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 1.5rem;
}


.skill-card {
  position: relative;
  padding: 1.4rem;
  border-radius: 18px;
  background: transparent;   
  color: white;
  font-size: 1.1rem;
  font-weight: 500;
  text-align: center;
  box-shadow: 0 0 15px rgba(157, 0, 255, 0.5);
  opacity: 0;
  transform: translateY(30px);
}


.skill-card::before {
  content: "";
  position: absolute;
  inset: 0;
  border-radius: inherit;
  padding: 2px; 

  background: linear-gradient(
    90deg,
    #00fff0,
    #9d00ff
  );

  -webkit-mask:
    linear-gradient(#000 0 0) content-box,
    linear-gradient(#000 0 0);
  -webkit-mask-composite: xor;
          mask-composite: exclude;

  filter: drop-shadow(0 0 6px rgba(157, 0, 255, 0.7))
          drop-shadow(0 0 6px rgba(0, 255, 240, 0.6));

  pointer-events: none;
}

.skill-card {
  animation: floatSkill 4s ease-in-out infinite;
}

@keyframes floatSkill {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-8px);
  }
}



.skills-section.show .skill-card {
  animation: skillFadeUp 0.6s ease forwards,
  floatSkill 4s ease-in-out infinite;
}


.skills-section.show .skill-card:nth-child(1) { animation-delay: 0.1s; }
.skills-section.show .skill-card:nth-child(2) { animation-delay: 0.2s; }
.skills-section.show .skill-card:nth-child(3) { animation-delay: 0.3s; }
.skills-section.show .skill-card:nth-child(4) { animation-delay: 0.4s; }
.skills-section.show .skill-card:nth-child(5) { animation-delay: 0.5s; }
.skills-section.show .skill-card:nth-child(6) { animation-delay: 0.6s; }

@keyframes skillFadeUp {
  to {
    opacity: 1;
    
  }
}


.skill-card:hover {
  transform: translateY(-6px) scale(1.03);
  transition: 0.3s ease;
}
.projects-section {
  padding: 5rem 10%;
  text-align: center;
  width: 60%;
  margin: 0 auto;
}

.projects-section h2 {
  font-size: 2.6rem;
  color: #ffaedb;
  margin-bottom: 2.5rem;
  text-align: center;
}


.project-item {
  margin-bottom: 1.2rem;
  border-radius: 16px;
  overflow: hidden;
  background: linear-gradient(90deg,#8c52ff,#009b9e);
  backdrop-filter: blur(8px);
}


.project-header {
  width: 100%;
  padding: 1.2rem 1.5rem;
  background: transparent;
  border: none;
  color: #fff;
  font-size: 1.2rem;
  font-weight: 500;
  display: flex;
  justify-content: space-between;
  align-items: center;
  cursor: pointer;
}

.project-header i {
  transition: transform 0.3s ease;
}


.project-content {
  max-height: 0;
  overflow: hidden;
  padding: 0 1.5rem;
  transition: max-height 0.5s ease, padding 0.3s ease;
}

.project-content p {
  margin: 0.8rem 0;
  font-size: 1rem;
  display: flex;
  gap: 0.6rem;
  align-items: flex-start;
}

.project-content i {
  background: linear-gradient(90deg, #00e0ff, #ffaedb);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-top: 3px;
}


.divider {
  height: 2px;
  width: 100%;
  background: linear-gradient(
    90deg,
    transparent,
    #00e0ff,
    #ffaedb,
    transparent
  );
  margin: 0.8rem 0 1.2rem;
  opacity: 0;
  transition: opacity 0.3s ease;
}



.project-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  margin: 1rem 0 1.2rem;
  color: #ffffff;
  text-decoration: none;
  font-weight: 500;
}

.project-link:hover {
  text-decoration: underline;
}


.project-item.active .project-content {
  max-height: 500px;
  padding-bottom: 1.2rem;
}

.project-item.active .project-header i {
  transform: rotate(180deg);
}

.project-item.active .divider {
  opacity: 1;
}
.experience-section,
.education-section {
  padding: 5rem 10%;
  min-height: 100vh;
  display: flex;
  justify-content: center;
}

.experience-section .section-content,
.education-section .section-content {
  max-width: 1100px;
  width: 100%;
}

.section-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 4rem;
}


.section-text {
  flex: 1.1;
}

.section-text h2 {
  font-size: 2.6rem;
  color: #ffaedb;
  margin-bottom: 2rem;
}

.section-illustration {
  flex: 0.9;
  display: flex;
  justify-content: center;
}

.section-illustration img {
  width: 280px;
  opacity: 0.9;
}

.experience-card {
  background: linear-gradient(135deg, #00a2ff, #ac5696);
  padding: 1.6rem;
  border-radius: 18px;
  margin-bottom: 1.5rem;
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.experience-card h3 {
  font-size: 1.3rem;
  margin-bottom: 0.3rem;
}

.company {
  display: block;
  font-weight: 600;
  opacity: 0.95;
}

.duration {
  display: block;
  font-size: 0.9rem;
  opacity: 0.8;
  margin-bottom: 0.6rem;
}

.experience-card p {
  line-height: 1.6;
  font-size: 1rem;
}
.education-card {
  background: linear-gradient(135deg, #3a8ec4, #b0127c);
  padding: 1.6rem;
  border-radius: 18px;
  margin-bottom: 1.5rem;
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.education-card h3 {
  font-size: 1.3rem;
  margin-bottom: 0.3rem;
}

.institute {
  display: block;
  font-weight: 600;
}

.education-card p {
  margin-top: 0.4rem;
  font-size: 1rem;
}

@media (max-width: 900px) {
  .section-content,
  .section-content.reverse {
    flex-direction: column;
    text-align: center;
  }

  .section-illustration {
    order: -1; 
  }

  .section-illustration img {
    width: 220px;
    margin-bottom: 2rem;
  }
}



.experience-card:hover,
.education-card:hover {
  transform: translateY(-6px);
  transition: 0.3s ease;
}

.certifications-section {
  padding: 5rem 10%;
  text-align: center;
}

.certifications-section h2 {
  font-size: 2.6rem;
  color: #ffaedb;
  margin-bottom: 3rem;
}

.cert-card h3 {
  margin-bottom: 1rem;
}

.cert-slider {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1.2rem;
}


.cert-viewport {
  width: 420px;
  max-width: 90%;
  overflow: hidden;
}


.cert-track {
  display: flex;
  transition: transform 0.5s ease;
}

.cert-card {
  flex: 0 0 100%;
  padding: 1.5rem;
  border-radius: 18px;
  background: transparent;
  text-align: center;

  border: 2px solid rgba(255, 215, 0, 0.6);
  box-shadow: 0 0 18px rgba(255, 215, 0, 0.45);
}

.cert-card img {
  width: 100%;
  border-radius: 12px;
  background: #fff;
  padding: 10px;
  border: 2px solid rgba(255, 215, 0, 0.6);
  box-shadow: 0 0 12px rgba(255, 215, 0, 0.4);
}



.nav-btn {
  background: rgba(0,0,0,0.35);
  border: none;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  font-size: 1.6rem;
  color: #ffd700;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
}

.nav-btn:hover {
  box-shadow: 0 0 12px rgba(255, 215, 0, 0.8);
}





.achievements-section {
  padding: 5rem 10%;
  text-align: center;
}

.achievements-section h2 {
  font-size: 2.6rem;
  color: #ffaedb;
  margin-bottom: 3rem;
}

.achievements-grid {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1.5rem;
}

.achievement-card {
  display: inline-flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem 1.5rem;
  border-radius: 18px;
  background: transparent;
  position: relative;
  box-shadow: inset 0 0 0 2px transparent;
  width: auto;
  max-width: 100%;
}

.achievement-card::before {
  content: "";
  position: absolute;
  inset: 0;
  border-radius: inherit;
  padding: 2px;
  background: linear-gradient(135deg, #ffd700, #ffae00);
  -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
  -webkit-mask-composite: xor;
  mask-composite: exclude;
  filter: drop-shadow(0 0 10px rgba(255, 215, 0, 0.6));
}

.achievement-card h3 {
  margin-bottom: 0.3rem;
}

.achievement-card p {
  font-size: 0.95rem;
  opacity: 0.9;
}

.achievement-card i {
  font-size: 1.6rem;
  color: #ffd700;
}

.achievement-card .right {
  margin-left: auto;
}


.achievements-grid.side-by-side {
  display: grid;
  justify-content: center;
  gap: 1.5rem;
}

.achievements-grid.side-by-side .achievement-card {
  width: fit-content;
  margin: 0 auto;
}




</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>
<body>

<section class="hero">
<div class="hero-left">
<h1>Hi! 👋<br/><span>Welcome</span> to my portfolio,<br/>I am <span><?php echo $user['name']; ?></span></h1>
<p>I’m glad you’re here. This is where I share my work, skills, and journey.
Feel free to explore and connect with me.</p>

<div class="actions">
<div class="action-item">
<button onclick="toggleInfo('phone-info')">
<i class="fa-solid fa-phone"></i> Contact Me
</button>
<div class="info" id="phone-info">📞 <?php echo $user['phone']; ?></div>
</div>

<div class="action-item">
<button onclick="toggleInfo('address-info')">
<i class="fa-solid fa-house"></i> Address
</button>
<div class="info" id="address-info">🏠 <?php echo $user['address']; ?></div>
</div>

<a class="download-btn" href="<?php echo $user['resume']; ?>" download>
<i class="fa-solid fa-download"></i> Download Resume
</a>
</div>
</div>

<div class="hero-right">
<img src="<?php echo $user['photo']; ?>">
<div class="links">
<a href="mailto:<?php echo $user['email']; ?>"><i class="fa-solid fa-envelope"></i></a>
<a href="<?php echo $user['github']; ?>" target="_blank"><i class="fa-brands fa-github"></i></a>
<a href="<?php echo $user['linkedin']; ?>" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
</div>
</div>
</section>

<section class="about-section">
<div class="about-content">
<div class="about-text">
<h2>About Me</h2>
<p><?php echo nl2br($user['about']); ?></p>
</div>
<div class="about-illustration">
<img src="about-icon.svg">
</div>
</div>
</section>

<section class="skills-section">
<h2>Skills</h2>
<div class="skills-grid">
<?php foreach($skills as $s){ ?>
<div class="skill-card neon"><?php echo $s; ?></div>
<?php } ?>
</div>
</section>

<section class="projects-section">
<h2>Projects</h2>
<?php foreach($projects as $p){ ?>
<div class="project-item">
<button class="project-header">
<span><?php echo $p['title']; ?></span>
<i class="fa-solid fa-chevron-down"></i>
</button>
<div class="project-content">
<div class="divider"></div>
<p><i class="fa-solid fa-file-lines"></i><?php echo $p['description']; ?></p>
<p><i class="fa-solid fa-code"></i><strong>Tech Stack:</strong> <?php echo $p['tech_stack']; ?></p>
<?php if($p['project_link']){ ?>
<a href="<?php echo $p['project_link']; ?>" target="_blank" class="project-link">
<i class="fa-solid fa-arrow-up-right-from-square"></i>View Project
</a>
<?php } ?>
</div>
</div>
<?php } ?>
</section>

<section class="experience-section">
<div class="section-content">
<div class="section-text">
<h2>Experience</h2>
<?php foreach($experience as $ex){ ?>
<div class="experience-card">
<h3><?php echo $ex['role']; ?></h3>
<span class="company"><?php echo $ex['company']; ?></span>
<span class="duration"><?php echo $ex['duration']; ?></span>
<p><?php echo $ex['description']; ?></p>
</div>
<?php } ?>
</div>
<div class="section-illustration">
<img src="experience-icon.svg">
</div>
</div>
</section>

<section class="education-section">
<div class="section-content reverse">
<div class="section-illustration">
<img src="education-icon.svg">
</div>
<div class="section-text">
<h2>Education</h2>
<?php foreach($education as $e){ ?>
<div class="education-card">
<h3><?php echo $e['degree']; ?></h3>
<span class="institute"><?php echo $e['institute']; ?></span>
<span class="duration"><?php echo $e['duration']; ?></span>
<p>Score: <?php echo $e['score']; ?></p>
</div>
<?php } ?>
</div>
</div>
</section>

<section class="certifications-section">
  <h2>Certifications</h2>

  <div class="cert-slider">

  <button class="nav-btn left" onclick="prevCert()">
    <i class="fa-solid fa-chevron-left"></i>
  </button>

  <div class="cert-viewport">
    <div class="cert-track">
      <?php foreach($certificates as $c){ ?>
        <div class="cert-card">
          <h3><?= $c['certificate_name'] ?></h3>
          <a href="<?= $c['certificate_file'] ?>" target="_blank">
            <img src="<?= $c['certificate_file'] ?>">
          </a>
        </div>
      <?php } ?>
    </div>
  </div>

  <button class="nav-btn right" onclick="nextCert()">
    <i class="fa-solid fa-chevron-right"></i>
  </button>

</div>

</section>


<section class="achievements-section">
<h2>Achievements</h2>
<div class="achievements-grid">
<?php foreach($achievements as $a){ ?>
<div class="achievement-card">
<i class="fa-solid fa-trophy"></i>
<div>
<h3><?php echo $a['title']; ?></h3>
<p><?php echo $a['description']; ?></p>
</div>
<i class="fa-solid fa-trophy right"></i>
</div>
<?php } ?>
</div>
</section>

<script>
function toggleInfo(id) {
const el = document.getElementById(id);
el.style.display = el.style.display === 'block' ? 'none' : 'block';
}

const aboutSection = document.querySelector('.about-section');
const skillsSection = document.querySelector('.skills-section');

window.addEventListener('scroll', () => {
  const triggerPoint = window.innerHeight * 0.75;

  if (aboutSection.getBoundingClientRect().top < triggerPoint) {
    aboutSection.classList.add('show');
  }

  if (skillsSection.getBoundingClientRect().top < triggerPoint) {
    skillsSection.classList.add('show');
  }
});


document.querySelectorAll('.project-header').forEach(button => {
  button.addEventListener('click', () => {
    const item = button.parentElement;

    
    document.querySelectorAll('.project-item').forEach(i => {
      if (i !== item) i.classList.remove('active');
    });

    item.classList.toggle('active');
  });
});


let certIndex = 0;
const track = document.querySelector('.cert-track');
const certCount = document.querySelectorAll('.cert-card').length;

function nextCert() {
  certIndex = (certIndex + 1) % certCount;
  track.style.transform = `translateX(-${certIndex * 100}%)`;
}

function prevCert() {
  certIndex = (certIndex - 1 + certCount) % certCount;
  track.style.transform = `translateX(-${certIndex * 100}%)`;
}
</script>

</body>
</html>
