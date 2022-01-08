<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "admin/config.php";
 
// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have atleast 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($conn);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/commonstyle.css">
    <link rel="stylesheet" href="css/reset_password.css">
    <link rel="shortcut icon" href="gym.ico" />
    <style>
        .m {
            background-color: #f8f9fa;
        }
        
        .w {
            background-color: white;
        }
        
        .main {
            width: 1200px;
            height: 500px;
        }
    </style>
</head>
<body>
    <header class="header w">
        <div class="logo">
            <h1>
                <a href="index1.html" title="NTU">NTU</a>
            </h1>
        </div>
        <div class="title">
            <h1>Welcome to Sports & Recreation Centre!</h1>
        </div>
    </header>

    <nav class="nav">
        <div class="w">
            <div class="navitems">
                <ul>
                    <li>
                        <a href="index1.html">Home</a>
                        <a href="login.php">Login/Sign up</a>
                        <a href="user_main.php">My account</a>
                        <a href="#contact">Contact us</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="m">
        <div class="w">
            <div class="main">
                    <div class="title1">
                         <h2>Reset Password</h2>
                    </div>
            <div class="wrapper">

                  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                        <div class="form-group1">
                            <input type="password" name="new_password" required placeholder = "New Password"class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                            <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
                        </div>
                        <div class="form-group2">
                            <input type="password" name="confirm_password" required placeholder = "Confirm Password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                        </div>
                        <div class="form-group3">
                            <input type="submit" class="submit" value="Submit">
                            <a class="cancel" href="user_main.php">Cancel</a>
                        </div>
                  </form>
                     </div>   
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="w">
            <div class="address">
                Nanyang Technological University <br> 50 Nanyang Avenue, Singapore 639798 <br> Tel: (65) 67911744 <br><br> Novena Campus <br> 11 Mandalay Road, Singapore 308232 <br> Tel: (65) 65138572
            </div>
            <div class="contact">
                <h3 id="contact">
                    Contact Us <br><br>
                </h3>
                Chen Yuxuan: <a href="mailto:chen1252@e.ntu.edu.sg">chen1252@e.ntu.edu.sg</a>
                <br><br> Ma Yuchen: <a href="mailto:yma011@e.ntu.edu.sg">yma011@e.ntu.edu.sg</a>
            </div>
        </div>
    </footer>
</body>
</html>