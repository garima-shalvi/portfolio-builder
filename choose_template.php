<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['auth_id'])) {
    header("Location: login.php");
    exit();
}

$portfolio_id = $_GET['pid'] ?? null;
if (!$portfolio_id || !ctype_digit((string)$portfolio_id)) die("Portfolio not found");
$portfolio_id = (int)$portfolio_id;

$check = $conn->prepare("SELECT id FROM portfolios WHERE id=? AND user_id=?");
$check->bind_param("ii", $portfolio_id, $_SESSION['auth_id']);
$check->execute();

if (!$check->get_result()->fetch_assoc()) {
    http_response_code(403);
    die("Unauthorized");
}

$check->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Choose Your Portfolio Template</title>

<style>
body {
  font-family: "Poppins", Arial, sans-serif;
  min-height: 100vh;
  margin: 0;
  background: linear-gradient(135deg, #91cd81, #e29161);
  display: flex;
  align-items: center;
  justify-content: center;
  
}

.container {
  text-align: center;
}

h1 {
  color: #fff;
  margin-bottom: 2rem;
  font-size: 2.5rem;
}


.template-grid {
  display: grid;
  grid-template-columns: repeat(2, 320px);
  gap: 2.5rem;
  justify-content: center;
  align-content: center;
}
@media (max-width: 750px) {
  .template-grid {
    grid-template-columns: 1fr;
  }
}

.template-card {
  width: 300px;
  background: rgba(255, 255, 255, 0.12);
  border-radius: 18px;
  padding: 0.9rem;
  backdrop-filter: blur(10px);
  box-shadow:
    0 15px 35px rgba(0,0,0,0.25),
    0 0 25px rgba(255,255,255,0.15);
  transition: 0.35s ease;
  cursor: pointer;
}

.template-card:hover {
  transform: translateY(-10px) scale(1.03);
  box-shadow:
    0 25px 50px rgba(0,0,0,0.35),
    0 0 35px rgba(255,255,255,0.35);
}


.template-card img {
  width: 100%;
  border-radius: 12px;
  margin-bottom: 1rem;
}


.template-card h3 {
  color: #fff;
  font-size: 1.3rem;
  margin: 0.5rem 0 1rem;
}


.template-card button {
  padding: 12px 22px;
  border: none;
  border-radius: 30px;
  font-size: 15px;
  cursor: pointer;
  background: linear-gradient(135deg, #ffd700, #ffae00);
  color: #000;
  font-weight: 600;
  transition: 0.3s;
}

.template-card button:hover {
  box-shadow: 0 0 18px rgba(255, 215, 0, 0.8);
}
</style>
</head>

<body>

<div class="container">
  <a href="dashboard.php" style="display:inline-block; margin-bottom:20px; text-decoration:none; color:#fff; opacity:0.85;">← Back to Dashboard</a>
  <h1>Choose Your Portfolio Template</h1>

  <div class="template-grid">

    
    <a href="portfolio.php?pid=<?php echo htmlspecialchars((string)$portfolio_id); ?>&preview=1" style="text-decoration:none;">
      <div class="template-card">
        <img src="images/template1-preview.png" alt="Template 1">
        <h3>Template 1</h3>
        <button>Select Template</button>
      </div>
    </a>

    
    <a href="my_template.php?pid=<?php echo htmlspecialchars((string)$portfolio_id); ?>&preview=2" style="text-decoration:none;">
      <div class="template-card">
        <img src="images/template2-preview.png" alt="Template 2">
        <h3>Template 2</h3>
        <button>Select Template</button>
      </div>
    </a>

    <a href="temp-3.php?pid=<?php echo htmlspecialchars((string)$portfolio_id); ?>&preview=3" style="text-decoration:none;">
      <div class="template-card">
        <img src="images/template3-preview.png" alt="Template 3">
        <h3>Template 3</h3>
        <button>Select Template</button>
      </div>
    </a>

    <a href="temp-4.php?pid=<?php echo htmlspecialchars((string)$portfolio_id); ?>&preview=4" style="text-decoration:none;">
      <div class="template-card">
        <img src="images/template4-preview.png" alt="Template 4">
        <h3>Template 4</h3>
        <button>Select Template</button>
      </div>
    </a>

  </div>
</div>

</body>
</html>