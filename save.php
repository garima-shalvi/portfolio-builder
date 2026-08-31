<?php

session_start();
require_once "db.php";
$conn->begin_transaction();

try{
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception("Invalid access");
}

$portfolio_id = $_POST['portfolio_id'] ?? null;

if(!$portfolio_id){
    throw new Exception("Portfolio missing");
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

$check = $conn->prepare("
    SELECT id FROM portfolio_details WHERE portfolio_id = ?
");
$check->bind_param("i", $portfolio_id);
$check->execute();
$result = $check->get_result();

$exists = $result->num_rows > 0;

$check->close();

if($exists){

    
    $stmt = $conn->prepare("
        UPDATE portfolio_details SET
        name=?,
        email=?,
        phone=?,
        address=?,
        institution=?,
        linkedin=?,
        github=?,
        about=?,
        photo=IF(?='', photo, ?),
        resume=IF(?='', resume, ?)
        WHERE portfolio_id=?
    ");

    $stmt->bind_param(
        "ssssssssssssi",
        $name,
        $email,
        $phone,
        $address,
        $institution,
        $linkedin,
        $github,
        $about,
        $photoPath, $photoPath,
        $resumePath, $resumePath,
        $portfolio_id
    );

}else{

    
    $stmt = $conn->prepare("
        INSERT INTO portfolio_details
        (portfolio_id,name,email,phone,address,institution,linkedin,github,about,photo,resume)
        VALUES (?,?,?,?,?,?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "issssssssss",
        $portfolio_id,
        $name,
        $email,
        $phone,
        $address,
        $institution,
        $linkedin,
        $github,
        $about,
        $photoPath,
        $resumePath
    );
}

$stmt->execute();
$stmt->close();


if(!empty($_POST['skills'])) {
    $del = $conn->prepare("DELETE FROM skills WHERE portfolio_id=?");
    $del->bind_param("i",$portfolio_id);
    $del->execute();
    $del->close();
    $stmt = $conn->prepare("INSERT INTO skills(portfolio_id, skill_name) VALUES (?,?)");
    foreach($_POST['skills'] as $skill){
        $stmt->bind_param("is", $portfolio_id, $skill);
        $stmt->execute();
    }
    $stmt->close();
}


if(!empty($_POST['degree'])){
    $del = $conn->prepare("DELETE FROM education WHERE portfolio_id=?");
    $del->bind_param("i",$portfolio_id);
    $del->execute();
    $del->close();
    $stmt = $conn->prepare("INSERT INTO education(portfolio_id, degree, institute, duration, score) VALUES (?,?,?,?,?)");
    for($i=0; $i<count($_POST['degree']); $i++){
        $stmt->bind_param(
            "issss",
            $portfolio_id,
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
    $del = $conn->prepare("DELETE FROM projects WHERE portfolio_id=?");
    $del->bind_param("i",$portfolio_id);
    $del->execute();
    $del->close();
    $stmt = $conn->prepare("INSERT INTO projects(portfolio_id,title,description,tech_stack,project_link) VALUES (?,?,?,?,?)");
    for($i=0; $i<count($_POST['project_title']); $i++){
        $stmt->bind_param(
            "issss",
            $portfolio_id,
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
    $del = $conn->prepare("DELETE FROM experience WHERE portfolio_id=?");
    $del->bind_param("i",$portfolio_id);
    $del->execute();
    $del->close();
    $stmt = $conn->prepare("INSERT INTO experience(portfolio_id,company,role,duration,description) VALUES (?,?,?,?,?)");
    for($i=0; $i<count($_POST['company']); $i++){
        $stmt->bind_param(
            "issss",
            $portfolio_id,
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
    $del = $conn->prepare("DELETE FROM certificates WHERE portfolio_id=?");
    $del->bind_param("i",$portfolio_id);
    $del->execute();
    $del->close();
    $stmt = $conn->prepare("INSERT INTO certificates(portfolio_id,certificate_name,certificate_file) VALUES (?,?,?)");
    for($i=0; $i<count($_POST['cert_name']); $i++){
        $certFilePath = '';
        if(isset($_FILES['cert_file']['name'][$i]) && $_FILES['cert_file']['error'][$i] == 0){
            $certFileName = time().'_'.$i.'_'.basename($_FILES['cert_file']['name'][$i]);
            $certFilePath = 'uploads/certificates/'.$certFileName;
            move_uploaded_file($_FILES['cert_file']['tmp_name'][$i], $certFilePath);
        }

        
        if(!empty($_POST['cert_name'][$i])){
            $stmt->bind_param("iss", $portfolio_id, $_POST['cert_name'][$i], $certFilePath);
            $stmt->execute();
        }
    }
    $stmt->close();
}


if(!empty($_POST['ach_title'])){
    $del = $conn->prepare("DELETE FROM achievements WHERE portfolio_id=?");
    $del->bind_param("i",$portfolio_id);
    $del->execute();
    $del->close();
    $stmt = $conn->prepare("INSERT INTO achievements(portfolio_id,title,description) VALUES (?,?,?)");
    for($i=0; $i<count($_POST['ach_title']); $i++){
        $stmt->bind_param("iss",$portfolio_id,$_POST['ach_title'][$i],$_POST['ach_desc'][$i]);
        $stmt->execute();
    }
    $stmt->close();
}


$conn->commit();

header("Location: choose_template.php?pid=".$portfolio_id);
exit();
}
catch(Exception $e) {
   $conn->rollback();
   die("Something went wrong: " . $e->getMessage());
}

?>
