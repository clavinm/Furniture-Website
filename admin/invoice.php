<?php
require('/Xampp/htdocs/furniture-shop-main/admin/fpdf/fpdf.php');
include('/Xampp/htdocs/furniture-shop-main/admin/include/dbcon.php');

$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();

if(isset($_GET['invoice'])){
   $invoice_no = $_GET['invoice'];
//set font to arial, bold, 14pt
$pdf->SetFont('Arial','B',14);

$pdf->Cell(130	,5,'Souza Furniture Mart',0,0);
$pdf->Cell(59	,5,'INVOICE',0,1);//end of line

//set font to arial, regular, 12pt
$pdf->SetFont('Arial','',12);

$query = "SELECT * FROM customer_order WHERE invoice_no= $invoice_no";
$run = mysqli_query($con,$query);
//$ro = mysqli_num_rows($run);
//if($ro == 1){
$row = mysqli_fetch_array($run);
$order_invoice  = $row['invoice_no'];
$cust_id        = $row['customer_id'];
$order_date     = $row['order_date'];
$product_id     = $row['product_id'];
$product_qty    = $row['products_qty'];
$product_amount = $row['product_amount'];    
      
      $cust_add     = $row['customer_address'];
      $cust_city    = $row['customer_city'];
      $cust_pcode   = $row['customer_pcode'];
      $cust_number  = $row['customer_phonenumber'];
      //end customer query
      $que="SELECT * from customer WHERE cust_id=$cust_id";
      $ru=mysqli_query($con,$que);
      $ro=mysqli_fetch_array($ru);
        $cust_email    = $ro['cust_email'];
        $cust_name     = $ro['cust_name'];
      

     //product Query
      $query="SELECT * FROM furniture_product f, customer_order s WHERE f.pid in(SELECT s.product_id FROM customer_order WHERE s.invoice_no='$invoice_no')";
      $run=mysqli_query($con,$query);
      //end product query



$pdf->Cell(130	,5,' ',0,0);
$pdf->Cell(59	,5,'',0,1);//end of line

$pdf->Cell(130	,5,'Mangalore, Kinnigoli 574150',0,0);
$pdf->Cell(25	,5,'Date',0,0);
$pdf->Cell(34	,5,$order_date,0,1);//end of line

$pdf->Cell(130	,5,'Phone +91xx-xxxxxxx',0,0);
$pdf->Cell(25	,5,'Invoice #',0,0);
$pdf->Cell(34	,5,$order_invoice,0,1);//end of line

$pdf->Cell(130	,5,'',0,0);
$pdf->Cell(25	,5,'Customer ID',0,0);
$pdf->Cell(34	,5,$cust_id,0,1);//end of line

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189	,10,'',0,1);//end of line

//billing address
$pdf->Cell(100	,5,'Bill to',0,1);//end of line

//add dummy cell at beginning of each line for indentation
$pdf->Cell(10	,5,'',0,0);
$pdf->Cell(90	,5,$cust_name,0,1);

$pdf->Cell(10	,5,'',0,0);
$pdf->Cell(90	,5,$cust_email,0,1);

$pdf->Cell(10	,5,'',0,0);
$pdf->Cell(90	,5,$cust_add.' , '.$cust_city.' , '.$cust_pcode,0,1);

$pdf->Cell(10	,5,'',0,0);
$pdf->Cell(90	,5,$cust_number,0,1);

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189	,10,'',0,1);//end of line

//invoice contents
$pdf->SetFont('Arial','B',12);

$pdf->Cell(130	,5,'Description',1,0);
$pdf->Cell(25	,5,'Quantity',1,0);
$pdf->Cell(34	,5,'Single Amount',1,1);//end of line

$pdf->SetFont('Arial','',10);

//Numbers are right-aligned so we give 'R' after new line parameter
      
      $amount=50;
   while($row=mysqli_fetch_array($run)){
      
$pdf->Cell(130	,5,$row['title'],1,0);
$pdf->Cell(25	,5,$row['products_qty'],1,0);
$pdf->Cell(34	,5,$row['product_amount'],1,1,'R');

$amount += $row['product_amount'];

//end of line
   }

//summary
$pdf->Cell(130	,5,'',0,0);
$pdf->Cell(25	,5,'Shipping',1,0);
$pdf->Cell(10	,5,'Rs',1,0);
$pdf->Cell(24	,5,'50',1,1,'R');//end of line


$pdf->Cell(130	,5,'',0,0);
$pdf->Cell(25	,5,'Subtotal',1,0);
$pdf->Cell(10	,5,'Rs',1,0);
$pdf->Cell(24	,5,$amount,1,1,'R');//end of line

$pdf->Cell(130	,5,'',0,0);
$pdf->Cell(25	,5,'Total Due',1,0);
$pdf->Cell(10	,5,'Rs',1,0);
$pdf->Cell(24	,5,$amount,1,1,'R');//end of line


$pdf->Output();
/**}else{
   $que = "SELECT * FROM customer_order WHERE invoice_no in(SELECT invoice_no FROM customer_order GROUP BY invoice_no having count(*) > 1)";
   $run = mysqli_query($con,$que);
   $row= mysqli_fetch_array($run);
   $order_invoic = $row['product_id'];
   if($run){
      $pdf->Cell(25	,5,'Invoice #',0,0);
      $pdf->Cell(34	,5,$order_invoic,0,1);//end of line
      
      
      $pdf->Output();
   }

} */
}
?>
