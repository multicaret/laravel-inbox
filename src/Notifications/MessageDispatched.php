<?php

namespace Multicaret\Inbox\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Multicaret\Inbox\Models\Message;
use Multicaret\Inbox\Models\Thread;

class MessageDispatched extends Notification
{
    use Queueable;

    public $thread, $message, $participant;

    /**
     * Create a new notification instance.
     *
     * @param Thread  $thread
     * @param Message $message
     * @param         $participant
     */
    public function __construct($thread, $message, $participant)
    {
        $this->thread = $thread;
        $this->message = $message;
        $this->participant = $participant;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return config('inbox.notifications.via', [
            'mail'
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'thread_id' => $this->thread->id,
            'message_id' => $this->message->id,
            'isReply' => $this->thread->messages()->count() >= 2,
        ];
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'thread_id' => $this->thread->id,
            'message_id' => $this->message->id,
            'isReply' => $this->thread->messages()->count() >= 2,
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     * @throws \Throwable
     */
    public function toMail($notifiable)
    {
        $buttonUrl = route(config('inbox.route.name') . 'inbox.show', $this->thread);
        $isReply = $this->thread->messages()->count() >= 2;
        $greeting = $isReply ? 'Re: ' . $this->thread->subject : $this->thread->subject;

        return (new MailMessage)
            ->success()
            ->subject($this->message->user->name . ' ' . trans('inbox::messages.notification.subject') . ' - ' . config('app.name'))
            ->greeting($greeting)
            ->line($this->message->body)
            ->action(trans('inbox::messages.notification.button'), $buttonUrl);
    }
}
