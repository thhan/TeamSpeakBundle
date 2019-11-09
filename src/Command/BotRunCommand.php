<?php

namespace ESportsAlliance\TeamSpeakBundle\Command;


use ESA\TeamSpeakBundle\Event\ChannelCreatedEvent;
use ESA\TeamSpeakBundle\Event\ChannelDeletedEvent;
use ESA\TeamSpeakBundle\Event\ChannelMovedEvent;
use ESA\TeamSpeakBundle\Event\ClientEnterViewEvent;
use ESA\TeamSpeakBundle\Event\ClientLeftViewEvent;
use ESA\TeamSpeakBundle\Event\ClientMovedEvent;
use ESA\TeamSpeakBundle\Event\NotifyEvent;
use ESA\TeamSpeakBundle\Event\ServerSelectedEvent;
use ESA\TeamSpeakBundle\Event\TextMessageEvent;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class BotRunCommand extends ContainerAwareCommand
{
    /**
     * @var InputInterface
     */
    protected static $input;

    /**
     * @var OutputInterface
     */
    protected static $output;

    /**
     * @var string
     */
    protected static $pidFile;

    /**
     * @var EventDispatcher
     */
    protected static $dispatcher;

    protected static $last;

    protected function configure()
    {
        $this->setName('teamspeak:bot:run')->setDescription("Start TeamSpeak-Bot.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        self::$input      = $input;
        self::$output     = $output;
        self::$dispatcher = $this->getContainer()->get('event_dispatcher');
        self::$pidFile    = sprintf("%s/../.teamspeak-bot.pid", $this->getContainer()->get('kernel')->getRootDir());

        $class = get_class($this);

        \TeamSpeak3::init();
        \TeamSpeak3_Helper_Signal::getInstance()->subscribe("notifyServerselected", "$class::onSelect");
        \TeamSpeak3_Helper_Signal::getInstance()->subscribe("notifyEvent", "$class::onEvent");
        \TeamSpeak3_Helper_Signal::getInstance()->subscribe("serverqueryWaitTimeout", "$class::onTimeout");

        /**
         * @var $teamSpeak \TeamSpeak3_Node_Host
         */
        $teamSpeak = $this->getContainer()->get('esa_team_speak')->getTeamSpeakInstance();

        while (file_exists(self::$pidFile)) {
            try {
                $teamSpeak->getAdapter()->wait();
            } catch (\Exception $e) {
                echo sprintf("[%s] [%s:%s   ] - %s ", date("H:i:s"), $e->getFile(), $e->getLine(), $e->getMessage());
                sleep(30);
            }
        }
    }

    public static function onEvent(\TeamSpeak3_Adapter_ServerQuery_Event $event, \TeamSpeak3_Node_Host $host)
    {
        if (self::$last == serialize($event)) {
            return;
        }
        self::$last = serialize($event);

        self::$dispatcher->dispatch(NotifyEvent::getName(), new NotifyEvent($event, $host));

        switch ($event->getType()) {
            case "channelcreated":
                self::$dispatcher->dispatch(ChannelCreatedEvent::getName(), new ChannelCreatedEvent($event, $host));
                break;
            case "channelmoved":
                self::$dispatcher->dispatch(ChannelMovedEvent::getName(), new ChannelMovedEvent($event, $host));
                break;
            case "channeldeleted":
                self::$dispatcher->dispatch(ChannelDeletedEvent::getName(), new ChannelDeletedEvent($event, $host));
                break;
            case 'cliententerview':
                self::$dispatcher->dispatch(ClientEnterViewEvent::getName(),
                    new ClientEnterViewEvent($event, $host));
                break;
            case 'clientleftview':
                self::$dispatcher->dispatch(ClientLeftViewEvent::getName(), new ClientLeftViewEvent($event, $host));
                break;
            case "clientmoved":
                self::$dispatcher->dispatch(ClientMovedEvent::getName(), new ClientMovedEvent($event, $host));
                break;
            case "textmessage":
                if ($event->getData()["invokerid"] != $host->whoami()["client_id"]) {
                    self::$dispatcher->dispatch(TextMessageEvent::getName(), new TextMessageEvent($event, $host));
                }
                break;
            default:
                break;
        }

    }

    public static function onSelect(\TeamSpeak3_Node_Host $host)
    {
        self::$dispatcher->dispatch(ServerSelectedEvent::getName(), new ServerSelectedEvent($host));

        $host->serverGetSelected()->notifyRegister("server");
        $host->serverGetSelected()->notifyRegister("channel");
        $host->serverGetSelected()->notifyRegister("textserver");
        $host->serverGetSelected()->notifyRegister("textchannel");
        $host->serverGetSelected()->notifyRegister("textprivate");
    }

    /**
     * @param int $timeout
     * @param \TeamSpeak3_Adapter_ServerQuery $serverQuery
     */
    public static function onTimeout($timeout, \TeamSpeak3_Adapter_ServerQuery $serverQuery)
    {

        if (false === file_exists(self::$pidFile)) {
            die();
        }

        if ($serverQuery->getQueryLastTimestamp() < time() - 290) {
            $serverQuery->request("clientupdate");
        }
    }
}
