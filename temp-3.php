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
$stmt = $conn->prepare("SELECT * FROM education WHERE portfolio_id=?");
$stmt->bind_param("i", $portfolio_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) $education[] = $row;
$stmt->close();

$skills = [];
$stmt = $conn->prepare("SELECT skill_name FROM skills WHERE portfolio_id=?");
$stmt->bind_param("i", $portfolio_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) $skills[] = $row['skill_name'];
$stmt->close();

$projects = [];
$stmt = $conn->prepare("SELECT * FROM projects WHERE portfolio_id=?");
$stmt->bind_param("i", $portfolio_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) $projects[] = $row;
$stmt->close();

$experience = [];
$stmt = $conn->prepare("SELECT * FROM experience WHERE portfolio_id=?");
$stmt->bind_param("i", $portfolio_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) $experience[] = $row;
$stmt->close();

$certificates = [];
$stmt = $conn->prepare("SELECT * FROM certificates WHERE portfolio_id=?");
$stmt->bind_param("i", $portfolio_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) $certificates[] = $row;
$stmt->close();

$achievements = [];
$stmt = $conn->prepare("SELECT * FROM achievements WHERE portfolio_id=?");
$stmt->bind_param("i", $portfolio_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) $achievements[] = $row;
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($user['name']); ?> | Portfolio</title>
<link rel="stylesheet" href="themes/lavender/style.css">
</head>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap');

