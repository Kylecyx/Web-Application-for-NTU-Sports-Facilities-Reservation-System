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
require_once "admin/config.php";
$fac_id=$_POST['facility'];
$_SESSION['fac_id'] = $fac_id;
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Week Reservation Table</title>
	<meta name="generator" content="BBEdit 9.6" />
	<link rel="stylesheet" href="css/book_timetable.css">
</head>

<body>
	<a name="reserve"></a>
	<div class="window">
		<h2><?php echo "$facility"; ?></h2>


		<?php
			include "php/get_current_time.php";
			// Format strings
			$timeFormat = 'G:i'; // 24hours
			$weekdayFormat = 'l'; // Monday
			$dateFormat = 'Y/m/d'; // April 1
			$valueFormat = 'YmdHi'; // 201303180900
			//set the default timezone
			date_default_timezone_set('Asia/Singapore');
			//echo date_default_timezone_get();
			$now = $tmestamp;

			// Get reserved hours
			$reserved = array();
			if(isset($_POST['reserved'])) {
				$a = explode(' ', preg_replace('/\s{2,}|[^\s\d]/', ' ', $_POST['reserved']));
				foreach($a as $time) {
					$reserved[] = strtotime($time);
				}
			}
			
			// Get timestamps			
			$startingTime = strtotime("now 8am"); // current week, Monday, 9AM
			$endingTime = strtotime('+1 week');
			$startingMonth = date('F', $startingTime);
			$endingMonth = date('F', $endingTime);
			$startingDay = date('j', $startingTime);
			$endingDay = date('j', $endingTime);

			// Week of ...
			$period = $startingMonth . ' ' . $startingDay . ' - ';
			if($startingMonth != $endingMonth) {
				$period .= $endingMonth . ' ';
			}
			$period .= $endingDay;
		?>

		<form action="book_confirm.php" method="POST">
			
			<table cellpadding="0" cellspacing="4" border="0" class="reservation">
				<tr>
					<td colspan=7><h3>Week of <?php echo "$period"?></h3></td>
				</tr>

				<?php
					
					// Table header
					echo '<tr>';
					for($i = 0; $i < 7; $i++) {
						$time = strtotime("+$i days", $startingTime);
						echo '<th><label>' . date($weekdayFormat, $time)  . '</label></th>';
					}
					echo '</tr>';
					for($h = 0; $h < 15; $h++) {
						echo '<tr>';
						for($i = 0; $i < 7; $i++) {
							$time = strtotime("+$i days $h hours", $startingTime);
							$value = date($valueFormat, $time);
							if($time <= $now) {
								$class = 'closed';
							} else {
								$sql="SELECT count(*) as total FROM f32ee.bookings WHERE time_slot = '$value' AND fac_id = '$fac_id'";
								if (!$result = mysqli_query($conn, $sql)) {
									echo "<br>Failed to fetch data from bookings: " . mysqli_error($conn);
								}
								$data=mysqli_fetch_assoc($result);
								if($data['total'] >= 1) {
									$class = 'closed';
								} else {
								$class = (in_array($time, $reserved)) ? 'reserved': 'avail';
								}
							}
							$text = date($timeFormat, $time);
							echo "<td><label class='$class'><input type='radio' name='date_radio' value='$value' required/>$text</label></td>";
						}
						echo '</tr>';
					}

					// Table footer
					echo '<tr>';
					for($i = 0; $i < 7; $i++) {
						$time = strtotime("+$i days", $startingTime);
						echo '<th><label>' . date($dateFormat, $time)  . '</label></th>';
					}
					echo '</tr>';
				?>
			</table>
			<center><input type="submit" value="Submit" /></center>
			<a href="book_facility.php" class='back'><< Back to the previous page</a>
		</form>
	</div>
</body>
</html>