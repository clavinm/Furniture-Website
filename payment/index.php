<?php
session_start();
include('/Xampp/htdocs/furniture-shop-main/payment/config.php');
include '/Xampp/htdocs/furniture-shop-main/include/dbcon.php';

if(isset($_GET['invoice'])){
    $invoice_no = $_GET['invoice'];
    $que = mysqli_query($con, "SELECT * FROM customer_order WHERE invoice_no='$invoice_no'");
    $row = mysqli_fetch_array($que);
    $pay = $row['paymentMethod'];
    if($pay == 'Payment Successfull'){
        header('location: succes.php');

    }else{

    $query = "SELECT SUM(product_amount) AS product_amount FROM customer_order WHERE invoice_no='$invoice_no' GROUP BY invoice_no";
    $run = mysqli_query($con,$query);
    $row = mysqli_fetch_array($run);
    $product_amount = $row['product_amount'] + 50;  
    $_SESSION['amt'] = $product_amount;
    $_SESSION['invoice'] = $invoice_no;
    }
} 


?>
<form action="submit.php" method="POST" class= "form-group">
<center>
<br>
<br>
<div class="card">
                        <div class="card-header">
                        <h2>Please Click On The Below Link</h2>
                        </div>
                           
<script
                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                data-key="<?php echo $publishableKey?>"
                data-amount="<?php echo str_replace(",","",$_SESSION['amt'])* 100?>"
                data-name="Souza Furniture Mart"
                data-description="Online payment"
                data-image="https://cdn1.vectorstock.com/i/1000x1000/54/75/home-furniture-logo-vector-34005475.jpg"
                data-currency="inr"
                data-locale="auto">
                                </script>
                
</div>
</center>
</form>
