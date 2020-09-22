<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("helpers/loadall.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = connect($db_host, $db_user, $db_password, $db_name);

    $post = [];
    if (($raw_post = file_get_contents("php://input")) !== "" && !($post = json_decode($raw_post, JSON_UNESCAPED_SLASHES))) {
        ret(404, "Invalid JSON Given");
    }

    $name = $post["name"];
    $rollNo = $post["rollNo"];
    $email = $post["email"];
    $phone = $post["phone"];
    $projects = $post["projects"];

    if (empty($name) || !preg_match('/[a-zA-Z. ]/', $name) || strlen($name) > 255) {
        ret(400, "Name is empty / invalid");
    }
    if (empty($rollNo) || !preg_match('/[BMPbmp][01][0-9]{5}[A-Za-z]{2,3}/', $rollNo) || strlen($rollNo) > 15) {
        ret(400, "Roll no is empty / invalid");
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
        ret(400, "Email is empty / invalid");
    }
    if (empty($phone) || strlen($phone) < 10 || strlen($phone) > 15) {
        ret(400, "Phone is empty / invalid");
    }

    if (!empty($projects) && strlen($projects) > 1000) {
        ret(400, "Projects limit is 1000 characters");
    }

    // prepare and bind
    $stmt = $conn->prepare("INSERT INTO {$db_table_name} (name, email, phone, rollNo, projects) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $rollNo, $projects);
    $stmt->execute();

    $stmt->close();
    $conn->close();
    ret(200, "OK");
} else {
    ret(404, "Not found");
}