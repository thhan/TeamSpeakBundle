<?php

namespace ESportsAlliance\TeamSpeakBundle\Event;


class NotifyEvent extends AbstractTeamSpeakEvent
{

    /**
     * @var \TeamSpeak3_Adapter_ServerQuery_Event
     */
    protected $event;

    /**
     * ClientEnterViewEvent constructor.
     *
     * @param \TeamSpeak3_Adapter_ServerQuery_Event $event
     * @param \TeamSpeak3_Node_Host $host
     */
    public function __construct(\TeamSpeak3_Adapter_ServerQuery_Event $event, \TeamSpeak3_Node_Host $host)
    {
        $this->event = $event;
        $this->host  = $host;
    }

    /**
     * @return \TeamSpeak3_Adapter_ServerQuery_Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    public static function getName()
    {
        return self::NOTIFY;
    }
}
