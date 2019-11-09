<?php

namespace ESportsAlliance\TeamSpeakBundle\Event;


class ServerqueryWaitTimeoutEvent extends NotifyEvent
{
    public static function getName()
    {
        return self::SERVERQUERY_WAIT_TIMEOUT;
    }
}
