<?php 
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\SMTP;
 use PHPMailer\PHPMailer\Exception;
require '/Xampp/htdocs/furniture-shop-main/vendor/autoload.php';

ob_start();
session_start();
include('include/header.php');

$invoice_no = $_SESSION['order_invoice'];

function send_mail($customer_email,$invoice_no){
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
    $mail->addAddress($customer_email);

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
<a href='http://localhost/furniture-shop-main/admin/invoice.php?invoice=$invoice_no'> Click Me </a>
                 



  ";
  
  $mail->Body = $email_template;
  $mail->send();
  
}


?>
        
        <div class="jumbotron">
            <h2 class="text-center mt-5">Checkout</h2>
        </div>
        

    <div class="container">
 <?php
   if(isset($_SESSION['id'])){
      $customer_id    = $_SESSION['id'];
      $customer_email = $_SESSION['remail']; 
      $customer_name  = $_SESSION['name'];
      $customer_number=   $_SESSION['number'];

      $sub_total=0;
      $shipping_cost = 0;
      $total = 0;

      if(isset($_POST['checkout'])){
        $fullname = $_POST['fullname'];
        $address  = $_POST['address'];
        $city     = $_POST['city'];
        $code     = $_POST['code'];
        $number   = $_POST['phone_number'];
        $invoice  = mt_rand();
        $date     = date("d-m-Y"); 
        

        $cartt = "SELECT * FROM cart WHERE cust_id='$customer_id'";
        $run  = mysqli_query($con,$cartt);
        if(mysqli_num_rows($run) > 0){
          while($row = mysqli_fetch_array($run) ){
            $db_pro_id  = $row['product_id'];
            $db_pro_qty  = $row['quantity'];

          $pr_query  = "SELECT * FROM furniture_product WHERE pid=$db_pro_id";
          $pr_run    = mysqli_query($con,$pr_query);
          if(mysqli_num_rows($pr_run) > 0){
            while($pr_row = mysqli_fetch_array($pr_run)){
              
              $price = $pr_row['price'];
              $arrPrice = array($pr_row['price']);   

              $single_pro_total_price = $db_pro_qty * $price;
                
              $checkout_query  = "INSERT INTO `customer_order`(`customer_id`, `customer_email`,
              `customer_fullname`, `customer_address`, `customer_city`, `customer_pcode`, `customer_phonenumber`,
              `product_id`, `product_amount`, `invoice_no`, `products_qty`, `order_date`, `order_status`)
              VALUES('$customer_id','$customer_email','$fullname','$address','$city','$code','$number',$db_pro_id,
              $single_pro_total_price,'$invoice',$db_pro_qty,'$date','pending')";
              
               
                  if(mysqli_query($con,$checkout_query)){
                        $del_query = "DELETE FROM cart where cust_id = $customer_id";
                        if(mysqli_query($con,$del_query)){
                             
              send_mail($customer_email,$invoice_no);
              
              header('Location: customer/orders.php');
            
                         $_SESSION['message'] ="<div class='alert alert-primary alert-dismissible fade show pt-1 pb-1 pl-3' role='alert'>
                          <strong><i class='fas fa-check-circle'></i> Thanks! </strong>for your order,It will be deliver within 7 working days.
                          <button type='button' class='close p-2' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                          </button></div>";
                        }
                        
                      }
                    }
                  }
                }
            }
    
      }
  
           
     
                  

            ?>
                  <h1>Check Out</h1>
                  <div class="row">
                      <!--shopping cart-->
                    <div class="col-md-6 p-3">
                    <h5>Shipping Detail</h5><hr>
                   
                    <div class="form-group">
                      <label for="email"><b>Email:</b></label>
                      <label><b><?php echo $customer_email;?></b></label>
                    </div>

                   <form method="post"  class="mt-4">
                      <div class="form-group">
                        <label for="fullname">Fullname:</label>
                        <input type="text" name="fullname" placeholder="Full Name" class="form-control" value="<?php echo $customer_name; ?>" required>
                      </div>

                        <div class="form-group">
                          <label for="address">Address:</label>
                          <input type="text" name="address" placeholder="Address"  class="form-control" >
                      </div>
                      
                      <div class="row">
                        <div class="col-md-6 col-6">
                            <div class="form-group">
                            <label for="city">City:</label>
                              <input type="text" name="city" placeholder="City" class="form-control" required >
                          </div>
                        </div>
                        
                        <div class="col-md-6 col-6">
                          <div class="form-group">
                          <label for="postalcode">Postal code:</label>
                            <input type="number" name="code" placeholder="Postal code" class="form-control" required >
                        </div>
                        </div>

                      </div>

                      <div class="form-group">
                        <label for="number">Number:</label>
                        <input type="number" name="phone_number" placeholder="Phone Number" class="form-control" value="<?php echo $customer_number; ?>" required>
                      </div>

                      <div class="form-group text-center mt-4">
                          <input type="submit" name="checkout" class="btn btn-primary btn-block p-2" value="Place Order" id="border-less">
                      </div>
                  </form>
                 </div>
                <!--end cart--->
                
                <!--shopping Detail-->
               <div class="col-md-6 p-3">
                 <!-- cart-->
                 <table class="table table-responsive table-hover ">
                 <h5>Order Detail</h5><hr>
                      
                  <tbody>
                 <?php
                  $cart = "SELECT * FROM cart WHERE cust_id='$customer_id'";
                  $run  = mysqli_query($con,$cart);
                   if(mysqli_num_rows($run) > 0){
                      while($cart_row = mysqli_fetch_array($run)){
                          $db_cust_id = $cart_row['cust_id'];
                          $db_pro_id  = $cart_row['product_id'];
                          $db_pro_qty  = $cart_row['quantity'];

                       $pr_query  = "SELECT * FROM furniture_product WHERE pid=$db_pro_id";
                       $pr_run    = mysqli_query($con,$pr_query);
                                       
                        if(mysqli_num_rows($pr_run) > 0){
                         while($pr_row = mysqli_fetch_array($pr_run)){
                              $pid = $pr_row['pid'];
                              $title = $pr_row['title'];
                              $price = $pr_row['price'];
                              $arrPrice = array($pr_row['price']);    
                              $size = $pr_row['size'];
                              $img1 = $pr_row['image'];
                                             
                              $single_pro_total_price = $db_pro_qty * $price;
                              $pro_total_price = array($db_pro_qty * $price);  
                              $each_pr = implode($pro_total_price);
                                           //   $values = array_sum($arrPrice);
                                 $shipping_cost=0;
                                 $values = array_sum($pro_total_price);
                                 $sub_total +=$values;
                                 $total = $sub_total + $shipping_cost;


  
                                              
                            ?> 
                <div class="row">
                   <!--Image-->
                    <div class="col-md-3 col-3">
                         <img src="img/<?php echo $img1;?>" width="100%">
                     </div>
                     <!--end image-->
                     
                      <!-- Title-->
                    <div class="col-md-5 col-5">
                        <h5><?php echo $title;?> </h5>
                        <p> Dimension:<?php echo $size;?></p>
                     </div>
                        <!--end title-->
                        
                         <!--qunatity-->
                        <div class="col-md-2 col-1">
                            <h5>x <?php echo $db_pro_qty;?></h5>
                        </div>
                         <!--end qty-->
                          <!--price-->
                        <div class="col-md-2 col-2">
                            <h5>
                            <?php echo $single_pro_total_price;?> 
                            </h5>
                        </div>
                         <!--end price-->
                         
                </div><hr>
                             
                           <?php  
                              
                                     }
                                    }    
                                  }
                                 }
                    ?>
                              
                          
                      </tbody>
                
                  </table>
                <!--end cart-->

             
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-6">
                        <h6>Subtotal</h6>
                        <h6>Shipping</h6>
                        <h5 class="font-weight-bold">Total</h5>
                        
                    </div>
                    <div class="col-md-6 col-sm-6 col-6">
                        <h6 class="text-right font-weight-normal">Rs <?php echo $sub_total;?></h6>
                        <h6 class="text-right font-weight-normal">Rs <?php echo $shipping_cost;?></h6>
                        <h5 class="text-right font-weight-bold">Rs <?php echo $total;?></h5>
                    </div>
                </div>
                
              </div>
                <!--end order--->
          </div>
               
    <?php     
  }
 ?>
        
    </div>
        <?php include('include/footer.php');?>