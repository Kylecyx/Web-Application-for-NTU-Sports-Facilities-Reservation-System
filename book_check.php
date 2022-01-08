<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "admin/config.php";
$username=$_SESSION["username"];

if(isset($_POST['save'])){
	$checkbox = $_POST['check'];
	for($i=0;$i<count($checkbox);$i++){
	    $del_id = $checkbox[$i]; 
        $sql="DELETE FROM f32ee.bookings WHERE booking_id='$del_id' AND username = '$username'";
        if (!$result = mysqli_query($conn, $sql)) {
            echo "Failed to get database: " . mysqli_error($conn);
            mysqli_close($conn);
        }
    }
}

$sql = "SELECT * FROM f32ee.bookings WHERE username = '$username'";
if (!$result = mysqli_query($conn, $sql)) {
    echo "Failed to get database: " . mysqli_error($conn);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NTU GYM</title>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/commonstyle.css">
    <link rel="stylesheet" href="css/bookcheck.css">
    <link rel="shortcut icon" href="gym.ico" />
    <style>


}
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
    <div class="m"></div>

    <div class="m">
        <div class="w">
            <div class="main">
                <div class="booking">
                    <div>
                            <h1>You have booked:</h1>
                                        
                    </div>
                    <form action="" method="post">
                        <table border='1' cellspacing='0' cellpadding='10' class='table' >
                            <tr>
                                    <th>Select to Cancel</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Facility</th>
                            </tr>
                            <?php 
                                $i=0;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $time_booked = $row['time_slot'];
                                    $fac_id = $row['fac_id'];
                                    $book_id = $row['booking_id'];
                                    $date_booked = substr($time_booked, 0, 8);
                                    $hours_booked = substr($time_booked, 8, 2);
                                    $newDate = date("l d/m/Y", strtotime($date_booked));  
                                    $later_hours = $hours_booked + 1;
                                    // 9 will become 09
                                    $later_padded = sprintf("%02d", $later_hours);
                                    //echo "$date_booked<br>";
                                    //echo "$later_padded<br>";
                                    if ($fac_id==1){
                                            $facility = "Swimming Pool @ SRC";
                                    } elseif ($fac_id==2){
                                        $facility = "GYM @ Northhill";
	                                } elseif ($fac_id==3){
                                        $facility = "Badminton hall @ Northhill";
	                                } elseif ($fac_id==4){
                                        $facility = "Table Tennis hall @ Northhill";
	                                } elseif ($fac_id==5){
                                        $facility = "Basketball Field @ SRC";
	                                } elseif ($fac_id==6){
                                        $facility = "Football Field @ SRC";
	                                } elseif ($fac_id==7){
                                        $facility = "Tennis Field @ SRC";
	                                } 
                            ?>
                            <tr>
                            <td style='width:10px;'><input type="checkbox" id="checkItem" name="check[]" value="<?php echo $row["booking_id"]; ?>"></td>
                            <td><?php echo $newDate; ?></td>
                            <td><?php echo "$hours_booked:00 - $later_padded:00"; ?></td>
                            <td><?php echo $facility; ?></td>
                            </tr>
                            <?php
                                $i++;
                                }
                                mysqli_close($conn);
                            ?>
                        </table>
                        <p align="center"><button type="submit" class="cancel" name="save">CANCEL BOOKING</button></p>
                    </form>
                    <nav>
                    <ul>
                        <li><a href="user_main.php"><b><< Back to the main page</b></a></li>
                    </ul>
                </nav>
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