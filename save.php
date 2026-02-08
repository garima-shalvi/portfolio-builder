<?php
require_once "db.php";


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid access");
}


$folders = ['uploads/photos/', 'uploads/resumes/', 'uploads/certificates/'];
foreach ($folders as $f) {
    if (!is_dir($f)) mkdir($f, 0777, true);
}


$name        = $_POST['name'] ?? '';
$email       = $_POST['email'] ?? '';
$phone       = $_POST['phone'] ?? '';
$address     = $_POST['address'] ?? '';
$institution = $_POST['inst'] ?? '';
$linkedin    = $_POST['lik'] ?? '';
$github      = $_POST['gith'] ?? '';
$about       = $_POST['about'] ?? '';


$photoPath = $resumePath = '';

if(!empty($_FILES['pic']['name'])) {
    $originalName = pathinfo($_FILES['pic']['name'], PATHINFO_FILENAME);
    $ext = pathinfo($_FILES['pic']['name'], PATHINFO_EXTENSION);
    $safeName = preg_replace("/[^a-zA-Z0-9_-]/", "_", $originalName); 
    $photoPath = "uploads/photos/" . time() . "_" . $safeName . "." . $ext;

    move_uploaded_file($_FILES['pic']['tmp_name'], $photoPath);
}



if(!empty($_FILES['res']['name'])) {
    $resumePath = "uploads/resumes/" . time() . "_" . basename($_FILES['res']['name']);
    move_uploaded_file($_FILES['res']['tmp_name'], $resumePath);
}


$stmt = $conn->prepare("INSERT INTO users 
    (name,email,phone,address,institution,linkedin,github,about,photo,resume) 
    VALUES (?,?,?,?,?,?,?,?,?,?)");

$stmt->bind_param(
    "ssssssssss",
    $name, $email, $phone, $address, $institution,
    $linkedin, $github, $about, $photoPath, $resumePath
);

$stmt->execute();
$user_id = $stmt->insert_id;
$stmt->close();


if(!empty($_POST['skills'])) {
    $stmt = $conn->prepare("INSERT INTO skills(user_id, skill_name) VALUES (?,?)");
    foreach($_POST['skills'] as $skill){
        $stmt->bind_param("is", $user_id, $skill);
        $stmt->execute();
    }
    $stmt->close();
}


if(!empty($_POST['degree'])){
    $stmt = $conn->prepare("INSERT INTO education(user_id, degree, institute, duration, score) VALUES (?,?,?,?,?)");
    for($i=0; $i<count($_POST['degree']); $i++){
        $stmt->bind_param(
            "issss",
            $user_id,
            $_POST['degree'][$i],
            $_POST['edu_institute'][$i],
            $_POST['edu_year'][$i],
            $_POST['edu_score'][$i]
        );
        $stmt->execute();
    }
    $stmt->close();
}


if(!empty($_POST['project_title'])){
    $stmt = $conn->prepare("INSERT INTO projects(user_id,title,description,tech_stack,project_link) VALUES (?,?,?,?,?)");
    for($i=0; $i<count($_POST['project_title']); $i++){
        $stmt->bind_param(
            "issss",
            $user_id,
            $_POST['project_title'][$i],
            $_POST['project_desc'][$i],
            $_POST['tech_stack'][$i],
            $_POST['project_link'][$i]
        );
        $stmt->execute();
    }
    $stmt->close();
}


if(!empty($_POST['company'])){
    $stmt = $conn->prepare("INSERT INTO experience(user_id,company,role,duration,description) VALUES (?,?,?,?,?)");
    for($i=0; $i<count($_POST['company']); $i++){
        $stmt->bind_param(
            "issss",
            $user_id,
            $_POST['company'][$i],
            $_POST['role'][$i],
            $_POST['duration'][$i],
            $_POST['exp_desc'][$i]
        );
        $stmt->execute();
    }
    $stmt->close();
}


if(isset($_POST['cert_name'])){
    $stmt = $conn->prepare("INSERT INTO certificates(user_id,certificate_name,certificate_file) VALUES (?,?,?)");
    for($i=0; $i<count($_POST['cert_name']); $i++){
        $certFilePath = '';
        if(isset($_FILES['cert_file']['name'][$i]) && $_FILES['cert_file']['error'][$i] == 0){
            $certFileName = time().'_'.$i.'_'.basename($_FILES['cert_file']['name'][$i]);
            $certFilePath = 'uploads/certificates/'.$certFileName;
            move_uploaded_file($_FILES['cert_file']['tmp_name'][$i], $certFilePath);
        }

        
        if(!empty($_POST['cert_name'][$i])){
            $stmt->bind_param("iss", $user_id, $_POST['cert_name'][$i], $certFilePath);
            $stmt->execute();
        }
    }
    $stmt->close();
}


if(!empty($_POST['ach_title'])){
    $stmt = $conn->prepare("INSERT INTO achievements(user_id,title,description) VALUES (?,?,?)");
    for($i=0; $i<count($_POST['ach_title']); $i++){
        $stmt->bind_param("iss",$user_id,$_POST['ach_title'][$i],$_POST['ach_desc'][$i]);
        $stmt->execute();
    }
    $stmt->close();
}


header("Location: choose_template.php?user_id=".$user_id);
exit();
?>
