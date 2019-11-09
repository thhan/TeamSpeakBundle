<?php

namespace ESportsAlliance\TeamSpeakBundle\Event;


class ClientEnterViewEvent extends NotifyEvent
{
    public static function getName()
    {
        return self::CLIENT_ENTER_VIEW;
    }
}