:root{
    --primary:#6d28d9;
    --secondary:#c084fc;
    --bg:#140c24;
    --card:#1f1435;
    --text:#f3e8ff;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}
body{
    background:
        linear-gradient(rgba(20,12,36,0.45), rgba(20,12,36,0.45)),
        url("https://static.vecteezy.com/system/resources/previews/013/141/947/large_2x/pretty-purple-nebula-galaxy-astrology-deep-outer-space-cosmos-background-beautiful-abstract-illustration-art-dust-free-photo.jpg");

    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;

    color: var(--text);
    overflow-x: hidden;
}

.blob{
    position:fixed;
    width:400px;
    height:400px;
    border-radius:50%;
    filter:blur(120px);
    z-index:-1;
    animation:float 12s infinite ease-in-out;
}

.blob1{
    background:#8b5cf6;
    top:-100px;
    left:-100px;
}

.blob2{
    background:#c084fc;
    bottom:-150px;
    right:-100px;
    animation-delay:6s;
}

@keyframes float{
    0%{ transform:translateY(0);}
    50%{ transform:translateY(40px);}
    100%{ transform:translateY(0);}
}

.hero{
    padding:100px 5%;
}

.hero-container{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:60px;
    flex-wrap:wrap;
}

.hero-left{
    flex:1;
    display:flex;
    justify-content:center;
}

.hero-pic{
    width:300px;
    height:300px;
    border-radius:20%;
    object-fit:cover;
    border:6px solid var(--secondary);
    box-shadow:0 0 40px rgba(192,132,252,.6);
    transition:.4s ease;
}

.hero-pic:hover{
    transform:scale(1.15);
}

.hero-right{
    flex:2;
}

.hero-right h1{
    font-size:45px;
    background:linear-gradient(90deg,#ffffff,#c084fc,#8b5cf6);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.about{
    font-size:16px;
    line-height:1.6;
    max-width:600px;
    margin-bottom:25px;
    opacity:.9;
}

.hero-buttons a{
    display:inline-block;
    margin:8px 10px 8px 0;
    padding:12px 22px;
    border-radius:30px;
    background:var(--secondary);
    color:#140c24;
    text-decoration:none;
    font-weight:500;
    transition:.3s ease;
}

.hero-buttons a:hover{
    box-shadow:0 0 20px rgba(192,132,252,.8);
    transform:translateY(-4px);
}

.hero-illustration img{
    width:300px;
    margin-top:30px;
    animation:floatImage 6s ease-in-out infinite;
}

@keyframes floatImage{
    0%{transform:translateY(0);}
    50%{transform:translateY(-15px);}
    100%{transform:translateY(0);}
}

.container{
    width:85%;
    max-width:1000px;
    margin:auto;
    padding:60px 0;
}

section{
    margin-bottom:80px;
}
.hea{
  margin-bottom:30px;
    color:var(--secondary);
}
section h1{
    text-align:center;
    margin-bottom:40px;
    font-size:38px;
    font-weight:700;
    background:linear-gradient(90deg,#f3e8ff,#d8b4fe,#fbcfe8);

    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
    letter-spacing:1px;
    position:relative;
}
section h1::after{
    content:"";
    display:block;
    width:80px;
    height:4px;
    margin:10px auto 0;
    border-radius:5px;
    background:linear-gradient(90deg,#c084fc,#8b5cf6);
    box-shadow:0 0 10px rgba(192,132,252,.8);
}
@keyframes glowMove{
    0%{ filter:drop-shadow(0 0 5px #caa9ec); }
    50%{ filter:drop-shadow(0 0 20px #a482f4); }
    100%{ filter:drop-shadow(0 0 5px #c084fc); }
}

section h1{
    animation:glowMove 3s infinite ease-in-out;
}
.emoji{
    color:initial;
    margin-right:8px;
}

h3{
    color:rgb(237, 198, 250);
    font-size:25px;
}

.card{
    box-shadow:0 0 3px 3px rgba(193, 156, 230, 0.96);
    background:var(--card);
    padding:25px;
    border-radius:18px;
    margin-bottom:20px;
    transition:.3s;
}

.card:hover{
    transform:translateY(-8px) scale(1.02);
    box-shadow:0 15px 30px rgba(192,132,252,.3);
}

.skills span{
    box-shadow:0 0 3px 3px rgba(193, 156, 230, 0.96);
    display:inline-block;
    background:rgba(192,132,252,.2);
    padding:10px 18px;
    border-radius:20px;
    margin:6px;
    transition:all 0.5s ease;
}
.skills span:hover{
    transform:scale(1.3)
}

.certificate-grid{
    display:flex;
    flex-wrap:wrap;
    gap:40px;
    justify-content:center;
}

.certificate-card{
    width:300px;
    height:250px;
    background:var(--card);
    border-radius:15px;
    overflow:hidden;
    transition:transform .3s ease, box-shadow .3s ease;
    display:flex;
    flex-direction:column;
}

.certificate-card img{
    width:100%;
    height:250px;
    object-fit:cover;
    display:block;
}

.certificate-title{
    height:50px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:16px;
    padding:5px;
    color:var(--text);
}

.certificate-card:hover{
    transform:scale(1.5);
    box-shadow:0 15px 30px rgba(192,132,252,.4);
}

.achievement-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:25px;
}

.achievement-card{
    background:linear-gradient(135deg,#2a1b47,#3b1e69);
    padding:25px;
    border-radius:18px;
    transition:.3s;
}

.achievement-card:hover{
    transform:translateY(-8px);
    box-shadow:0 15px 35px rgba(192,132,252,.5);
}

.btn{
    display:inline-block;
    margin-top:15px;
    padding:10px 22px;
    background:linear-gradient(135deg,#8b5cf6,#c084fc);
    color:#140c24;
    text-decoration:none;
    border-radius:30px;
    font-weight:600;
    transition:all .3s ease;
    box-shadow:0 5px 15px rgba(192,132,252,.4);
}

.btn:hover{
    transform:translateY(-4px);
    box-shadow:0 10px 25px rgba(192,132,252,.6);
}

@media (max-width:1024px){

    .projects-container,
    .certificate-container{
        grid-template-columns:repeat(2,1fr);
        gap:20px;
    }
}

@media (max-width:768px){

    section{
        padding:60px 20px;
    }

    h1{
        font-size:28px !important;
    }

    .projects-container,
    .certificate-container{
        grid-template-columns:1fr;
    }

    .certificate-card{
        width:100%;
        max-width:350px;
        margin:auto;
    }

    .skills span{
        font-size:14px;
        padding:8px 14px;
    }

    .btn{
        padding:8px 16px;
        font-size:14px;
    }
}

@media (max-width:480px){

    h1{
        font-size:22px !important;
    }
}

.hidden{
    opacity:0;
    transform:translateY(40px);
    transition:all .8s ease;
}

.show{
    opacity:1;
    transform:translateY(0);
}

footer{
    text-align:center;
    padding:30px;
    background:#1a1030;
}
</style>

<body>

<div class="blob blob1"></div>
<div class="blob blob2"></div>

<section class="hero">
    <div class="hero-container">

        <div class="hero-left">
            <img src="<?php echo htmlspecialchars($user['photo']); ?>" class="hero-pic">
        </div>

        <div class="hero-right">
            <h1 class="hea"><?php echo htmlspecialchars($user['name']); ?></h1>
            <p class="about"><?php echo nl2br(htmlspecialchars($user['about'])); ?></p>

            <div class="hero-buttons">
                <?php if(!empty($user['resume'])): ?>
                    <a href="<?php echo htmlspecialchars($user['resume']); ?>" download>Resume</a>
                <?php endif; ?>

                <?php if(!empty($user['linkedin'])): ?>
                    <a href="<?php echo htmlspecialchars($user['linkedin']); ?>" target="_blank" rel="noopener noreferrer">LinkedIn</a>
                <?php endif; ?>

                <?php if(!empty($user['github'])): ?>
                    <a href="<?php echo htmlspecialchars($user['github']); ?>" target="_blank" rel="noopener noreferrer">GitHub</a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>

<div class="container">

<section>
<h1>🎓 Education</h1>
<?php foreach($education as $e): ?>
    <div class="card">
        <h3><?php echo htmlspecialchars($e['degree']); ?></h3>
        <p><?php echo htmlspecialchars($e['institute']); ?></p>
        <span><?php echo htmlspecialchars($e['duration']); ?> | <?php echo htmlspecialchars($e['score']); ?></span>
    </div>
<?php endforeach; ?>
</section>

<section>
<h1>
   <span class="emoji">💻</span>
   <span class="heading-text">SKILLS</span>
</h1>
<div class="skills">
<?php foreach($skills as $s): ?>
    <span><?php echo htmlspecialchars($s); ?></span>
<?php endforeach; ?>
</div>
</section>

<section>
<h1>
   <span class="emoji">🚀</span>
   <span class="heading-text">PROJECTS</span>
</h1>
<?php foreach($projects as $p): ?>
    <div class="card">
        <h3><?php echo htmlspecialchars($p['title']); ?></h3><br>
        <p><?php echo nl2br(htmlspecialchars($p['description'])); ?></p><br>
        <small><?php echo htmlspecialchars($p['tech_stack']); ?></small><br>
        <?php if(!empty($p['project_link'])): ?>
            <a href="<?php echo htmlspecialchars($p['project_link']); ?>" target="_blank" rel="noopener noreferrer" class="btn">View Project</a>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
</section>

<section>
<h1>
   <span class="emoji">📈</span>
   <span class="heading-text">EXPERIENCES</span>
</h1>
<?php foreach($experience as $ex): ?>
    <div class="card">
        <h3><?php echo htmlspecialchars($ex['role']); ?> - <?php echo htmlspecialchars($ex['company']); ?></h3><br>
        Duration: <span><?php echo htmlspecialchars($ex['duration']); ?></span><br><br>
        <p><?php echo nl2br(htmlspecialchars($ex['description'])); ?></p>
    </div>
<?php endforeach; ?>
</section>

<section>
<h1>
   <span class="emoji">📜</span>
   <span class="heading-text">Certificates</span>
</h1>
<div class="certificate-grid">

<?php foreach($certificates as $c): ?>
    <?php if(!empty($c['certificate_file'])): ?>
        <div class="certificate-card">
            <img src="<?php echo htmlspecialchars($c['certificate_file']); ?>" alt="">
            <div class="certificate-title">
                <?php echo htmlspecialchars($c['certificate_name']); ?>
            </div>
        </div>
    <?php endif; ?>

<?php endforeach; ?>

</div>
</section>

<section>
<h1>
   <span class="emoji">🏆</span>
   <span class="heading-text">ACHIEVEMENTS</span>
</h1>
<div class="achievement-grid">
<?php foreach($achievements as $a): ?>
    <div class="achievement-card">
        <h3><?php echo htmlspecialchars($a['title']); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($a['description'])); ?></p>
    </div>
<?php endforeach; ?>
</div>
</section>

</div>

<footer>
© <?php echo date("Y"); ?> <?php echo htmlspecialchars($user['name']); ?>
</footer>

<script>
const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if(entry.isIntersecting){
            entry.target.classList.add('show');
        }
    });
});

document.querySelectorAll('section, .card, .certificate-card, .achievement-card')
.forEach(el => {
    el.classList.add('hidden');
    observer.observe(el);
});
</script>

</body>
</html>

<?php if(isset($_GET['preview'])): ?>
    <div style="text-align:center; padding:20px; background:#000;">
        <a href="save_template.php?pid=<?php echo urlencode($portfolio_id); ?>&template=temp-3"
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