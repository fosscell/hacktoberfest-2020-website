<?php

function ret($status, $message) {
    http_response_code($status);
    echo json_encode(["message" => $message]);
    exit;
}
