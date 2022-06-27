<?php
session_start();
    include('/Xampp/htdocs/furniture-shop-main/payment/config.php');
    include '/Xampp/htdocs/furniture-shop-main/include/dbcon.php';
     $amount = $_SESSION['amt'];
     $invoice_no = $_SESSION['invoice'];
   
    

    $token = $_POST["stripeToken"];
    $token_card_type = $_POST["stripeTokenType"];
    $charge = \Stripe\PaymentIntent::create([
      "amount" => str_replace(",","",$amount) * 100,
      "currency" => 'inr',
      "description"=>"Online payment",
      "card"=> [ 'token' => $request -> $token,]

    ]);

    if($charge){
      header("Location:success.php?invoice=$invoice_no");
    }
?>