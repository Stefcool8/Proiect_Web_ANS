<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';
    $mail = new PHPMailer();
    
    
    //function function_alert($message) { 
    // Display the alert box 
   
    //echo "<script> alert('$message');</script>";
    //}
    $success='';
    $msg='';
    $msgClass='';
    if(filter_has_var(INPUT_POST,'submit')){
        
        $name=htmlspecialchars($_POST['name']);
        $email=htmlspecialchars($_POST['email']);
        $message=htmlspecialchars($_POST['message']);
        $subject=htmlspecialchars($_POST['subject']);

        if(!empty($email) && !empty($name) && !empty($subject) && !empty($message)){
            //passed
            //function_alert('Your message has been successfuly sent:)');
            if($name == 'Aser'){
                $success='success';
                $msg='Success!';
                if(filter_var($email,FILTER_VALIDATE_EMAIL) === false){
                    $success='';
                    $msg='Please use a valid email address!';
                }
                else{
                    $toEmail =  'aser.wnw@gmail.com';
                    $subject = 'Contact Request From'.$name;
                    $body = '<h2> Contact Request </h2>
                            <h4> Name: </h4> <p>'.$name.'</p>
                            <h4> Email address: </h4> <p>'.$email.'</p>
                            <h4> Subject: </h4> <p>'.$subject.'</p>
                            <h4> Message: </h4> <p>'.$message.'</p>
                            ';
                    
                    $headers = "MIME-Version: 1.0"."\r\n";
                    $headers .="Content-Type:text/html;charset=UTF-8". "\r\n";
                    $headers .="From: ".$name."<".$email.">"."\r\n";
                    
                    $mail->From = $email;
                    $mail->FromName = $name;
                    $mail->addAddress($toEmail, 'User');     // Add a recipient
                    $mail->addAddress('ellen@example.com');               // Name is optional
                    $mail->addReplyTo('info@example.com', 'Information');
                    $mail->addCC('cc@example.com');
                    $mail->addBCC('bcc@example.com');

                    $mail->addAttachment('');         // Add attachments
                    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                    $mail->isHTML(true);                                  // Set email format to HTML

                    $mail->Subject = $subject;
                    $mail->Body    = $body;
                    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';


                    /*if(mail($toEmail, $subject, $body, $headers)){
                        //mail successfuly sent
                        $msg='Your message has been successfully sent!';
                    }else{
                        // something bad happen
                        $success='';
                        $msg='Something went wrong. We could nou sent your message!';
                    }*/
                    if(!$mail->send()) {
                        $success='';
                        $msg = 'Message could not be sent.';
                        echo 'Mailer Error: ' . $mail->ErrorInfo;
                    } else {
                        $msg = 'Message has been sent';
                    }

                }

            }
            else{
                $msg='Wrong name. Unsened messaage!';
            }
            
        }else{
            //failed
            $msg='Please fill in all the fields!';
            //$msgClass='alert-danger';
        }
    }
    
?>

<div class="contact">
    <?php require_once __DIR__ . '/../shared/navbar.php'; ?>

    <main class="main-content">
        <div class="container">
            
            <h2>Contact Us</h2>
            <form method="post" action="/contact">
            <?php if(filter_has_var(INPUT_POST,'submit')) :?>
                    <?php if($success !='success'): ?>
                        <div style="color:white; background-color:#FF3633; text-align: center"> <?php echo $msg; ?> </div>
                    <?php else : ?>
                        <div style="color:white; background-color:#33FF9C; text-align: center;"> <?php echo $msg; ?> </div>
                    <?php endif; ?>
            <?php endif; ?>
                <div class="input-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required>
                </div>
                <div class="input-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" id = "submit" name ="submit">Send Message</button>
            </form>
        </div>
    </main>
    <?php require_once __DIR__ . '/../shared/footer.php'; ?>
</div>
