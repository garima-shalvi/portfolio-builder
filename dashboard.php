<?php
session_start();

if (!isset($_SESSION['auth_id'])) {
    header("Location: login.php");
    exit();
}

include "db.php";

$user_id = $_SESSION['auth_id'];


$stmt = $conn->prepare(
    "SELECT id, slug, template_name, created_at
     FROM portfolios
     WHERE user_id = ?
     ORDER BY id DESC
     LIMIT 1"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$portfolio = $result->fetch_assoc();

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard | CareerCanvas</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: "Segoe UI", Arial, sans-serif;
    background: linear-gradient(135deg, #e8f9fb, #f4f7fb, #dff6ff);
    min-height: 100vh;
    color: #023047;
}




.navbar {
    background: linear-gradient(135deg, #052a62, #0077b6);
    color: white;
    padding: 18px 6%;
    display: flex;
    justify-content: space-between;
    align-items: center;

    box-shadow: 0 8px 25px rgba(0, 119, 182, 0.25);
}

.navbar h2 {
    margin: 0;
    font-size: 25px;
    letter-spacing: 0.5px;
}

.logout {
    color: white;
    text-decoration: none;
    font-size: 16px;
    font-weight: 600;
    padding: 9px 18px;
    border-radius: 25px;
    transition: 0.3s ease;
}

.logout:hover {
    background: rgba(255,255,255,0.15);
    transform: translateY(-2px);
}




.container {
    width: 85%;
    max-width: 1050px;
    margin: 60px auto;
}




.welcome {
    margin-bottom: 35px;
    animation: fadeIn 0.8s ease;
}

.welcome h1 {
    margin: 0 0 10px;
    font-size: 38px;
    color: #052a62;
}

.welcome h1 span {
    color: #00a2b8;
}

.welcome p {
    margin: 0;
    font-size: 17px;
    color: #4c6475;
}




.portfolio-card {
    background: rgba(255,255,255,0.95);
    padding: 35px;
    border-radius: 22px;

    box-shadow:
        0 20px 50px rgba(6, 101, 133, 0.15);

    border: 1px solid rgba(144, 219, 244, 0.5);

    animation: fadeIn 0.9s ease;

    transition: 0.3s ease;
}

.portfolio-card:hover {
    transform: translateY(-4px);

    box-shadow:
        0 25px 60px rgba(6, 101, 133, 0.22);
}

.portfolio-card h2 {
    margin-top: 0;
    margin-bottom: 25px;
    color: #052a62;
    font-size: 28px;
}




.portfolio-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 25px;
}

.info-item {
    background: #f2fbfd;
    padding: 15px 18px;
    border-radius: 12px;
    border-left: 4px solid #00b4d8;
}

.info-item strong {
    display: block;
    color: #052a62;
    margin-bottom: 5px;
}

.info-item span {
    color: #4c6475;
}




.url-title {
    font-weight: 700;
    color: #052a62;
    margin-bottom: 10px;
}

.url-box {
    display: flex;
    gap: 12px;
    margin-top: 5px;
}

.url-box input {
    flex: 1;
    padding: 14px 16px;

    border: 1px solid #bde0fe;
    border-radius: 12px;

    font-size: 14px;
    color: #34495e;
    background: #f8fdff;

    min-width: 0;
}

.url-box input:focus {
    outline: none;
    border-color: #00a2b8;
    box-shadow: 0 0 0 3px rgba(0, 180, 216, 0.15);
}




button,
.btn {
    padding: 13px 22px;
    border: none;
    border-radius: 30px;

    cursor: pointer;
    text-decoration: none;

    font-size: 15px;
    font-weight: 600;

    transition: 0.3s ease;
}




.copy-btn {
    background: linear-gradient(135deg, #00a2b8, #00b4d8);
    color: white;
    white-space: nowrap;
}

.copy-btn:hover {
    transform: translateY(-2px);

    box-shadow:
        0 8px 20px rgba(0, 180, 216, 0.35);
}




.view-btn {
    display: inline-block;
    margin-top: 25px;

    background: linear-gradient(135deg, #052a62, #0077b6);
    color: white;
}

.view-btn:hover {
    transform: translateY(-3px);

    box-shadow:
        0 10px 25px rgba(0, 119, 182, 0.35);
}




.create-btn {
    display: inline-block;
    margin-top: 20px;

    background: linear-gradient(135deg, #0077b6, #00b4d8);
    color: white;
}

.create-btn:hover {
    transform: translateY(-3px);

    box-shadow:
        0 10px 25px rgba(0, 180, 216, 0.35);
}


/

.success {
    color: #008f9c;
    font-weight: 600;
    margin-top: 12px;

    display: none;

    animation: fadeIn 0.3s ease;
}




.empty-card {
    text-align: center;
    padding: 55px 30px;
}

.empty-icon {
    font-size: 50px;
    margin-bottom: 15px;
}

.empty-card h2 {
    margin-bottom: 10px;
}

.empty-card p {
    color: #647b89;
}




@keyframes fadeIn {

    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }

}




@media (max-width: 700px) {

    .navbar {
        padding: 16px 25px;
    }

    .container {
        width: 90%;
        margin: 40px auto;
    }

    .welcome h1 {
        font-size: 30px;
    }

    .portfolio-card {
        padding: 25px;
    }

    .portfolio-info {
        grid-template-columns: 1fr;
    }

    .url-box {
        flex-direction: column;
    }

    .copy-btn {
        width: 100%;
    }

}

</style>

</head>

<body>




<div class="navbar">

    <h2>CareerCanvas</h2>

    <a href="logout.php" class="logout">
        Logout
    </a>

</div>




<div class="container">


    <div class="welcome">

        <h1>
            Welcome
            <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            🎉
        </h1>

        <p>
            Manage your portfolio from here.
        </p>

    </div>


    <?php if ($portfolio): ?>

        <?php

        $portfolio_url =
            "http://localhost/Portfolio%20Builder/p.php?slug="
            . urlencode($portfolio['slug']);

        ?>


        

        <div class="portfolio-card">

            <h2>
                ✨ Your Portfolio
            </h2>


            <div class="portfolio-info">

                <div class="info-item">

                    <strong>Template</strong>

                    <span>
                        <?php
                        echo htmlspecialchars($portfolio['template_name']);
                        ?>
                    </span>

                </div>


                <div class="info-item">

                    <strong>Created</strong>

                    <span>
                        <?php
                        echo htmlspecialchars($portfolio['created_at']);
                        ?>
                    </span>

                </div>

            </div>


            <div class="url-title">
                Your Portfolio URL
            </div>


            <div class="url-box">

                <input
                    type="text"
                    id="portfolioUrl"
                    value="<?php echo htmlspecialchars($portfolio_url); ?>"
                    readonly
                >


                <button
                    class="copy-btn"
                    onclick="copyLink()"
                >
                    📋 Copy Link
                </button>

            </div>


            <p
                id="copyMessage"
                class="success"
            >
                ✓ Link copied successfully!
            </p>


            <a
                href="p.php?slug=<?php echo urlencode($portfolio['slug']); ?>"
                target="_blank"
                class="btn view-btn"
            >
                👁 View Portfolio
            </a>

        </div>


    <?php else: ?>


        

        <div class="portfolio-card empty-card">

            <div class="empty-icon">
                ✨
            </div>

            <h2>
                No Portfolio Yet
            </h2>

            <p>
                You haven't created your portfolio yet.
                Let's build something amazing!
            </p>

            <a
                href="create_portfolio.php"
                class="btn create-btn"
            >
                🚀 Create Portfolio
            </a>

        </div>


    <?php endif; ?>


</div>


<script>

function copyLink() {

    const url =
        document.getElementById("portfolioUrl").value;

    navigator.clipboard.writeText(url)

        .then(function() {

            const message =
                document.getElementById("copyMessage");

            message.style.display = "block";

            setTimeout(function() {

                message.style.display = "none";

            }, 2000);

        })

        .catch(function() {

            alert("Unable to copy the link.");

        });

}

</script>


</body>

</html>