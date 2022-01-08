<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
	<link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/commonstyle.css">
	<link rel="stylesheet" href="css/bookfacility.css">
    <link rel="shortcut icon" href="gym.ico" />
    <style>
        .main{ font: 14px sans-serif; text-align: center; }
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
				<div class="h1">
					<h1>New Booking</h1>
				</div>
				<div class="box">
					<div class="h4">
						Select a Venue
					</div>
					<form action="book_timetable.php" method="POST">
					<label for="facility"> <!-- Can add label if want -->
						<select id="facility" name="facility" onclick="getOption()" class="venue"> <!-- Use "select" to create object -->
							<option value="1">Swimming Pool @ SRC</option>
							<option value="2">GYM @ Northhill</option>
							<optgroup label="Sports Halls">
								<option value="3">Badminton Hall @ Northhill</option>
								<option value="4">Table Tennis Hall @ Northhill</option>
								<option value="5">Basketball Field @ SRC</option>
								<option value="6">Football Field @ SRC</option>
								<option value="7">Tennis Field @ SRC</option>
						</select>
					</label>
                    <p>
                        (Please note that you can only book the same facility <b>once</b> a week according to the school's rule) 
                    </p>
					<!-- <p>
						The value of the option selected is:
						<span class="output"></span><br>
					</p> -->
					<script type="text/javascript">
						function getOption() {
							selectElement = 
									document.querySelector('#facility');
								
							output = selectElement.value;
			
							document.querySelector('.output').textContent = output;
							document.cookie="fac=output";
						}
					</script>
					<center><input type="submit" value="Submit"/></center>
                    <a href="user_main.php" class="back"><u><< Back to the previous page</u></a>
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