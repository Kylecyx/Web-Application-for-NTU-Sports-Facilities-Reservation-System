<?php
$servername = "localhost";
$username = "f32ee";
$password = "f32ee";
$DBname = "f32ee";

//Create connection
$conn = mysqli_connect($servername, $username, $password, $DBname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//Create table 1:
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
	first_name VARCHAR(50) NOT NULL,
	last_name VARCHAR(50) NOT NULL,
	phone_number VARCHAR(8) NOT NULL
)";

if (!mysqli_query($conn, $sql)) {
	echo "Error creating users table: " . mysqli_error($conn);
	mysqli_close($conn);
}

//Create table 2:
$sql = "CREATE TABLE IF NOT EXISTS facilities (
    fac_id INT UNSIGNED,
    fac_name VARCHAR(50) NOT NULL,
	PRIMARY KEY (fac_id)
)";

if (!mysqli_query($conn, $sql)) {
	echo "Error creating facilities table: " . mysqli_error($conn);
	mysqli_close($conn);
}
//$sql = "DROP TABLE bookings";

//Create table 3:
$sql = "CREATE TABLE IF NOT EXISTS bookings(
	booking_id INT UNSIGNED AUTO_INCREMENT, 
	username VARCHAR(50) NOT NULL,
	fac_id INT UNSIGNED,
	time_slot VARCHAR(50) NOT NULL,
	PRIMARY KEY (booking_id, username, fac_id),
	FOREIGN KEY (username) REFERENCES users(username),
	FOREIGN KEY (fac_id) REFERENCES facilities(fac_id)
)";

if (!mysqli_query($conn, $sql)) {
	echo "Error creating bookings table: " . mysqli_error($conn);
	mysqli_close($conn);
}


$sql = "INSERT IGNORE INTO facilities VALUES (1, 'swimming_src');";
$sql .= "INSERT IGNORE INTO facilities VALUES (2, 'gym_nh');";
$sql .= "INSERT IGNORE INTO facilities VALUES (3, 'badminton_nh');";
$sql .= "INSERT IGNORE INTO facilities VALUES (4, 'tabletennis_nh');";
$sql .= "INSERT IGNORE INTO facilities VALUES (5, 'basketball_src');";
$sql .= "INSERT IGNORE INTO facilities VALUES (6, 'foodball_src');";
$sql .= "INSERT IGNORE INTO facilities VALUES (7, 'tennis_src');";

$sql .= "INSERT IGNORE INTO bookings VALUES (1, 'chelseaandteen@gmail.com', 2, '202111050900');";
//$sql .= "DELETE FROM f32ee.bookings";

//check if values are settled
if (!mysqli_multi_query($conn, $sql)) {
	echo "Failed to fill tables with data: " . mysqli_error($conn);
	mysqli_close($conn);
}

mysqli_close($conn);
?>