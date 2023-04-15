<?php include('include/header.php'); 
require_once "loading/load.php";?>

        <div class="container sign-in-up">
          <div class="row justify-content-center">
            
            <div class="col-md-6">
              <div class="card">
                <div class="card-body">
                <h1 class="text-center mt-5">Souza Furniture Mart</h1>
                  
                  <h2 class="text-center mt-5">Register Account</h2>
                  
                  
                  <form method="post" class="mt-5 p-3">
                    
                    <?php 
                      if(isset($_POST['register'])){
                          
                          $fullname = $_POST['fullname'];
                          $email = $_POST['email'];
                          $password = md5($_POST['password']);
                          $conf_pass = md5($_POST['confirm-password']);
                          $number = $_POST['phone_number'];
                          
                          if(!empty($fullname) && !empty($email) && !empty($password) && !empty($conf_pass) && !empty($number)){
                            $que = mysqli_query($con,"SELECT * FROM customer WHERE cust_email='$email'");
                            if(mysqli_num_rows($que) > 0){
                              $error = "Email Already Exsist";
                            }else{
                            if($password === $conf_pass){

                              $cust_query="INSERT INTO customer(`cust_name`,`cust_email`,`cust_pass`,`cust_number`) VALUES('$fullname','$email','$password','$number')";


                              if(mysqli_query($con,$cust_query)){
                                  $message="You Are Registered Successfully!";
                              }
                              
                              
                              
                            } 
                            else{
                                $error="Passwords do not Match";
                            }
                          }
                        }
                            else{
                          $error="All (*) Fields Required";
                      }
                    
                      }
                      
                      ?>
                      <?php
                      if(isset($error)){
                      
                        echo "<div class='alert bg-danger' role='alert'>
                                <span class='text-white text-center'> $error</span>
                              </div>";
                    
                        }
                      else if(isset($message)){
                      
                        echo "<div class='alert bg-success' role='alert'>
                                <span class='text-white text-center'> $message</span>
                              </div>";
                    
                        }
                      
                      ?>
                    <div class="form-group">
                    
                      <input type="text" name="fullname" placeholder="Full Name" class="form-control" >
                     </div>

                    <div class="form-group">
                      <input type="text" name="email" placeholder="Email" class="form-control" >
                     </div>

                      <div class="row">
                        <div class="col-md-6 col-sm-6 col-12">
                          <div class="form-group">
                            <input type="password" name="password" placeholder="password" class="form-control" >
                          </div>
                        </div>
                        <div class="col-sm-6 col-12 col-md-6 ">
                          <div class="form-group">
                            <input type="password" name="confirm-password" placeholder="Confirm password" class="form-control" >
                          </div>
                        </div>
                      </div>
                  

                      
                    
                    <div class="form-group">
                      <input type="number" name="phone_number" placeholder="Phone Number" class="form-control" >
                   </div>

                      <div class="form-group text-center mt-4">
                        <input type="submit" name="register" class="btn btn-primary" value="Register">
                      </div>

                      <div class="text-center mt-4"> Already a Member? <a href="sign-in.php"> Sign in </a></div>

                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
   
        <?php include('include/footer.php');?>