<?php 
  /*  $msg='';
    $msgClass='';
    if(filter_has_var(INPUT_POST,'submit')){
        $name=$_POST['name'];
        $email=$_POST['email'];
        $message=$_POST['message'];

        if(!empty($email) && !empty($name) && !empty($message)){
            //passed
            echo 'PASSED';
        }else{
            //failed
            $msg='Please fill in all the fields!';
            $msgClass='alert-danger';
        }
    }
    */
?>

<div class="contact">
    <?php require_once __DIR__ . '/../shared/navbar.php'; ?>

    <main class="main-content">
        <div class="container">
            <?php //if($msg !=''): ?>
                <div class ="alert <?php// echo $msgClass; ?>"> <?php //echo $msg; ?> </div>
            <?php //endif; ?>
            <h2>Contact Us</h2>
            <form method="post" action="/contact">
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
