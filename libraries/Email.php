<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email {
    public static function send($to, $subject, $body, $isHtml = false) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Configure
            $mail->SMTPAuth = true;
            $mail->Username = 'user@example.com';
            $mail->Password = 'password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('from@example.com', 'School Management');
            $mail->addAddress($to);

            // Content
            $mail->isHTML($isHtml);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function sendNotification($userEmail, $type, $data) {
        $subject = '';
        $body = '';

        switch ($type) {
            case 'fee_due':
                $subject = 'Fee Payment Reminder';
                $body = "Dear student, your fee payment is due. Amount: {$data['amount']}";
                break;
            case 'exam_result':
                $subject = 'Exam Results Published';
                $body = "Your exam results are now available. Check your portal.";
                break;
            // Add more types
        }

        return self::send($userEmail, $subject, $body);
    }
}