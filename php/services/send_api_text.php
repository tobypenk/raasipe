<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require '../../twilio/autoload.php';
    use Twilio\Rest\Client;
    
    include "text_credentials.php";
    
    $message = $_POST["message"];
    $recipient_number = $_POST["pn"];

    $client = new Client($account_sid, $auth_token);
    $client->messages->create(
        $recipient_number,
        array(
            'from' => $twilio_number,
            'body' => $message
        )
    );
    echo "<p>Text sent!</p>";
    
?>