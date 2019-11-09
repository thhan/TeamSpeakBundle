<?php

namespace ESportsAlliance\TeamSpeakBundle\Event;


class ChannelCreatedEvent extends NotifyEvent
{
    public static function getName()
    {
        return self::CHANNEL_CREATED;
    }
}
