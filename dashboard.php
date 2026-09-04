<?php
session_start();

if (!isset($_SESSION['auth_id'])) {
    header("Location: login.php");
    exit();
}

include "db.php";

$user_id = $_SESSION['auth_id'];
$template_names = [
    "portfolio" => "Template 1",
    "my_template" => "Template 2",
    "temp-3" => "Template 3",
    "temp-4" => "Template 4"
];
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
    font-family: "Montserrat", sans-serif;
}

body {
    margin: 0;
    min-height: 100vh;
    color: #023047;
    background: linear-gradient(165deg, #2699E6, #1a66cc, #0d33b3, #000099);
    background-size: 300% 300%;
    animation: gradientMove 12s ease infinite;
}

.navbar {
    width: 100%;
    padding: 18px 6%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(0, 0, 80, 0.25);
    border-bottom: 1px solid rgba(0, 255, 255, 0.35);
    box-shadow: 0 5px 25px rgba(0, 0, 80, 0.25);
}

.navbar h2 {
    margin: 0;
    font-size: 25px;
    font-weight: 700;
    color: #00ffff;
    letter-spacing: 0.5px;
    text-shadow: 0 0 12px rgba(0, 255, 255, 0.35);
}

.logout {
    color: white;
    text-decoration: none;
    font-size: 14px;
    font-weight: 700;
    padding: 10px 20px;
    border: 1px solid rgba(255, 255, 255, 0.7);
    border-radius: 30px;
    transition: 0.3s ease;
}

.logout:hover {
    background: #00ffff;
    color: #003366;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 255, 255, 0.3);
}

.container {
    width: 85%;
    max-width: 1050px;
    margin: 55px auto;
}

.welcome {
    margin-bottom: 30px;
    animation: fadeIn 0.8s ease;
}

.welcome h1 {
    margin: 0 0 10px;
    font-size: 38px;
    color: white;
    font-weight: 700;
}

.welcome h1 span {
    color: #00ffff;
    text-shadow: 0 0 12px rgba(0, 255, 255, 0.3);
}

.welcome p {
    margin: 0;
    color: #bffcff;
    font-size: 16px;
}

.portfolio-card {
    background: rgba(255, 255, 255, 0.97);
    padding: 35px;
    border-radius: 22px;
    border: 1px solid rgba(0, 255, 255, 0.45);
    box-shadow: 0 20px 50px rgba(0, 30, 100, 0.3);
    animation: fadeIn 0.9s ease;
    transition: 0.3s ease;
}

.portfolio-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 25px 60px rgba(0, 255, 255, 0.2);
}

.portfolio-card h2 {
    margin-top: 0;
    margin-bottom: 25px;
    color: #083188;
    font-size: 27px;
}

.portfolio-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 28px;
}

.info-item {
    background: #e8f9fb;
    padding: 16px 18px;
    border-radius: 12px;
    border-left: 4px solid #00b4d8;
}

.info-item strong {
    display: block;
    color: #083188;
    margin-bottom: 6px;
    font-size: 14px;
}

.info-item span {
    color: #31556b;
    font-size: 14px;
}

.url-title {
    font-weight: 700;
    color: #083188;
    margin-bottom: 10px;
}

.url-box {
    display: flex;
    gap: 12px;
    margin-top: 5px;
}

.url-box input {
    flex: 1;
    min-width: 0;
    padding: 14px 16px;
    border: 1px solid #bde0fe;
    border-radius: 12px;
    font-size: 14px;
    color: #34495e;
    background: #f8fdff;
}

.url-box input:focus {
    outline: none;
    border-color: #00b4d8;
    box-shadow: 0 0 0 3px rgba(0, 180, 216, 0.15);
}

button,
.btn {
    display: inline-block;
    padding: 12px 22px;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    text-decoration: none;
    font-size: 14px;
    font-weight: 700;
    transition: 0.3s ease;
}

.copy-btn {
    background: linear-gradient(135deg, #0077b6, #00b4d8);
    color: white;
    white-space: nowrap;
}

.copy-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 180, 216, 0.4);
}

.view-btn {
    margin-top: 25px;
    background: linear-gradient(135deg, #083188, #0077b6);
    color: white;
}

.view-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 119, 182, 0.4);
}

.edit-btn {
    margin-top: 25px;
    margin-left: 8px;
    background: linear-gradient(135deg, #0077b6, #00b4d8);
    color: white;
}

.edit-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 180, 216, 0.4);
}

.ai-btn {
    margin-top: 25px;
    margin-left: 8px;
    background: linear-gradient(135deg, #1a66cc, #2699E6);
    color: white;
}

.ai-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(38, 153, 230, 0.45);
}

.create-btn {
    margin-top: 20px;
    background: #00ffff;
    color: #003366;
    border: 2px solid white;
}

.create-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 255, 255, 0.4);
}

.success {
    color: #008f9c;
    font-weight: 700;
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

@keyframes gradientMove {
    0% {
        background-position: 0% 50%;
    }

    50% {
        background-position: 100% 50%;
    }

    100% {
        background-position: 0% 50%;
    }
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

    .view-btn,
    .edit-btn,
    .ai-btn {
        width: 100%;
        margin-left: 0;
        text-align: center;
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
                        <?php echo htmlspecialchars($template_names[$portfolio['template_name']] ?? "Unknown"); ?>

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
            <a
             href="abc.php?portfolio_id=<?php echo $portfolio['id']; ?>"
              class="btn edit-btn"
               >
              ✏️ Edit Portfolio
             </a>
             <a href="ai_review.html" class="btn ai-btn">🤖 AI Career Review</a>

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