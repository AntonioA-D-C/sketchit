<?php

namespace App\Notifications;

use App\Models\comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CommentLike extends Notification
{
    use Queueable;


    protected $commentId;
    protected $post;
    protected $user;
    /**
     * Create a new notification instance.
     */
    public function __construct($commentId, $post)
    {
        $this->commentId = $commentId;
        $this->post = $post;

        $this->user = comment::find($this->commentId)->user;
 

    }
    

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        
        return ['database'];
    }
    /**
     * Get the mail representation of the notification.
     */
 
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable)
    {
       
   
       
        return [
      
          'reply_id'=>$this->commentId,
          'message'=>$this->user->name." has liked your comment in ".$this->post->title
        ];
  
    }
}
