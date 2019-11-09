<?php

namespace ESportsAlliance\TeamSpeakBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class BotStartCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('teamspeak:bot:start')->setDescription("Start TeamSpeak-Bot in background.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $appRoot = $this->getContainer()->get('kernel')->getRootDir();
        $pidFile = sprintf("%s/../.teamspeak-bot.pid", $appRoot);
        $cmd     = sprintf("php %s/../bin/console teamspeak:bot:run", $appRoot);

        if (true === file_exists($pidFile)) {
            throw new RuntimeException(sprintf("A teamspeak-bot is already running"));
        }

        $process = new Process($cmd);
        $process->disableOutput();
        $process->setTimeout(null);
        $process->start();
        $pid = $process->getPid();

        if (false === $process->isRunning()) {
            throw new RuntimeException(sprintf("TeamSpeak-Bot couldn't start."));
        }
        file_put_contents($pidFile, $pid);
        $output->writeln("TeamSpeak-bot successfully started.");
    }
}
