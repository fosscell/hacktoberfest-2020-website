<?php

function send_mail($to_address, $subject, $name, $SENDGRID_API_KEY) {

    $url = "https://api.sendgrid.com/v3/mail/send";

      
    $to = [["name" => $name, "email" => $to_address]];
    $from =["name" => "FOSSCell NITC", "email" => "hacktoberfest@fosscell.org"];
    $content = [ 
        ["type" => "text/plain", "value" => get_mail_template($name, true)],
        ["type" => "text/html", "value" => get_mail_template($name, false)]
    ];

    $data = [
        "personalizations" => [["to" => $to, "subject" => $subject]],
        "from" => $from, 
        "content" => $content
    ];
    
    $ch = curl_init($url);
    # Setup request to send json via POST.
    $payload = json_encode($data);
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer {$SENDGRID_API_KEY}"));
    # Return response instead of printing.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    # Send request.
    $result = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (curl_error($ch) || $httpcode !== 202) {
        throw new Exception("Couldn't send mail");
    }
    curl_close($ch);
}

function get_mail_template($name, $onlyText = false) {
    $message = "Hi %name%,\nYou're now registered for Hacktoberfest NITC\n\nComplete registration for the event at MLH: https://organize.mlh.io/participants/events/4608-hacktoberfest-nit-calicut. \nJoin the community Discord server: https://discord.gg/Ys4z5SF\n";
    if (!$onlyText) {
        $message = file_get_contents(__DIR__ . "/email/template-1.html");
    }

    return str_replace("%name%", $name, $message);
}