<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, users will be directed to main page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: user_main.php");
    exit;
}
 
// Include config file
require_once "admin/config.php";
 
// Define the following variables
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Check the username and password error
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // username is required
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // password is required
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validation of existing users from the database
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = $username;
            
            // preparation 
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // if the email exists, check if password matches
                if(mysqli_stmt_num_rows($stmt) == 1){ 
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // a new login session will be started
                            session_start();
                            
                            // Set the session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // to the main page after logging in
                            header("location: user_main.php");
                        } else{
                            // password did not match
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // email has not been registered
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // sql stmt close
            mysqli_stmt_close($stmt);
        }
    }
    
    // end the database connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/commonstyle.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="shortcut icon" href="gym.ico" />
    <style>
        /* body{ font: 14px sans-serif; } */
        
        .m {
            background-color: #f8f9fa;
        }
        
        .w {
            background-color: white;
        }
        
        .main {
            width: 1200px;
            height: 700px;
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
    <div class="m"></div>

    <div class="m">
        <div class="w">
            <div class="main">
                     
                <div class="wrapper">
                    <div class="title1">
                         <h2>Login</h2>
                    </div>

                    

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group1">
                            <!-- <label>Username</label> -->
                            <input type="text" required placeholder="Email Address" 
                            name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                            <span class="invalid-feedback"><?php echo $username_err; ?></span>
                        </div>    
                        <div class="form-group2">
                            <!-- <label>Password</label> -->
                            <input type="password" required placeholder="Password"
                            name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                            <?php 
                            if(!empty($login_err)){
                                echo '<div class="alert alert-danger">' . $login_err . '</div>';
                            }        
                            ?>
                        </div>
                        <div class="form-group3">
                            <input type="submit" class="button" value="Login">
                        </div>
                        <p>Don't have an account? <a href="registration.php"><u>Sign up now</u></a>.</p>
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