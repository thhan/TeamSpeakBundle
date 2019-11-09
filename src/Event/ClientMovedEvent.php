<?php

namespace ESportsAlliance\TeamSpeakBundle\Event;


class ClientMovedEvent extends NotifyEvent
{
    public static function getName()
    {
        return self::CLIENT_MOVED;
    }
}
