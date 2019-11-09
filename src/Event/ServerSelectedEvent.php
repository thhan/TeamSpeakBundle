<?php

namespace ESportsAlliance\TeamSpeakBundle\Event;


class ServerSelectedEvent extends AbstractTeamSpeakEvent
{
    public static function getName()
    {
        return self::SERVER_SELECTED;
    }
}
