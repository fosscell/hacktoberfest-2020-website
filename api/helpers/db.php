<?php

function connect($servername, $username, $password, $dbname) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        ret(500, "Database connection failed");
    }

    return $conn;
}