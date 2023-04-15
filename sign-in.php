<?php
include('include/header.php');?>
        <div class="container sign-in-up">
          <div class="row justify-content-center" >
            
            <div class="col-md-6" style="height:66.5vh;">
              <div class="card">
                <div class="card-body">
                <h2 class="text-center mt-5">Souza Furniture Mart</h2>
                  
                  <h3 class="text-center mt-5">Sign in</h3>
                  
                  <form method="post" class="mt-5 p-3">

                   <?php if(isset($_POST['signin'])){
                        $email     = mysqli_real_escape_string($con,$_POST['email']);    
                        $password  = mysqli_real_escape_string($con,md5($_POST['password']));    
                        
                        $query = "SELECT * FROM customer";
                        $run   = mysqli_query($con,$query);
                        
                        if(mysqli_num_rows($run) > 0 ){
                           while($row = mysqli_fetch_array($run)){

                            $db_cust_id    = $row['cust_id'];
                            $db_cust_name  = $row['cust_name'];
                            $db_cust_email = $row['cust_email'];
                            $db_cust_pass  = $row['cust_pass'];
                            $db_cust_number= $row['cust_number'];

                            if($email == $db_cust_email && $password == $db_cust_pass){
                                $_SESSION['id']    = $db_cust_id;
                                $_SESSION['name']  = $db_cust_name;
                                $_SESSION['remail'] = $db_cust_email;
                                $_SESSION['number']= $db_cust_number;
                                
                                header('location:customer/index.php'); 

                            } 
                            else{
                              $error="Invalid Email or Password";
                            }
                           }
                          } 
                          else{
                            $error="This account doesn't exist";
                          }
                            
                         
                        
                      }
                        
                      ?>
                      
                         <?php
                      if(isset($error)){
                      
                        echo "<div class='alert bg-danger' role='alert'>
                                <span class='text-white text-center'> $error</span>
                                  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                  </button>
                              </div>";
                    
                        }
                      
                      ?>

                      
                    <div class="form-group">
                      <input type="text" name="email" placeholder="Email" class="form-control" required>
                     </div>
                     <div class="form-group">
                    <input type="password" name="password" placeholder="password" class="form-control" required>
                    </div>
                      
                    <a href="forgot-pass.php" > Forget Password?</a>

                      <div class="form-group text-center mt-4">
                        <input type="submit" name="signin" class="btn btn-primary" value="Sign in">
                      </div>

                      <div class="text-center mt-4"> Not a Member Yet <a href="register.php"> Register </a></div>

                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
   
  <?php include('include/footer.php'); ?>