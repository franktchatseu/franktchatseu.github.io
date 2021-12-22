<?php
header('Access-Control-Allow-Origin: *');
// Database Configuration details below.
$dbOptions = array(
    'db_host' => 'localhost',
    'db_user' => 'root',
    'db_pass' => '',
    'db_name' => 'marketing'
);

// Database Config End
error_reporting(E_ALL ^ E_NOTICE);

require "src/DB.php";
require 'PHPMailer/PHPMailerAutoload.php';
require 'src/EMAIL.php';

try {
    DB::init($dbOptions);
    EMAIL::create_tables();
    $response = array();

    $inputName = utf8_decode(urldecode($_POST['inputName']));

    $reciepientName = utf8_decode(urldecode($_POST['inputName']));
    $inputEmail = utf8_decode(urldecode($_POST['inputEmail']));
    $inputMessage = utf8_decode(urldecode($_POST['inputMessage']));

    $toAddress = "contact@yellowbird.mobi";
    $subjectToYellowbird = "Website | " . $inputName;
    $subject = "YellowBIRD";

    $body = 'Hello ' . $inputName . ',
    
    Thank you for contacting YellowBIRD, your message has been recieved and we shall get in tourch with you soon.
    
    Thank you.';

    $AltBody = 'Hello ' . $inputName . ',
    
    Thank you for contacting YellowBIRD, your message has been recieved and we shall get in tourch with you soon.
    
    Thank you.';

    EMAIL::SaveMessagesFromWebsite($inputEmail, $reciepientName, $inputMessage);
    echo EMAIL::SendEmail($inputEmail, $reciepientName, $subject, $body, $AltBody);
} catch (Exception $e) {
    die(json_encode(array('error' => $e->getMessage())));
}
