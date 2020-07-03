<?php

namespace Multicaret\Inbox;

trait EventMap
{
    /**
     * All of the Inbox event / listener mappings.
     *
     * @var array
     */
    protected $events = [
        Events\NewMessageDispatched::class => [
            Listeners\SendNotification::class,
        ],

        Events\NewReplyDispatched::class => [
            Listeners\SendNotification::class,
        ],
    ];
}
