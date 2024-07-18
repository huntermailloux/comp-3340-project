<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars_decode($_POST['message']); // Decode HTML entities

    // Email settings
    $to = "email@example.ca";  // Replace with actual email
    $subject = "Contact Form Submission from " . $name;
    $headers = "From: " . $email;

    // Message
    $body = "Name: " . $name . "\n";
    $body .= "Email: " . $email . "\n";
    $body .= "Message: " . $message . "\n";

    // Send email
    if (mail($to, $subject, $body, $headers)) {
        echo "Thank you for contacting us!";
    } else {
        echo "Sorry, there was an error sending your message.";
    }
}
?>