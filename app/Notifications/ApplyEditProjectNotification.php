<?php

namespace App\Notifications;

use App\Models\Variant;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ApplyEditProjectNotification extends Notification
{
    use Queueable;

    public $message;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Variant $variant)
    {
        $this->variant = $variant;
        //$this->message = $message;
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
            'id' => $this->variant->departure()->first()->project()->first()->id,
            'user_id' => $this->variant->user_id,
            'project_title' => $this->variant->departure()->first()->project()->first()->code . ' - ' . $this->variant->departure()->first()->project()->first()->title,
            'publication_date' => \Carbon\Carbon::now()->format('d/m/Y'),
            'message' => 'El usuario ' . $this->variant->user()->first()->first_name . ' ' . $this->variant->user()->first()->last_name . ' de la empresa ' . $this->variant->user()->first()->company_name . ' ha editado la aplicación de la partida ' . $this->variant->departure()->first()->code . ' de tu proyecto.',
            //'message' => $this->message, //'El usuario ' . $this->variant->user()->first()->first_name . ' ' . $this->variant->user()->first()->last_name . ' de la empresa ' . $this->variant->user()->first()->company_name . ' ha editado la aplicación de la partida ' . $this->variant->departure()->first()->code . ' de tu proyecto.',
            'type' => 4 // Apply Edit Project
        ];
    }
}
