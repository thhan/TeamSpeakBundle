<?php

namespace ESportsAlliance\TeamSpeakBundle\Event;


class TextMessageEvent extends NotifyEvent
{
    public static function getName()
    {
        return self::TEXT_MESSAGE;
    }
}
