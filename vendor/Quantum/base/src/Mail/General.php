<?php

namespace Quantum\base\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class General extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var
     */
    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->data['template'])->subject($this->data['subject'])
            ->from($this->data['from'], $this->data['from_name'])
            ->with([
                'content_html' => $this->data['content_html'],
                'content_text' => $this->data['content_text']
            ]);
    }
}
