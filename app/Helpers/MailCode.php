<?php

namespace App\Helpers;

use App\Helpers\Text\Translate;
use Exception;
use Illuminate\Support\Facades\Log;

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

            Log::info(json_encode($this->to));
            Log::info(json_encode($this->title));
            Log::info(json_encode((string)$this->message));
            Log::info(json_encode($this->headers));
            Log::info("Send mail.");
            return mail($this->to, $this->title, (string)$this->message, $this->headers);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return false;
        }
    }
}

?>