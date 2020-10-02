<?php

function ret($status, $message) {
    http_response_code($status);

    if (is_array(($message))) {
        echo json_encode($message);
    } else {
        echo json_encode(["message" => $message]);
    }
    exit;
}
