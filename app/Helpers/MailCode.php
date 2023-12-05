<?php

namespace App\Helpers;

use App\Helpers\Text\Translate;
use Exception;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;

class MailCode
{
    protected $to = "";
    protected $title = "";
    protected $message = "";
    protected $headers = [];
    /**
     * @var Translate
     */
    protected $translate;

    public function __construct(
        string $to,
        string $title,
        string $message
    ) {
        $this->translate = new Translate();
        $this->to = $to;
        $this->title = $title;
        $this->message = view('mail.account.validate', ['code' => $message]);

        $this->headers = [
            'MIME-Version' => 'MIME-Version: 1.0',
            'Content-type' => 'text/html; charset=UTF-8',
            'From' => "NotifyAPP <notify@grazcompany.com>",
            'Reply-To' => "notify@grazcompany.com",
            'X-Mailer' => 'PHP/' . phpversion()
        ];
    }

    public function createMail() {
        try {
            ini_set($this->translate->getDisplayError(), 1 );
            error_reporting( E_ALL );

            return mail($this->to, $this->title, (string)$this->message, $this->headers);
        } catch (Exception $e) {
            return false;
        }

        try {
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->SMTPDebug = 2;
            $mail->Host = 'smtp.hostinger.com';
            $mail->Port = 465;
            $mail->SMTPAuth = true;
            $mail->Username = 'notify@grazcompany.com';
            $mail->Password = 'Abel$123456';
            $mail->setFrom('notify@grazcompany.com', 'Notify APP');
            $mail->addReplyTo('notify@grazcompany.com', 'Notify APP');
            $mail->addAddress($this->to, 'UserName');
            $mail->Subject = 'Checking if PHPMailer works';
            $mail->msgHTML((string)$this->message);
            $mail->Body = 'This is just a plain text message body';
            //$mail->addAttachment('attachment.txt');
            if (!$mail->send()) {
                Log::info('Mailer Error: ' . $mail->ErrorInfo);
                return false;
            } else {
                Log::info('The email message was sent.');
                return true;
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return false;
        }
    }
}

?>