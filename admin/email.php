<?php 
//Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

require '/Xampp/htdocs/furniture-shop-main/vendor/autoload.php';

include('include/dbcon.php');

   
   function send_mail($cust_email,$order_invoice){
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
		   $mail->addAddress($cust_email);
   
		   //Content
		   $mail->isHTML(true);                                  //Set email format to HTML
		   $mail->Subject = 'no reply';
		   $mail->Subject = "Souza Furniture Mart";
	   
	   
	   $email_template = "
   <h1>	
   Souza Furniture Mart
   </h1>
   <h2>
   Woo hoo! Your order is on its way. Your order details can be found below.
<br>
<br>
   To Know about your Order details 
   <a href='http://localhost/furniture-shop-main/admin/invoice.php?invoice=$order_invoice'> Click Me </a>
                    

   
   
	   ";
	   
	   $mail->Body = $email_template;
	   $mail->send();
	   
   }

   $order_query = "SELECT * FROM customer_order WHERE cust_id=(SELECT LAST_INSERT_ID())";
                                    $run = mysqli_query($con,$order_query);
                        if($run){
                                    $order_row = mysqli_fetch_array($run);
                                    $order_id      = $order_row['order_id'];
                                            
                                            $order_invoice = $order_row['invoice_no'];
                                            $cust_email    = $order_row['customer_email'];
                                            
                                            send_mail($cust_email,$order_invoice);
                                            $message = "Email has been successfully sent.. Please check your email";
                        
                                            if($message){
                                                        $_SESSION['messag'] = $message;
                                                        header('Location:verified_furniture_pro.php');
                                            }
                                        }
                                        
                                            ?>
                                