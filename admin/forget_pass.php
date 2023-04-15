<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '/Xampp/htdocs/furniture-shop-main/vendor/autoload.php';

include("include/header.php");
include('include/dbcon.php');


function reset_link($get_email,$token)
{
    $mail = new PHPMailer(true);


                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->Username   = 'pw9766539@gmail.com';                     //SMTP username
                        $mail->Password   = 'cscdyvzsjdjpfjqz';                               //SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                        //Recipients
                        $mail->setFrom('pw9766539@gmail.com');
                        $mail->addAddress($get_email);

                        //Content
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = 'no reply';
                        $mail->Subject = "Email Verification from Souza Furniture Mart";
                    
                    
                    $email_template = "
                    <h2>You have Registered with Souza Furniture Mart</h2>
                    <h5>Verify your email address to reset your password with the below given link</h5>
                    <br/><br/>
                    
                    <a href='http://localhost/furniture-shop-main/admin/password-change.php?token=$token&email=$get_email'> Click Me </a>
                    ";
                    
                    $mail->Body = $email_template;
                    $mail->send();
    
}

if(isset($_POST['submit']))
{
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $token = md5(rand());
    $check_email = "SELECT email FROM admin WHERE email= '$email' LIMIT 1";
    $check_email_run = mysqli_query($con, $check_email);
    if(mysqli_num_rows($check_email_run) > 0)
    {
        $row= mysqli_fetch_array($check_email_run);
         $get_email = $row['email'];
    
        $update_token = "UPDATE admin SET verify_token= '$token' WHERE email='$get_email' LIMIT 1";
        $update_token_run=mysqli_query($con, $update_token); 
        if($update_token_run)
        {
            reset_link($get_email,$token);
            $_SESSION['status']="We have sent password reset link to your email";
            header("Location: forget_pass.php");
            exit(0);

        }else{
            $_SESSION['status']="Something went wrong";
            header("Location: forget_pass.php");
            exit(0);
        }
    }else{
        $_SESSION['status']="No Email Found";
        header("Location: forget_pass.php");
        exit(0);
        
    }

}






if(isset($_POST['password_update']))
{
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $new_password = mysqli_real_escape_string($con, md5($_POST['new_password']));
    $confirm_password = mysqli_real_escape_string($con, md5($_POST['confirm_password']));
    $token = mysqli_real_escape_string($con, $_POST['password_token']);
    if(!empty($token))
    {
        if(!empty($email) && !empty($new_password) && !empty($confirm_password))
        {
            //Checking Token is Valid or not
            $check_token = "SELECT verify_token FROM admin WHERE verify_token='$token' LIMIT 1";
            $check_token_run = mysqli_query($con, $check_token);
            if(mysqli_num_rows($check_token_run) > 0)
            {
                if($new_password == $confirm_password)
                {
                    $update_password = "UPDATE admin SET password='$new_password' WHERE verify_token='$token' LIMIT 1";
                    $update_password_run = mysqli_query($con, $update_password);
                    if($update_password_run)
                    {
                        $new_token = md5(rand())."Souza"; 
                        $update_new_token = "UPDATE admin SET verify_token='$new_token' WHERE verify_token='$token' LIMIT 1";
                        $update_new_token_run = mysqli_query($con, $update_new_token);

                        $_SESSION['status'] = "Password Updated Successfully";
                        header("Location: signin.php");
                        exit(0);
                    }else{
                        $_SESSION['status'] = "Error In Updating Password";
                        header("Location: password-change.php?token=$token&email=$email");
                        exit(0);
                    }
                
                }else
                {
                $_SESSION['status'] = "Password and Confirm Password does not match";
                header("Location: password-change.php?token=$token&email=$email");
                exit(0);
            
                }
            }else
                {
                $_SESSION['status'] = "Invalid Token";
                header("Location: password-change.php?token=$token&email=$email");
                exit(0);
                }
        }else{
            $_SESSION['status'] = "All Fields Are Mandatory";
            header("Location: password-change.php?token=$token&email=$email");
            exit(0);
        }
    }
    else
    {
        $_SESSION['status'] = "No Token Available";
        header("Location: forget_pass.php");
        exit(0);
    }
}







?>
<div class="py-5">
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
        <?php
                if(isset($_SESSION['status']))
                {
            ?>
                <div class="alert alert-success">
                <h5><?= $_SESSION['status']; ?></h5>
                </div>
            <?php
                unset($_SESSION['status']);
                }
            ?>
            <div class="card">
                <div class="card-header">
                    <h5>Reset Password</h5>
                </div>
                <div class="card-body p-4">
                    <form action="forget_pass.php" method="POST">
                        <div class="form-group mb-3">
                            <label> Email Address</label>
                            <input type="text" name="email" class="form-control" placeholder="Enter Email Address">
                        </div>
                        <div class="form-group mb-3">
                            <button type="submit" name="submit" class="btn btn-primary">Send Passsword Reset Link</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>


