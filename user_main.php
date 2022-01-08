<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<?php
    function get_name($username)
    {
        include "admin/config.php";
        $sql="SELECT * FROM f32ee.users WHERE username ='$username'";

        if (!$result = mysqli_query($conn, $sql)) {
            echo "Failed to get database: " . mysqli_error($conn);
            mysqli_close($conn);
        }
        $row = mysqli_fetch_assoc($result);
        while($row) {
            $first_name = $row["first_name"];
            return $first_name;
         }
    }

    $username=$_SESSION["username"];
    $first_name = get_name($username);
    $_SESSION['first_name'] = $first_name;
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/commonstyle.css">
    <link rel="stylesheet" href="css/user_main.css">
    <link rel="shortcut icon" href="gym.ico" />
    <style>
        .m {
            background-color: #f8f9fa;
        }
        
        .w {
            background-color: white;
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
                <h1 class="my-5">Hi, <b><?php echo "$first_name"; ?></b>! </h1>
                <div class="box">
                    <p>
                        <a href="book_facility.php" class="b1">
                            New booking
                        </a>
                        <a href="book_check.php" class="b2">Check my booking</a>
                        <a href="reset_password.php" class="b3">Reset my Password</a>
                        <a href="logout.php" class="b4"><u>Sign Out of My Account</u></a>
                    </p>
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