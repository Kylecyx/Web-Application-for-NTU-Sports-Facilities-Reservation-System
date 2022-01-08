<?php
    $time_booked = "202111011300";
    $d = DateTime::createFromFormat('YmdHi', $time_booked, new DateTimeZone('Asia/Singapore'));
    if ($d === false) {
        die("Incorrect date string");
    } else {
        $ts = $d->getTimestamp();
        echo $ts;
    } //1635138000 1635742800 604800
    $to = "f32ee@localhost";
    $subject = "NTU GYM booking confirmation";
    $message = "Dear yma011, your NTU GYM booking has been confirmed. Booking details: $time_booked";
    $headers = 'From: f32ee@localhost'."\r\n".'Reply-To: f32ee@localhost'."\r\n".'X-Mailer: PHP/'.phpversion();

    mail($to,$subject,$txt,$headers,'-ff32ee@localhost');
    echo ("mail sent to : ".$to);
        
?>
