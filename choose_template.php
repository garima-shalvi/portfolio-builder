<?php
$user_id = $_GET['user_id'] ?? null;
if (!$user_id) die("User not found");
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
  margin-bottom: 3rem;
  font-size: 2.5rem;
}

/* template grid */
.template-grid {
  display: flex;
  gap: 2.5rem;
  flex-wrap: wrap;
  justify-content: center;
}

/* template card */
.template-card {
  width: 300px;
  background: rgba(255, 255, 255, 0.12);
  border-radius: 18px;
  padding: 1rem;
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

/* preview image */
.template-card img {
  width: 100%;
  border-radius: 12px;
  margin-bottom: 1rem;
}

/* template title */
.template-card h3 {
  color: #fff;
  font-size: 1.3rem;
  margin: 0.5rem 0 1rem;
}

/* select button */
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
  <h1>Choose Your Portfolio Template</h1>

  <div class="template-grid">

    <!-- TEMPLATE 1 -->
    <a href="portfolio.php?user_id=<?php echo $user_id; ?>" style="text-decoration:none;">
      <div class="template-card">
        <img src="images/template1-preview.png" alt="Template 1">
        <h3>Template 1</h3>
        <button>Select Template</button>
      </div>
    </a>

    <!-- TEMPLATE 2 -->
    <a href="my_template.php?user_id=<?php echo $user_id; ?>" style="text-decoration:none;">
      <div class="template-card">
        <img src="images/template2-preview.png" alt="Template 2">
        <h3>Template 2</h3>
        <button>Select Template</button>
      </div>
    </a>

  </div>
</div>

</body>
</html>
