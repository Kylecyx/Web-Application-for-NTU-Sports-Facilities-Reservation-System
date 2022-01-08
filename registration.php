<?php
// Including config file
require_once "admin/config.php";
 
// define empty values
$username = $password = $confirm_password = $first_name = $last_name = $phone_number = "";
$username_err = $password_err = $confirm_password_err = $first_name_err = $last_name_err = $phone_number_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please provide your NTU school email.";
    } elseif(!filter_var($_POST["username"], FILTER_VALIDATE_EMAIL)) {
            $username_err = "Please enter a valid NTU school email."; 
    } else{
        list ($email_name, $domain) = explode('@', $_POST["username"]);
        if($domain != 'e.ntu.edu.sg') {
            $username_err = "Please enter a valid NTU school email."; 
        } else{
            // stmt preparation 
            $sql = "SELECT id FROM users WHERE username = ?";
            
            if($stmt = mysqli_prepare($conn, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                
                // trim posted variables
                $param_username = trim($_POST["username"]);
                
                // Check execute status
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    // Check if the email is registered
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $username_err = "This username has been registered. Please login use the link below.";
                    } else{
                        // save into the database
                        $username = trim($_POST["username"]);
                    }
                } else{
                    echo "Error! Please try again.";
                }

                // sql stmt close
                mysqli_stmt_close($stmt);
            }
        }
    }
    
    // Validate last name
    if(empty(trim($_POST["last_name"]))){
        $last_name_err = "Please enter your last name.";     
    } else{
        $last_name = trim($_POST["last_name"]);
    }
    // Validate first name
    if(empty(trim($_POST["first_name"]))){
        $first_name_err = "Please enter your first_name.";     
    } else{
        $first_name = trim($_POST["first_name"]);
    }

    // Validate phone
    if(empty(trim($_POST["phone_number"]))){
        $phone_number_err = "Please enter a valid phone number.";     
    } elseif(strlen(trim($_POST["phone_number"])) != 8){
        $phone_number_err = "Phone number must be 8 digits.";
    } else{
        $phone_number = trim($_POST["phone_number"]);
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have at least 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($last_name_err) && empty($first_name_err) && empty($phone_number_err) && empty($password_err) && empty($confirm_password_err)){
        $sql = "INSERT INTO users (username, first_name, last_name, phone_number, password) VALUES (?, ?, ?, ?, ?)";
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_username, $param_first_name, $param_last_name, $param_phone_number, $param_password);
            // Set parameters
            $param_username = $username;
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_phone_number = $phone_number;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/commonstyle.css">
    <link rel="stylesheet" href="css/registration.css">
    <link rel="shortcut icon" href="gym.ico" />
    <style>
        .m {
            background-color: #f8f9fa;
        }
        
        .w {
            background-color: white;
        }
        
        .main {
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

    <div class="m">
        <div class="w">
            <div class="main">
               <h1>Sign Up</h1>
            <div class="wrapper">
                
                <h4>Please fill this form to create an account</h4>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <input type="text" required placeholder="NTU Email Address" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                        <span class="invalid-feedback"><?php echo $username_err; ?></span>
                    </div>    
                    <div class="form-group">
                        <input type="text" required placeholder="Last Name" name="last_name" class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>">
                        <span class="invalid-feedback"><?php echo $last_name_err; ?></span>
                    </div>  
                    <div class="form-group">
                        <input type="text" required placeholder="First Name" name="first_name" class="form-control <?php echo (!empty($first_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $first_name; ?>">
                        <span class="invalid-feedback"><?php echo $first_name_err; ?></span>
                    </div>  
                    <div class="form-group">
                        <input type="text" required placeholder="Phone Number" name="phone_number" class="form-control <?php echo (!empty($phone_number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone_number; ?>">
                        <span class="invalid-feedback"><?php echo $phone_number_err; ?></span>
                    </div>  
                    <div class="form-group">
                        <input type="password" required placeholder="Password (more than 6 characters)" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="password" name="confirm_password" required placeholder="Confirm Password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="submit" value="Submit">
                        <input type="reset" class="reset" value="Reset">
                    </div>
                    <p>Already have an account? <a href="login.php"><u>Login here</u></a>.</p>
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