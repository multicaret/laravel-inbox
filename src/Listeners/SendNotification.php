<?php

namespace Multicaret\Inbox\Listeners;

use Multicaret\Inbox\Notifications\MessageDispatched;

class SendNotification
{
    /**
     * Handle the event.
     *
     * @param  mixed $event
     *
     * @return void
     */
    public function handle($event)
    {
        $thread = $event->thread;
        $message = $event->message;

        $participants = $thread->participants()
                               ->where('user_id', '!=', $message->user_id)
                               ->get();

        if ($participants->count()) {
            foreach ($participants as $participant) {
                $participant->notify(new MessageDispatched($thread, $message, $participant));
            }
        }
    }
}
