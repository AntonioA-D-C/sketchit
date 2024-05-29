<?php

namespace App\Notifications;

use App\Models\comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class NewReply extends Notification
{
    use Queueable;

    protected $commentId;
    protected $reply;
    protected $postTitle;
    protected $user;
    protected $content;
    /**
     * Create a new notification instance.
     */
    public function __construct($commentId, $reply, $postTitle)
    {
        $this->commentId = $commentId;
        $this->reply = $reply;
        $this->postTitle = $postTitle;

        $this->user = comment::find($this->reply->id)->user;
     $this->content = substr($this->reply->content, 0, 90);
     if(strlen($this->reply->content)>90){
        $this->content = $this->content."...";
     }

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
  /*  public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }
*/
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable)
    {
       
   
       
        return [
      
          'comment_id'=>$this->commentId,
          'reply_id'=>$this->reply->id,
          'message'=>$this->user->name." has responded to your comment in ".$this->postTitle
        ];
  
    }
}
