<?php

namespace App\Helpers;

use App\Helpers\Text\Translate;
use Exception;

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
            'From' => "PlatformDismac <platformdismac@grazcompany.com>",
            'Reply-To' => "platformdismac@grazcompany.com",
            'X-Mailer' => 'PHP/' . phpversion()
        ];
    }

    public function createMail() {
        try {
            ini_set($this->translate->getDisplayError(), 1 );
            error_reporting( E_ALL );

            return mail($this->to, $this->title, (string)$this->message, $this->headers);
        } catch (Exception $th) {
            return false;
        }
    }
}

?>