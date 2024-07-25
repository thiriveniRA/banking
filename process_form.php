<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "banking";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["id_proof"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["id_proof"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
if ($_FILES["id_proof"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
} else {
    if (move_uploaded_file($_FILES["id_proof"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars(basename($_FILES["id_proof"]["name"])) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
$stmt = $conn->prepare("INSERT INTO accounts (account_holder_name, account_number, account_type, bank_name, branch_name, id_proof, occupation, income_details, transaction_history, balance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssd", $account_holder_name, $account_number, $account_type, $bank_name, $branch_name, $id_proof, $occupation, $income_details, $transaction_history, $balance);
$account_holder_name = $_POST['account_holder_name'];
$account_number = $_POST['account_number'];
$account_type = $_POST['account_type'];
$bank_name = $_POST['bank_name'];
$branch_name = $_POST['branch_name'];
$id_proof = $target_file;
$occupation = $_POST['occupation'];
$income_details = $_POST['income_details'];
$transaction_history = $_POST['transaction_history'];
$balance = $_POST['balance'];
if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>
