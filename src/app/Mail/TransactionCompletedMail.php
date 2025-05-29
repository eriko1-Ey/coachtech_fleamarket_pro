<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Chat;

class TransactionCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $chat;

    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    public function build()
    {
        return $this->subject('取引が完了しました')
            ->view('emails.transaction_completed')
            ->with([
                'chat' => $this->chat,
            ]);
    }
}
