<?php
require_once("helpers/loadall.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $token = getallheaders()["Authorization"];
    if (!isset($token)) {
        ret(403, "Unauthorized");
    }

    $token = explode(" ", $token)[1];
    if (!isset($token) || $token !== $API_KEY) {
        ret(403, "Unauthorized");
    }

    $post = [];
    if (($raw_post = file_get_contents("php://input")) !== "" && !($post = json_decode($raw_post, JSON_UNESCAPED_SLASHES))) {
        var_dump($raw_post);
        ret(400, "Invalid JSON Given");
    }

    $isLive = $post["isLive"] === true ? "true" : "false";
    $conn = connect($db_host, $db_user, $db_password, $db_name);

    $stmt = $conn->prepare("UPDATE {$db_table_name["live"]} SET isLive = ?");
    $stmt->bind_param("s", $isLive);
    $stmt->execute();

    $stmt->close();
    $conn->close();
    ret(200, ["message" => "OK", "isLive" => $isLive === "true" ? true : false ]);

} else if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $conn = connect($db_host, $db_user, $db_password, $db_name);

    $result = $conn->query("SELECT isLive FROM {$db_table_name["live"]} LIMIT 1");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $isLive = $row["isLive"];
    } else {
        $isLive = false;
    }

    $conn->close();
    ret(200, [ "isLive" => $isLive === "true" ? true : false ]);
} else {
    ret(404, "Not found");
}
