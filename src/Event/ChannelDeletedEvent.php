<?php

namespace ESportsAlliance\TeamSpeakBundle\Event;


class ChannelDeletedEvent extends NotifyEvent
{

    public static function getName()
    {
        return self::CHANNEL_DELETED;
    }
}
