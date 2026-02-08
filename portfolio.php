<?php
require_once "db.php";

$user_id = $_GET['user_id'] ?? 1;
if (!$user_id) die("User not found");


$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();


$education = [];
$res = $conn->query("SELECT * FROM education WHERE user_id=$user_id");
while ($row = $res->fetch_assoc()) $education[] = $row;


$skills = [];
$res = $conn->query("SELECT skill_name FROM skills WHERE user_id=$user_id");
while ($row = $res->fetch_assoc()) $skills[] = $row['skill_name'];


$projects = [];
$res = $conn->query("SELECT * FROM projects WHERE user_id=$user_id");
while ($row = $res->fetch_assoc()) $projects[] = $row;


$experience = [];
$res = $conn->query("SELECT * FROM experience WHERE user_id=$user_id");
while ($row = $res->fetch_assoc()) $experience[] = $row;


$certificates = [];
$res = $conn->query("SELECT * FROM certificates WHERE user_id=$user_id");
while ($row = $res->fetch_assoc()) $certificates[] = $row;

$achievements = [];
$res = $conn->query("SELECT * FROM achievements WHERE user_id=$user_id");
while ($row = $res->fetch_assoc()) $achievements[] = $row;

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($user['name']); ?> | Portfolio</title>

<style>
:root{
  --bg:#020c10;
  --card:#0c2226;
  --accent:#39e6d6;
  --muted:#8fd8d2;
  --text:#eaffff;
  --shadow:rgba(57,230,214,0.4);
}

