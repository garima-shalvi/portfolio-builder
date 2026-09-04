<?php

session_start();
require_once "db.php";

$conn->begin_transaction();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid access");
    }

    if (!isset($_SESSION['auth_id'])) {
        throw new Exception("Not logged in");
    }

    $portfolio_id = $_POST['portfolio_id'] ?? null;

    if (!$portfolio_id || !ctype_digit((string)$portfolio_id)) {
        throw new Exception("Portfolio missing");
    }

    $portfolio_id = (int)$portfolio_id;
    $user_id = (int)$_SESSION['auth_id'];

    $owner = $conn->prepare("
        SELECT id
        FROM portfolios
        WHERE id=? AND user_id=?
    ");
    $owner->bind_param("ii", $portfolio_id, $user_id);
    $owner->execute();

    if (!$owner->get_result()->fetch_assoc()) {
        $owner->close();
        throw new Exception("Unauthorized");
    }

    $owner->close();

    $folders = [
        'uploads/photos/',
        'uploads/resumes/',
        'uploads/certificates/'
    ];

    foreach ($folders as $folder) {
        if (!is_dir($folder) && !mkdir($folder, 0755, true)) {
            throw new Exception("Unable to create upload directory");
        }
    }

    $name        = $_POST['name'] ?? '';
    $email       = $_POST['email'] ?? '';
    $phone       = $_POST['phone'] ?? '';
    $address     = $_POST['address'] ?? '';
    $institution = $_POST['inst'] ?? '';
    $linkedin    = $_POST['lik'] ?? '';
    $github      = $_POST['gith'] ?? '';
    $about       = $_POST['about'] ?? '';

    $photoPath = '';
    $resumePath = '';

    $maxPhotoSize = 5 * 1024 * 1024;
    $maxResumeSize = 10 * 1024 * 1024;
    $maxCertificateSize = 10 * 1024 * 1024;

    $finfo = new finfo(FILEINFO_MIME_TYPE);

    if (isset($_FILES['pic']) && $_FILES['pic']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['pic']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Profile picture upload failed");
        }

        if ($_FILES['pic']['size'] > $maxPhotoSize) {
            throw new Exception("Profile picture is too large. Maximum size is 5 MB");
        }

        $mime = $finfo->file($_FILES['pic']['tmp_name']);

        $allowedPhotos = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        ];

        if (!isset($allowedPhotos[$mime])) {
            throw new Exception("Invalid profile picture type. Please upload JPG, PNG, or WEBP");
        }

        $extension = $allowedPhotos[$mime];
        $fileName = bin2hex(random_bytes(16)) . '.' . $extension;
        $photoPath = 'uploads/photos/' . $fileName;

        if (!move_uploaded_file($_FILES['pic']['tmp_name'], $photoPath)) {
            throw new Exception("Unable to save profile picture");
        }
    }

    if (isset($_FILES['res']) && $_FILES['res']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['res']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Resume upload failed");
        }

        if ($_FILES['res']['size'] > $maxResumeSize) {
            throw new Exception("Resume is too large. Maximum size is 10 MB");
        }

        $mime = $finfo->file($_FILES['res']['tmp_name']);

        if ($mime !== 'application/pdf') {
            throw new Exception("Invalid resume type. Please upload a PDF file");
        }

        $fileName = bin2hex(random_bytes(16)) . '.pdf';
        $resumePath = 'uploads/resumes/' . $fileName;

        if (!move_uploaded_file($_FILES['res']['tmp_name'], $resumePath)) {
            throw new Exception("Unable to save resume");
        }
    }

    $check = $conn->prepare("
        SELECT id
        FROM portfolio_details
        WHERE portfolio_id=?
    ");
    $check->bind_param("i", $portfolio_id);
    $check->execute();
    $result = $check->get_result();

    $exists = $result->num_rows > 0;

    $check->close();

    if ($exists) {
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
            $photoPath,
            $photoPath,
            $resumePath,
            $resumePath,
            $portfolio_id
        );
    } else {
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

    if (!empty($_POST['skills'])) {
        $del = $conn->prepare("DELETE FROM skills WHERE portfolio_id=?");
        $del->bind_param("i", $portfolio_id);
        $del->execute();
        $del->close();

        $stmt = $conn->prepare("
            INSERT INTO skills(portfolio_id, skill_name)
            VALUES (?,?)
        ");

        foreach ($_POST['skills'] as $skill) {
            $stmt->bind_param("is", $portfolio_id, $skill);
            $stmt->execute();
        }

        $stmt->close();
    }

    if (!empty($_POST['degree'])) {
        $del = $conn->prepare("DELETE FROM education WHERE portfolio_id=?");
        $del->bind_param("i", $portfolio_id);
        $del->execute();
        $del->close();

        $stmt = $conn->prepare("
            INSERT INTO education
            (portfolio_id, degree, institute, duration, score)
            VALUES (?,?,?,?,?)
        ");

        for ($i = 0; $i < count($_POST['degree']); $i++) {
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

    if (!empty($_POST['project_title'])) {
        $del = $conn->prepare("DELETE FROM projects WHERE portfolio_id=?");
        $del->bind_param("i", $portfolio_id);
        $del->execute();
        $del->close();

        $stmt = $conn->prepare("
            INSERT INTO projects
            (portfolio_id,title,description,tech_stack,project_link)
            VALUES (?,?,?,?,?)
        ");

        for ($i = 0; $i < count($_POST['project_title']); $i++) {
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

    if (!empty($_POST['company'])) {
        $del = $conn->prepare("DELETE FROM experience WHERE portfolio_id=?");
        $del->bind_param("i", $portfolio_id);
        $del->execute();
        $del->close();

        $stmt = $conn->prepare("
            INSERT INTO experience
            (portfolio_id,company,role,duration,description)
            VALUES (?,?,?,?,?)
        ");

        for ($i = 0; $i < count($_POST['company']); $i++) {
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

    if (isset($_POST['cert_name'])) {
        $existingCertificates = [];

        $oldCerts = $conn->prepare("
            SELECT id, certificate_file
            FROM certificates
            WHERE portfolio_id=?
        ");
        $oldCerts->bind_param("i", $portfolio_id);
        $oldCerts->execute();
        $oldResult = $oldCerts->get_result();

        while ($row = $oldResult->fetch_assoc()) {
            $existingCertificates[(int)$row['id']] = $row['certificate_file'];
        }

        $oldCerts->close();

        $del = $conn->prepare("DELETE FROM certificates WHERE portfolio_id=?");
        $del->bind_param("i", $portfolio_id);
        $del->execute();
        $del->close();

        $stmt = $conn->prepare("
            INSERT INTO certificates
            (portfolio_id,certificate_name,certificate_file)
            VALUES (?,?,?)
        ");

        $certIds = $_POST['cert_id'] ?? [];

        for ($i = 0; $i < count($_POST['cert_name']); $i++) {
            $certName = $_POST['cert_name'][$i];
            $certFilePath = '';

            if (
                isset($certIds[$i]) &&
                ctype_digit((string)$certIds[$i])
            ) {
                $certId = (int)$certIds[$i];

                if (array_key_exists($certId, $existingCertificates)) {
                    $certFilePath = $existingCertificates[$certId];
                }
            }

            if (
                isset($_FILES['cert_file']['error'][$i]) &&
                $_FILES['cert_file']['error'][$i] !== UPLOAD_ERR_NO_FILE
            ) {
                if ($_FILES['cert_file']['error'][$i] !== UPLOAD_ERR_OK) {
                    throw new Exception("Certificate upload failed");
                }

                if ($_FILES['cert_file']['size'][$i] > $maxCertificateSize) {
                    throw new Exception("Certificate file is too large. Maximum size is 10 MB");
                }

                $tmpName = $_FILES['cert_file']['tmp_name'][$i];
                $mime = $finfo->file($tmpName);

                $allowedCertificates = [
                    'application/pdf' => 'pdf',
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png'
                ];

                if (!isset($allowedCertificates[$mime])) {
                    throw new Exception("Invalid certificate file type. Please upload PDF, JPG, or PNG");
                }

                $extension = $allowedCertificates[$mime];
                $fileName = bin2hex(random_bytes(16)) . '.' . $extension;
                $certFilePath = 'uploads/certificates/' . $fileName;

                if (!move_uploaded_file($tmpName, $certFilePath)) {
                    throw new Exception("Unable to save certificate");
                }
            }

            if (!empty($certName)) {
                $stmt->bind_param(
                    "iss",
                    $portfolio_id,
                    $certName,
                    $certFilePath
                );
                $stmt->execute();
            }
        }

        $stmt->close();
    }

    if (!empty($_POST['ach_title'])) {
        $del = $conn->prepare("DELETE FROM achievements WHERE portfolio_id=?");
        $del->bind_param("i", $portfolio_id);
        $del->execute();
        $del->close();

        $stmt = $conn->prepare("
            INSERT INTO achievements
            (portfolio_id,title,description)
            VALUES (?,?,?)
        ");

        for ($i = 0; $i < count($_POST['ach_title']); $i++) {
            $stmt->bind_param(
                "iss",
                $portfolio_id,
                $_POST['ach_title'][$i],
                $_POST['ach_desc'][$i]
            );
            $stmt->execute();
        }

        $stmt->close();
    }

    $conn->commit();

    header("Location: choose_template.php?pid=" . $portfolio_id);
    exit();

} catch (Exception $e) {
    $conn->rollback();

    if (isset($portfolio_id) && $portfolio_id) {
        $_SESSION['save_error'] = $e->getMessage();
        header("Location: abc.php?portfolio_id=" . $portfolio_id);
        exit();
    }

    die("Something went wrong: " . $e->getMessage());
}

?>