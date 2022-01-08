<?php
    include "create_db.php";
?>
<?php
    require_once "config.php";

    //table 1
    echo "<table border='1'><tr>
    <th>id</th>
    <th>username</th>
    <th>first_name</th>
    <th>last_name</th>
    <th>phone_number</th>
    <th>password</th>
    </tr>";
    $result1 = mysqli_query($conn,"SELECT * FROM f32ee.users");

    while($row = mysqli_fetch_array($result1))
    {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['username'] . "</td>";
    echo "<td>" . $row['first_name'] . "</td>";
    echo "<td>" . $row['last_name'] . "</td>";
    echo "<td>" . $row['phone_number'] . "</td>";
    echo "<td>" . $row['password'] . "</td>";
    echo "</tr>";
    }
    echo "</table>";
    echo "<br>";


    //table 2
    echo "<table border='1'><tr>
    <th>fac_id</th>
    <th>fac_name</th>
    </tr>";
    $result2 = mysqli_query($conn,"SELECT * FROM f32ee.facilities");

    while($row = mysqli_fetch_array($result2))
    {
    echo "<tr>";
    echo "<td>" . $row['fac_id'] . "</td>";
    echo "<td>" . $row['fac_name'] . "</td>";
    echo "</tr>";
    }
    echo "</table>";
    echo "<br>";

        //table 3
    echo "<table border='1'><tr>
    <th>booking_id</th>
    <th>username</th>
    <th>fac_id</th>
    <th>time_slot</th>
    </tr>";
    $result3 = mysqli_query($conn,"SELECT * FROM f32ee.bookings");

    while($row = mysqli_fetch_array($result3))
    {
    echo "<tr>";
    echo "<td>" . $row['booking_id'] . "</td>";
    echo "<td>" . $row['username'] . "</td>";
    echo "<td>" . $row['fac_id'] . "</td>";
    echo "<td>" . $row['time_slot'] . "</td>";
    echo "</tr>";
    }
    echo "</table>";
    echo "<br>";
?>
<?php
    mysqli_close($conn);
    ?>


