<?php

namespace App\Mail;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewPostMail extends Mailable
{
    use Queueable, SerializesModels;

    public $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function build()
    {
        $url = url("/posts/{$this->post->id}");

        return $this->subject('Neuer Post')
            ->html("Ein neuer Post wurde erstellt. <a href='{$url}'>Hier klicken, um den Post anzusehen.</a>");

    }
}
