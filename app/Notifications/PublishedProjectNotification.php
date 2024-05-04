<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PublishedProjectNotification extends Notification
{
    use Queueable;

    public $category;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Project $project, $category)
    {
       $this->project = $project; 
       $this->category = $category; 
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        //return ['mail'];
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
            'id' => $this->project->id,
            'user_id' => $this->project->user_id,
            'project_title' => $this->project->code . ' - ' . $this->project->title,
            'publication_date' => \Carbon\Carbon::now()->format('d/m/Y'),
            'message' => 'Se ha publicado nuevo proyecto con categoría ' . $this->category,
            'type' => 1 // New Project
        ];
    }
}
