<?php

namespace ESportsAlliance\TeamSpeakBundle\Event;


class ClientLeftViewEvent extends NotifyEvent
{
    public static function getName()
    {
        return self::CLIENT_LEFT_VIEW;
    }
}
