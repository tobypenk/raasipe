<?php
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    $recipient = isset($_SESSION["email"]) ? $_SESSION["email"] : (isset($_POST["recipient"]) ? $_POST["recipient"] : "");
    $user_name = $_POST["user_name"];
    $html_message = $_POST["html_message"];
    $plain_message = $_POST["plain_message"];
    $subject = $_POST["subject"];
    
    include "email_credentials.php";

    if ($recipient == "") {
        echo "<p>You'll need to create an account and set your email to use this feature.</p>";

    } else {
        require '../../mailer/vendor/autoload.php';
        
        $mail = new PHPMailer(true); 
        try {
            $mail->SMTPDebug = 2; 
            $mail->isSMTP();
            $mail->Host = 'a2plcpnl0551.prod.iad2.secureserver.net';
            $mail->SMTPAuth = true;
            $mail->Username = $email_user;
            $mail->Password = $email_password; 
            $mail->SMTPSecure = 'tls'; 
            $mail->Port = 25; 

            $mail->setFrom($email_user, 'RaaSipe');
            $mail->addAddress($recipient, $user_name);     

            $mail->isHTML(true);                                  
            $mail->Subject = $subject;
            $mail->Body    = $html_message;
            $mail->AltBody = $plain_message;
        
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
        echo "<p>Email script executed</p>";
    }
?>




