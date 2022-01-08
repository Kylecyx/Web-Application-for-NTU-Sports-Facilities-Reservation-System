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
// Include config file

$fac_id = $_SESSION['fac_id'];
$username=$_SESSION["username"];
$date_radio=$_POST['date_radio'];
//require_once "admin/config.php";
function check_multiple_bookings($username, $facility_id, $datetime) {
    include "admin/config.php";
    $sql = "SELECT time_slot FROM f32ee.bookings WHERE username = '$username' AND fac_id = $facility_id;";
    if (!$result = mysqli_query($conn, $sql)) {
		echo "Failed to get database: " . mysqli_error($conn);
		mysqli_close($conn);
	}
    $time_column = array();
    $t_booking = DateTime::createFromFormat('YmdHi', $datetime, new DateTimeZone('Asia/Singapore'));
    if ($t_booking === false) {
            die("Incorrect date string");
        } else {
            $ts_booking = $t_booking->getTimestamp(); //convert to timestamp of the slot that is booking
        }
    $row = mysqli_fetch_array($result);
    if (empty($row)) {
        //mysqli_close($conn);
        return False;
    } else {
        while($row){
            $time_booked = $row['time_slot'];
            $t_booked = DateTime::createFromFormat('YmdHi', $time_booked, new DateTimeZone('Asia/Singapore'));
            if ($t_booked === false) {
                die("Incorrect date string");
            } else {
                $ts_booked = $t_booked->getTimestamp(); //convert to timestamp of the slot that has been booked
            }
            $ts_difference = $ts_booking - $ts_booked;
            if ($ts_difference > -604800 && $ts_difference < 604800){
                echo "<p> Sorry, you has booked this facility within 7 days. Please cancel the previous booking first or book a new facility.</p><br><br>";
                //mysqli_close($conn);
	            return True;
		    } else {
                //mysqli_close($conn);
                return False;
		    }
        }
    }
}

function update_bookings_table($username, $facility_id, $datetime) {
    include "admin/config.php";
 	$sql = "INSERT INTO f32ee.bookings (booking_id, username, fac_id, time_slot) VALUES (NULL, '$username', $facility_id, '$datetime')";
	if (!mysqli_query($conn, $sql)) {
		echo "Failed to update database: " . mysqli_error($conn);
		mysqli_close($conn);
	}
    // use wordwrap() if lines are longer than 70 characters
    //$msg = wordwrap($msg,70);

    mysqli_close($conn);
}

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/commonstyle.css">
    <link rel="stylesheet" href="css/bookconfirm.css">
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

                <h1 class="my-5"><b>
                 <?php 
                    // echo "$fac_id at $date_radio"; 
                    if (check_multiple_bookings($username, $fac_id, $date_radio) == True){
                        //header("refresh:10;url=book_facility.php"); //10 seconds return back to order page
                        echo "To check/cancel your booking, click <a href='book_check.php'>Check my bookings</a>";
                        echo "<br><br> To start a new booking, click <a href='book_facility.php'>New Booking</a>";
                    }
                    else {  // Book Confirmed
                        update_bookings_table($username, $fac_id, $date_radio);
                        if ($fac_id==1){
                            $facility = "Swimming Pool @ SRC";
                        } elseif ($fac_id==2){
                            $facility = "GYM @ Northhill";
                        } elseif ($fac_id==3){
                            $facility = "Badminton Hall @ Northhill";
                        } elseif ($fac_id==4){
                            $facility = "Table Tennis Hall @ Northhill";
                        } elseif ($fac_id==5){
                            $facility = "Basketball Field @ SRC";
                        } elseif ($fac_id==6){
                            $facility = "Football Field @ SRC";
                        } elseif ($fac_id==7){
                            $facility = "Tennis Field @ SRC";
                        } 
                        $date_booked = substr($date_radio, 0, 8);
                        $hours_booked = substr($date_radio, 8, 2);
                        $newDate = date("l d/m/Y", strtotime($date_booked));  
                        $later_hours = $hours_booked + 1;
                        // 9 will become 09
                        $later_padded = sprintf("%02d", $later_hours);
                        $first_name=$_SESSION["first_name"];
                        //$first_name = get_name($username);
                        // echo message and booking details
                        echo "Your NTU gym booking has been confirmed.<br><br>";
                        echo "Booking details:";
                        ?>
                        <table border='1' cellspacing="0">
                            <tr>
                            <td><?php echo $facility; ?></td>
                            <td><?php echo $newDate; ?></td>
                            <td><?php echo "$hours_booked:00 - $later_padded:00"; ?></td>
                            <tr>
                        </table>
                        <?php
                        echo "<br>A confirmation email will be sent to your email later.";
                        echo "<br><br>To check/cancel your booking, click <a href='book_check.php'>Check my booking</a>";
                        echo "<br><br> To start a new booking, click <a href='book_facility.php'>New Booking</a>";
                        // echo "<br><br> <a href='user_main.php'>My Account</a>";
                        //send email
                        $to = "f32ee@localhost";
                        $subject = "NTU GYM booking confirmation";
                        $message = "Dear $first_name, your NTU GYM booking has been confirmed. Booking details: $facility $newDate $hours_booked:00 - $later_padded:00.";
                        $headers = 'From: f32ee@localhost'."\r\n".'Reply-To: f32ee@localhost'."\r\n".'X-Mailer: PHP/'.phpversion();
                        mail($to,$subject,$message,$headers,'-ff32ee@localhost');
                    }
                ?>
                </b></h1>
                </p>
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