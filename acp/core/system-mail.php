<?php 
$email = "steve.cristofalo@gmail.com";
$subject =  "Email Test";
$message = "this is a mail testing email function on server";


mail($email, $subject, $message);
echo "Email Sent Successfully";