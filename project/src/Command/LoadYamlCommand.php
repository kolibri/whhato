<?php

namespace App\Command;

use App\Whhato\YamlLoader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadYamlCommand extends Command
{
    protected static $defaultName = 'whhato:load:yaml';

    private $defaultDataPath;
    private $entityManager;

    public function __construct(string $dataPath, EntityManagerInterface $entityManager)
    {
        $this->defaultDataPath = $dataPath;
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('path', InputArgument::OPTIONAL, 'path to yaml file dir', $this->defaultDataPath);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $messages = YamlLoader::loadDataPath($input->getArgument('path'));

        foreach ($messages as $monthDay => $messages) {
            foreach ($messages as $message) {
                $this->entityManager->persist($message);
                $output->writeln(sprintf('Saved "%s" to db', $message->getRawMessage()));
            }
        }

        $this->entityManager->flush();
    }
}
