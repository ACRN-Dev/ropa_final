<?php

namespace App\Mail;

use App\Models\Ropa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShareRopaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ropa;
    public $subjectLine;
    public $attachmentPath;
    public $ccList;

    /**
     * Create a new message instance.
     */
    public function __construct(Ropa $ropa, $subjectLine, $attachmentPath, $ccList = [])
    {
        $this->ropa = $ropa;
        $this->subjectLine = $subjectLine;
        $this->attachmentPath = $attachmentPath;
        $this->ccList = $ccList;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $email = $this->subject($this->subjectLine)
                      ->view('emails.ropa-share')
                      ->with([
                          'ropa' => $this->ropa
                      ])
                      ->attach($this->attachmentPath);

        // Add CC if present
        if (!empty($this->ccList)) {
            $email->cc($this->ccList);
        }

        return $email;
    }
}