*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',sans-serif;}
body{
  background:radial-gradient(circle at top,#06373d,#01090c);
  color:var(--text);
  scroll-behavior:smooth;
}


.container{
  width:90%;
  max-width:1200px;
  margin:auto;
  padding-bottom:50px;
}


.hero{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:50px;
  padding:80px 0;
  align-items:center;
  animation: fadeIn 1s ease;
}
.photo{
  width:260px;
  height:260px;
  object-fit:cover;
  border-radius:12px;
  border:3px solid var(--accent);
  box-shadow:0 0 30px var(--shadow);
  transition:.4s;
}
.photo:hover{
  transform:scale(1.1);
}


.float{
  display:inline-block;
  animation: float 4s ease-in-out infinite alternate;
}
.buttons a{
  animation: float 1s ease-in-out infinite alternate;
}


.badge{
  display:inline-block;
  padding:8px 18px;
  border-radius:20px;
  background:rgba(57,230,214,.15);
  color:var(--accent);
  margin-bottom:20px;
}


.hero h1{
  font-size:48px;
}
.hero h1 span{color:var(--accent);}
.personal p{
  color:var(--muted);
  margin-top:6px;
}
.buttons{
  margin-top:25px;
  display:flex;
  gap:15px;
  flex-wrap:wrap;
}
.btn{
  padding:12px 26px;
  border-radius:30px;
  text-decoration:none;
  font-weight:600;
  transition:.3s;
  background:var(--accent);
  color:#021416;
}
.btn.outline{
  background:none;
  border:2px solid var(--accent);
  color:var(--accent);
}
.btn:hover{transform:translateY(-2px) scale(1.05);}


section{margin-top:90px; animation: fadeIn 5s ease;}
section h1{
  text-align:center;
  color:var(--accent);
  margin-bottom:40px;
}


.cards{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
  gap:25px;
}
.card{
  background:var(--card);
  padding:22px;
  border-radius:16px;
  box-shadow:0px 0px 3px 3px rgba(139, 247, 236, 0.91);
  border:1px solid rgba(11, 243, 220, 0.89);
  transition:.3s;
}
.card:hover{
  transform:scale(1.05);
  box-shadow:0 0 20px var(--shadow);
}


.skills-container{
  overflow:hidden;
  white-space:nowrap;
  padding:10px 0;
  margin:0 auto;
}
.skills-track{
  display:inline-block;
  animation: scrollSkills 2s linear infinite;
}
.skill{
    box-shadow:0px 0px 3px 3px rgba(139, 247, 236, 0.91);
  display:inline-block;
  margin:0 15px;
  padding:8px 14px;
  border-radius:20px;
  background:rgba(57,230,214,.2);
  font-weight:600;
  transition:1.7s;
}
.skill:hover{
transform:scale(1.2);
}

.certs{
  display:grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap:10px;              /* IMPORTANT: space for hover */
  justify-items:center;
  align-items:start;
  padding:20px 0;
}

.cert{
  width:300px;
  height:250px;
  text-align:center;
  border-radius:12px;
  border:2px solid var(--accent);
  padding:10px;
  background:var(--card);
  transition:transform 0.4s ease;
  animation: float 3s ease-in-out infinite alternate;
  transform-origin:center;
}

.cert img{
  width:100%;
  height:200px;
  object-fit:cover;
  border-radius:10px;
  display:block;
  transition:1s;
}

.cert:hover img{
  transform:scale(1.8);
  z-index:10;
}
.cert-name{
  margin-top:8px;
  font-size:14px;
  color:var(--muted);
  text-align:center;
  word-break:break-word;
}


@keyframes scrollSkills{
  0%{transform:translateX(0);}
  100%{transform:translateX(-50%);}
}
@keyframes float{
  0%{transform:translateY(0);}
  100%{transform:translateY(-10px);}
}
@keyframes fadeIn{
  0%{opacity:0; transform:translateY(20px);}
  100%{opacity:1; transform:translateY(0);}
}


footer{
  text-align:center;
  padding:60px 0;
  color:var(--muted);
}
</style>
</head>
<body>

<div class="container">


<div class="hero">
  <div>
    <div class="badge float">✨ Available for hire</div>
    <h1>Hello, I'm <span><?php echo htmlspecialchars($user['name']); ?></span></h1>
    <div class="personal">
      <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
      <p><strong>Phone:</strong> <?php echo $user['phone']; ?></p>
      <p><strong>Address:</strong> <?php echo $user['address']; ?></p>
    </div>
    <p style="margin-top:20px;color:var(--muted);"><?php echo $user['about']; ?></p>
    <div class="buttons">
      <!-- <a href="mailto:<?php echo $user['email']; ?>" class="btn float">Contact Me</a> -->
      <a href="<?php echo $user['resume']; ?>" download class="btn outline float">🧑‍💼Resume</a>
      <?php if(!empty($user['linkedin'])): ?>
        <a href="<?php echo $user['linkedin']; ?>" target="_blank" class="btn outline float">🔗LinkedIn💼</a>
      <?php endif; ?>
      <?php if(!empty($user['github'])): ?>
        <a href="<?php echo $user['github']; ?>" target="_blank" class="btn outline float">🐙GitHub💻</a>
      <?php endif; ?>
    </div>
  </div>
  <img src="<?php echo $user['photo']; ?>" class="photo">
</div>


<section>
<h1>🎓Education🏫</h1>
<div class="cards">
<?php foreach($education as $e): ?>
  <div class="card">
    <h2><?php echo $e['degree']; ?></h2><br>
    <p><?php echo $e['institute']; ?></p><br>
    <p><?php echo $e['duration']; ?></p><br>
    <p>Score: <?php echo $e['score']; ?></p><br>
  </div>
<?php endforeach; ?>
</div>
</section>


<section>
<h1>⚡Skills⚡</h1>
<div class="skills-container">
  <div class="skills-track">
    <?php foreach($skills as $s): ?>
      <span class="skill"><?php echo $s; ?></span>
    <?php endforeach; ?>
    <?php foreach($skills as $s): ?>
      <span class="skill"><?php echo $s; ?></span>
    <?php endforeach; ?>
  </div>
</div>
</section>


<section>
<h1>🚀Projects🧩</h1>
<div class="cards">
<?php foreach($projects as $p): ?>
  <div class="card">
    <h2><?php echo $p['title']; ?></h2><br>
    <p><?php echo $p['description']; ?></p><br>
    <p><strong>Tech:</strong> <?php echo $p['tech_stack']; ?></p><br>
    <?php if($p['project_link']): ?>
      <a href="<?php echo $p['project_link']; ?>" target="_blank" class="btn outline float">View Project</a>
    <?php endif; ?>
  </div>
<?php endforeach; ?>
</div>
</section>


<section>
<h1>📈Experience🏢</h1>
<div class="cards">
<?php foreach($experience as $ex): ?>
  <div class="card">
    <h2><?php echo $ex['role']; ?></h2><br>
    <p><?php echo $ex['company']; ?></p><br>
    <p><?php echo $ex['duration']; ?></p><br>
    <p><?php echo $ex['description']; ?></p><br>
  </div>
<?php endforeach; ?>
</div>
</section>


<section>
<h1>📜Certificates🏅</h1>
<div class="certs">
<?php foreach($certificates as $c): ?>
  <div class="cert">
    <img src="<?php echo $c['certificate_file']; ?>" title="<?php echo $c['certificate_name']; ?>">
    <div class="cert-name"><?php echo $c['certificate_name']; ?></div>
  </div>
<?php endforeach; ?>
</div>
</section>
<section>
<h1>🌟Achievements🏆</h1>
<div class="cards">
<?php foreach($achievements as $p): ?>
  <div class="card">
    <h2><?php echo $p['title']; ?></h2><br>
    <p><?php echo $p['description']; ?></p><br>
    <!-- <p><strong>Tech:</strong> <?php echo $p['tech_stack']; ?></p><br>
    <?php if($p['project_link']): ?>
      <a href="<?php echo $p['project_link']; ?>" target="_blank" class="btn outline float">View Project</a>
    <?php endif; ?> -->
  </div>
<?php endforeach; ?>
</div>
</section>
</div>

<footer>
© <?php echo date("Y"); ?> <?php echo $user['name']; ?>
</footer>

</body>
</html>
