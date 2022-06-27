<?php
    require_once "/Xampp/htdocs/furniture-shop-main/stripe-php-master/init.php";

    
    $secretKey ="sk_test_51LEqJiSHZm0Z7Mqa3aCd0DMqjOwh0Rt47ayZXYpik0h7w3rNRSRA5tKgVzYKqtLfxADs7qn7ZOKJ8056bviZRJ7U00SzepFs7k";
    $publishableKey ="pk_test_51LEqJiSHZm0Z7MqaceLxtID3bJcDO7WS738m5NspoIIgqxtyPsSVHwiEBnJraQWpi0igAPvOs6mGBxSJcymClx9200D8g7qLyh";
    

    \Stripe\Stripe::setApiKey($secretKey);
?>