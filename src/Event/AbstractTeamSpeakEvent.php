<?php

namespace ESportsAlliance\TeamSpeakBundle\Event;


use Symfony\Component\EventDispatcher\Event;

abstract class AbstractTeamSpeakEvent extends Event
{
    const CHANNEL_CREATED          = "teamspeak.channel_created";
    const CHANNEL_DELETED          = "teamspeak.channel_deleted";
    const CHANNEL_MOVED            = "teamspeak.channel_moved";
    const CLIENT_ENTER_VIEW        = "teamspeak.client_enter_view";
    const CLIENT_LEFT_VIEW         = "teamspeak.client_left_view";
    const CLIENT_MOVED             = "teamspeak.client_moved";
    const NOTIFY                   = "teamspeak.notify";
    const TEXT_MESSAGE             = "teamspeak.text_message";
    const SERVER_SELECTED          = "teamspeak.server_selected";
    const SERVERQUERY_WAIT_TIMEOUT = "teamspeak.serverquery_wait_timeout";

    /**
     * @var \TeamSpeak3_Node_Host
     */
    protected $host;

    /**
     * ClientEnterViewEvent constructor.
     *
     * @param \TeamSpeak3_Node_Host $host
     */
    public function __construct(\TeamSpeak3_Node_Host $host)
    {
        $this->host = $host;
    }

    /**
     * @return \TeamSpeak3_Node_Host
     */
    public function getHost()
    {
        return $this->host;
    }
}
