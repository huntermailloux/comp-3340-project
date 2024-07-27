<?php

// Include database connection file
require 'connectionString.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
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
    $email_sent = mail($to, $subject, $body, $headers);

    // Prepare SQL query
    $stmt = $conn->prepare("INSERT INTO UserCommunications (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    // Execute SQL query and check if successful
    if ($stmt->execute()) {
        if ($email_sent) {
            echo "Thank you for contacting us! Your message has been sent.";
        } else {
            echo "Thank you for contacting us! Your message could not be sent via email but has been recorded.";
        }
    } else {
        echo "Sorry, there was an error saving your message.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
