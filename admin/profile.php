<?php 
 require_once('include/header.php');

if(!isset($_SESSION['email'])){
    header('location: signin.php');
}
if(isset($_SESSION['email'])){
    $session_id = $_SESSION['id'];
    $session_email = $_SESSION['email'];
}
?>
<div class="container-fluid mt-2">
     <div class="row">
           <div class="col-md-3 col-lg-3">
            <?php require_once('include/sidebar.php'); ?>
            </div>
        
            <div class="col-md-9 col-lg-9">

               <div class="row">
                  <div class="col-md-1">
                    <i class="fad fa-user-cog fa-6x text-primary"></i>
                  </div>
                  <div class="col-md-11 text-left mt-4">
                  <h1 class="ml-5 display-4 font-weight-normal">Profile Setting:</h1>
                  </div>
                </div>
              <hr>

              <!-- Extended material form grid -->
             <form method="post" enctype="multipart/form-data">
                <?php
                if(isset($allData['num_of_usr'])){
                $query = "SELECT * FROM admin WHERE email='$session_email'";
                $run=mysqli_query($con,$query);
                $row=mysqli_fetch_array($run);
                
                   $db_name = $row['name'];
                   $db_email = $row['email'];
                   $db_password = $row['password']; 
                   $image = $row['image'];
                }
                 if(isset($_POST['submit']))
                 {  
                     $name         = $_POST['name'];
                     $password     = md5($_POST['password']); 
                                       

                     $u_query ="UPDATE admin SET name='$name', password='$password' WHERE id ='$session_id'";
                      if(mysqli_query($con,$u_query)){
                         $message = "Profile Has Been Updated";
                      }              
                       
                        
                 }
                
                 ?>
                 <div class="row">
                  <?php if(isset($message)){
                        echo "<p style='color:green; font-weight:bold;'>$message</p>";
                    }?>
                   <div class="col-md-12 mt-4">
                      <label for="name" class="font-weight-bold">Email:</label> <?php echo $session_email;?>
                    </div>
                  </div>
                 
                  
                  <div class="row mt-4">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="name" class="font-weight-bold">Name:</label>
                        <input type="text" name="name" class="form-control">
                      </div>

                      <div class="form-group">
                        <label for="name" class="font-weight-bold">Password:</label>
                        <input type="password" name="password" class="form-control" id="inputPassword4MD" placeholder="Password">
                      </div>

                    
                        <input type="hidden" class="form-control-file border" type="file" name="upload">
                    </div>
                  
                    
                    
                  </div>
                  
                  <input type="submit" name="submit" class="btn btn-primary btn-md" value="Submit">
                </form>
            </div>
    </div>
                <!-- Extended material form grid -->
            </div>

     <?php 
                     
 require_once('include/footer.php');
?>