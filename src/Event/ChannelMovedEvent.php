<?php

namespace ESportsAlliance\TeamSpeakBundle\Event;


class ChannelMovedEvent extends NotifyEvent
{
    public static function getName()
    {
        return self::CHANNEL_MOVED;
    }
}
