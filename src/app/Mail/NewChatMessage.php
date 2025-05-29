<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewChatMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function build()
    {
        return $this->subject('新しいチャットメッセージがあります')
            ->view('emails.new_chat_message')
            ->with([
                'chatMessage' => $this->message
            ]);
    }
}
