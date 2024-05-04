<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CommentProjectNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->comment->departure()->first()->project()->first()->id,
            'user_id' => $this->comment->user_id,
            'project_title' => $this->comment->departure()->first()->project()->first()->code . ' - ' . $this->comment->departure()->first()->project()->first()->title,
            'publication_date' => \Carbon\Carbon::now()->format('d/m/Y'),
            'message' => 'El usuario ' . $this->comment->user()->first()->first_name . ' ' . $this->comment->user()->first()->last_name . ' de la empresa ' . $this->comment->user()->first()->company_name . ' ha comentado en la partida ' . $this->comment->departure()->first()->code . ' de tu proyecto.',
            'type' => 2 // New Comment
        ];
    }
}
