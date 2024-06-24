<?php
defined('BASE') or header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require BASE . '/Core/PHPMailer/PHPMailer.php';
require BASE . '/Core/PHPMailer/SMTP.php';
require BASE . '/Core/PHPMailer/Exception.php';
class Mailer {

    public string $toName = '';
    public string $toAddress = '';
    public string $fromName = 'Yusuf Mambrasar';
    public string $fromAddress = 'helo@yusufmambrasar.id';
    public string $subject = '';
    public string $message = '';
    public string $error = '';

    public function send()
    {
        $mail = new PHPMailer(true);
        try 
        {
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = 'mail.yusufmambrasar.id';
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet   = "UTF-8";
            $mail->Username = $this->fromAddress;
            $mail->Password = 'dPy@Vfr*2Sc$';

            $mail->setFrom($this->fromAddress,$this->fromName);
            $mail->addReplyTo($this->fromAddress,$this->fromName);
            $mail->addAddress($this->toAddress,$this->toName);
            $mail->isHTML(true); 
            $mail->Subject = $this->subject;
            $mail->Body = $this->message;
            if($mail->send())
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        } 
        catch (Exception $e) 
        {
            $this->error = $mail->ErrorInfo;
            return FALSE;
        }
    }

}