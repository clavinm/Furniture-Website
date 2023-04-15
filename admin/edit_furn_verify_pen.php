<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '/Xampp/htdocs/furniture-shop-main/vendor/autoload.php';


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
  
  if($_SESSION['pay'] == "Debit / Credit card"){
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
<br>
<br>
<h2>
    Please pay for your order by using this link 
    <a href='http://localhost/furniture-shop-main/payment/index.php?invoice=$order_invoice'> Click Me </a>
     
    ";
    $mail->Body = $email_template;
     }

  
  $mail->Body = $email_template;
$mail->send();

}


 require_once('include/header.php');
 
require_once "../loading/load.php";
 if(!isset($_SESSION['email'])){
  header('location: signin.php');
}
if(isset($_SESSION['email'])){
    $session_id = $_SESSION['id'];
    $session_email = $_SESSION['email'];
    $session_name = $_SESSION['name'];
}
?>

<div class="container-fluid mt-2">
    <script src="ckeditor/ckeditor.js"></script>
      <div class="row">
        <div class="col-md-3 col-lg-3">
            <?php require_once('include/sidebar.php'); ?>
        </div>
        
        <div class="col-md-9 col-lg-9">
          <form method="post" enctype="multipart/form-data">
             <?php
                    if(isset($_GET['order_id'])){
                      $fur_order_id = $_GET['order_id'];

                      $order_query = "SELECT * FROM customer_order WHERE order_id=$fur_order_id";
                      $run = mysqli_query($con,$order_query);
          
                      if(mysqli_num_rows($run) > 0){
                              $order_row = mysqli_fetch_array($run);
                              $order_invoice = $order_row['invoice_no'];
                              $order_id      = $order_row['order_id'];
                              $cust_id       = $order_row['customer_id'];
                              $order_pro_id  = $order_row['product_id'];
                              $order_qty     = $order_row['products_qty'];
                              $order_amount  = $order_row['product_amount'];
                              $order_date    = $order_row['order_date'];
                              $order_status  = $order_row['order_status'];
                              $_SESSION['pay'] = $order_row['paymentMethod'];
                              
                              $que="SELECT * from customer WHERE cust_id=$cust_id";
                              $ru=mysqli_query($con,$que);
                              $ro=mysqli_fetch_array($ru);
                                $cust_email    = $ro['cust_email'];
                              
                              $pr_query = "SELECT * FROM furniture_product fp INNER JOIN categories cat ON fp.category = cat.id WHERE pid = $order_pro_id ";
                              $pr_run   = mysqli_query($con,$pr_query);
                                  
                                  if(mysqli_num_rows($pr_run) > 0){
                                      $pr_row = mysqli_fetch_array($pr_run);
                                      $pid   = $pr_row['pid'];
                                      $image = $pr_row['image'];
                                      $category = $pr_row['category'];


                                if(isset($_POST['update'])){ 
                                  $status     = $_POST['status'];
                                                                  
                                  if(!empty($status)){
                                    $query = "UPDATE customer_order SET order_status='$status' WHERE invoice_no=$order_invoice"; 
                                      
                                    if(mysqli_query($con,$query)){
                                           if($status=='pending'){
                                              header("location:pending_furniture_pro.php");
                                           }else if($status=='verified'){
                                            header("location:verified_furniture_pro.php");
                                            $que = mysqli_query($con, "SELECT invoice_no=$order_invoice AS invoice_no FROM customer_order WHERE order_status='verified' GROUP BY invoice_no");    
                                    if($que){
                                      send_mail($cust_email,$order_invoice);
                                      }
                                          }else if($status=='delivered'){
                                          header("location:delivered_furniture_pro.php");
                                       } 
                                          }
                                      }
                                  }
                                  
              if(isset($msg)){
                    echo "<span class='mt-3 mb-5' style='color:green; font-weight:bold;'><i style='color:green; font-weight:bold;' class='fas fa-smile'></i> $msg</span>";
                    }?>
            <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                     <label for="furniture">#Invoice No:</label>
                      <input type="text" class="form-control" value="<?php echo $order_invoice;?>" disabled>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                     <label for="furniture">Order ID:</label>
                      <input type="text" class="form-control" value="<?php echo $order_id ;?>" disabled>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                     <label for="furniture">Product ID:</label>
                      <input type="text" class="form-control" value="<?php echo $order_pro_id;?>" disabled>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                     <label for="furniture">Product Category:</label>
                      <input type="text" class="form-control" value="<?php echo  $category;?>" disabled>
                  </div>
                </div>
                  
            </div>
                  
              <div class="row">
                    <div class="col-md-3">
                      <label for="category">Customer ID:</label>
                      <input type="text" class="form-control" value="<?php echo $cust_id ?>" disabled>
                      
                    </div>
                    <!-- Grid column -->
                    <div class="col-md-3">
                      <div class="form-group">
                      <label for="size">Customer Email:</label>
                      <input type="text" class="form-control" value="<?php echo $cust_email?>" disabled>
                      </div>
                    </div>
                    <!-- Grid column -->
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="size">Product Price:</label>
                        <input type="text" class="form-control" value="<?php echo $order_amount?>" disabled>
                      </div>
                    </div>

                    <div class="col-md-3">
                     <label for="size">Product Quantity:</label>
                      <input type="disabled" class="form-control" value="<?php echo $order_qty ;?>" disabled>
                    </div>
                    <div class="col-md-3">
                  <div class="form-group">
                     <label for="furniture">Payment Method:</label>
                      <input type="text" class="form-control" value="<?php echo $_SESSION['pay'];?>" disabled>
                  </div>
                </div>
                    
              </div> 
                       
                  
              <div class="row mt-3">

                <div class="col-md-6">      
                  <span>Choose files</span>
                    <select class="form-control" name="status">
                    <?php if($order_status == 'pending'){
                      echo "<option value='pending'  selected >Pending</option>";
                      echo "<option value='verified'>Verified</option>";
                      
                    } else if($order_status == 'verified'){
                      echo "<option value='verified'  selected >Verified</option>";
                      echo "<option value='pending'>Pending</option>";
                      echo "<option value='delivered'>Delivered</option>";
                    } 
                    
                    
                    ?>
                        
                    </select>
                </div>

                <div class="col-md-6">
                  <img src="img/<?php echo $image;?>" min-width="100%"  height="200px">
                </div>
              </div>
                 <?php  
                        
                      }
                    } 
                  ?>
              <input type="submit" name="update" class=" mt-3 btn btn-primary btn-md" value="Update">
              <?php
                }
                ?>
            </form>
        </div>
        
     </div>
        

      <?php 
 require_once('include/footer.php');
?>