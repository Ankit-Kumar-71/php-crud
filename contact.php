
<?php
require_once 'db.php';
require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$name = '';
$email = '';
$subject = '';
$message = '';

$errors = [];
$success = '';
$emailWarning = '';

function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validation
    if ($name === '' || strlen($name) > 100) {
        $errors[] = 'Please enter a valid name (maximum 100 characters).';
    }

    if (
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        strlen($email) > 150
    ) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($subject === '' || strlen($subject) > 200) {
        $errors[] = 'Please enter a valid subject (maximum 200 characters).';
    }

    if ($message === '') {
        $errors[] = 'Message is required.';
    }

    if (empty($errors)) {
        try {
            // Step 1: Save message in database
            $sql = "INSERT INTO contact_messages
                    (name, email, subject, message)
                    VALUES (:name, :email, :subject, :message)";

            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':subject' => $subject,
                ':message' => $message
            ]);

            // Step 2: Send email using PHPMailer
            try {
                $mail = new PHPMailer(true);

                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;

                // Apni Gmail ID daalo
                $mail->Username = 'jeetkashyap20224@gmail.com';

                // Google App Password daalo
                $mail->Password = 'tkrz zgey qwws ofhv';

                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                // Sender account tumhari Gmail honi chahiye
                $mail->setFrom(
                    'yourgmail@gmail.com',
                    'Website Contact Form'
                );

                // Jis email par messages receive karne hain
                $mail->addAddress('jeetkashyap20224@gmail.com');

                // Reply karne par visitor ko reply kar sakte ho
                $mail->addReplyTo($email, $name);

                $mail->isHTML(true);
                $mail->Subject = 'New Contact Message: ' . $subject;

                $mail->Body =
                    '<h2>New Contact Message</h2>' .
                    '<p><strong>Name:</strong> ' . e($name) . '</p>' .
                    '<p><strong>Email:</strong> ' . e($email) . '</p>' .
                    '<p><strong>Subject:</strong> ' . e($subject) . '</p>' .
                    '<p><strong>Message:</strong><br>' .
                    nl2br(e($message)) . '</p>';

                $mail->AltBody =
                    "Name: $name\n" .
                    "Email: $email\n" .
                    "Subject: $subject\n" .
                    "Message: $message";

                $mail->send();

                $success = 'Your message has been saved and emailed successfully.';

            } catch (Exception $mailError) {
                error_log($mailError->getMessage());

                // Database mein message save hai, email fail hua
                $success = 'Your message has been saved successfully.';
                $emailWarning = 'Email could not be sent. Please check your SMTP settings.';
            }

            // Clear fields after submission
            $name = '';
            $email = '';
            $subject = '';
            $message = '';

        } catch (PDOException $dbError) {
            error_log($dbError->getMessage());
            $errors[] = 'Message could not be saved. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 15px;
        }

        .contact-container {
            width: 100%;
            max-width: 1000px;
            display: flex;
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .1);
        }

        .contact-info {
            width: 40%;
            background: green;
            color: white;
            padding: 40px 30px;
        }

        .contact-info h2,
        .contact-form h2 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        .contact-info > p {
            color: #e5edff;
            line-height: 1.7;
            margin-bottom: 35px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 28px;
        }

        .info-item span {
            font-size: 25px;
        }

        .info-item h3 {
            font-size: 16px;
            margin-bottom: 7px;
        }

        .info-item p {
            color: #e5edff;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .contact-form {
            width: 60%;
            padding: 40px;
        }

        .contact-form h2 {
            color: #1e293b;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #334155;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-size: 15px;
            font-family: inherit;
            outline: none;
            transition: .3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
        }

        .form-group textarea {
            resize: vertical;
        }

        .contact-form button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 7px;
            background: green;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: .3s;
        }

        .contact-form button:hover {
            background: #166534;
        }

        .success,
        .error,
        .warning {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 7px;
            line-height: 1.5;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error,
        .warning {
            background: #fee2e2;
            color: #991b1b;
        }

        .error ul {
            padding-left: 20px;
        }

        @media (max-width: 700px) {
            .contact-container {
                flex-direction: column;
            }

            .contact-info,
            .contact-form {
                width: 100%;
                padding: 30px 22px;
            }

            .contact-info h2,
            .contact-form h2 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
<div class="contact-container">

    <div class="contact-info">
        <h2>Get In Touch</h2>
        <p>
            Agar aapka koi question hai, toh humein message bhejein.
            Hum aapki help karne ki poori koshish karenge.
        </p>

        <div class="info-item">
            <span>📧</span>
            <div>
                <h3>Email</h3>
                <p>info@example.com</p>
            </div>
        </div>

        <div class="info-item">
            <span>📞</span>
            <div>
                <h3>Phone</h3>
                <p>+91 98765 43210</p>
            </div>
        </div>

        <div class="info-item">
            <span>📍</span>
            <div>
                <h3>Address</h3>
                <p>New Delhi, India</p>
            </div>
        </div>
    </div>

    <div class="contact-form">
        <h2>Contact Us</h2>

        <?php if ($success !== ''): ?>
            <div class="success"><?= e($success) ?></div>
        <?php endif; ?>

        <?php if ($emailWarning !== ''): ?>
            <div class="warning"><?= e($emailWarning) ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="contact.php" method="POST">
            <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name"
                    placeholder="Enter your name"
                    value="<?= e($name) ?>" maxlength="100" required>
            </div>

            <div class="form-group">
                <label for="email">Your Email</label>
                <input type="email" id="email" name="email"
                    placeholder="Enter your email"
                    value="<?= e($email) ?>" maxlength="150" required>
            </div>

            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject"
                    placeholder="Enter subject"
                    value="<?= e($subject) ?>" maxlength="200" required>
            </div>

            <div class="form-group">
                <label for="message">Your Message</label>
                <textarea id="message" name="message" rows="5"
                    placeholder="Write your message..."
                    required><?= e($message) ?></textarea>
            </div>

            <button type="submit">Send Message</button>
        </form>
    </div>

</div>
</body>
</html>