<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer manually
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize input
    $name    = htmlspecialchars($_POST['name']);
    $email   = htmlspecialchars($_POST['email']);  // client email
    $phone   = htmlspecialchars($_POST['phone']);
    $service = htmlspecialchars($_POST['service']);
    $message = htmlspecialchars($_POST['message']);

    try {
        // ----------------- SEND EMAIL TO ADMIN -----------------
        $mailAdmin = new PHPMailer(true);
        $mailAdmin->SMTPDebug = 0;
        $mailAdmin->isSMTP();
        $mailAdmin->Host       = 'smtp.gmail.com';
        $mailAdmin->SMTPAuth   = true;
        $mailAdmin->Username   = 'maukierstus@gmail.com';
        $mailAdmin->Password   = 'gtkzvjpledkaxopx';
        $mailAdmin->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailAdmin->Port       = 587;

        $mailAdmin->setFrom('maukierstus@gmail.com', 'Virtual Assistance');
        $mailAdmin->addAddress('maukierstus@gmail.com'); // Admin email
        $mailAdmin->addReplyTo($email, $name);

        $mailAdmin->isHTML(true);
        $mailAdmin->Subject = "New Booking - $service";
        $mailAdmin->Body = "
            <h3>New Contact Message</h3>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Phone:</strong> {$phone}</p>
            <p><strong>Service:</strong> {$service}</p>
            <p><strong>Message:</strong><br>{$message}</p>
        ";
        $mailAdmin->AltBody = strip_tags($mailAdmin->Body);

        $mailAdmin->send();

        // ----------------- SEND CONFIRMATION TO CLIENT -----------------
        $mailClient = new PHPMailer(true);
        $mailClient->SMTPDebug = 0;
        $mailClient->isSMTP();
        $mailClient->Host       = 'smtp.gmail.com';
        $mailClient->SMTPAuth   = true;
        $mailClient->Username   = 'maukierstus@gmail.com';
        $mailClient->Password   = 'gtkzvjpledkaxopx';
        $mailClient->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailClient->Port       = 587;

        $mailClient->setFrom('maukierstus@gmail.com', 'Virtual Assistant');
        $mailClient->addAddress($email, $name);

        $mailClient->isHTML(true);
        $mailClient->Subject = "Booking Confirmation - $service";

        // HTML card layout
        $mailClient->Body = "
        <div style='max-width:600px;margin:auto;padding:20px;border:1px solid #ddd;border-radius:10px;font-family:Arial,sans-serif;color:#333;background:#f9f9f9;'>
            <h2 style='text-align:center;color:#2E86C1;'>Booking Confirmation</h2>
            <p>Hi <strong>{$name}</strong>,</p>
            <p>Thank you for booking with us! Here are your booking details:</p>
            <table style='width:100%;border-collapse:collapse;'>
                <tr><td style='padding:8px;border:1px solid #ddd;'><strong>Name</strong></td><td style='padding:8px;border:1px solid #ddd;'>{$name}</td></tr>
                <tr><td style='padding:8px;border:1px solid #ddd;'><strong>Email</strong></td><td style='padding:8px;border:1px solid #ddd;'>{$email}</td></tr>
                <tr><td style='padding:8px;border:1px solid #ddd;'><strong>Phone</strong></td><td style='padding:8px;border:1px solid #ddd;'>{$phone}</td></tr>
                <tr><td style='padding:8px;border:1px solid #ddd;'><strong>Service</strong></td><td style='padding:8px;border:1px solid #ddd;'>{$service}</td></tr>
                <tr><td style='padding:8px;border:1px solid #ddd;'><strong>Message</strong></td><td style='padding:8px;border:1px solid #ddd;'>{$message}</td></tr>
            </table>
            <p>We will get back to you shortly with further confirmation.</p>
            <p style='text-align:center;color:#888;margin-top:20px;'>Best regards,<br>Erastus Mauki\nVirtual Assistant</p>
        </div>
        ";

        $mailClient->AltBody = "Hi {$name},\n\nThank you for your booking!\n\nName: {$name}\nEmail: {$email}\nPhone: {$phone}\nService: {$service}\nMessage: {$message}\n\nWe will get back to you shortly.\n\nBest regards,\nErastus Mauki, VA";

        $mailClient->send();

        echo "<script>alert('Booking successful! A confirmation email has been sent to your email.'); window.location.href='contact.html';</script>";

    } catch (Exception $e) {
        echo "<h3>Message could not be sent.</h3>";
        echo "Mailer Error: " . $e->getMessage();
    }

} else {
    echo "Invalid request method.";
}
?>
