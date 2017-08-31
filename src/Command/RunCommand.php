<?php
namespace AlertMonitor\Command;

use AlertMonitor\Service\ConfigParser;
use AlertMonitor\Service\MonitorBuilder;
use AlertMonitor\Service\Output\ConsoleOutput;
use AlertMonitor\Service\Output\MailOutput;
use AlertMonitor\Service\Output\SmsOutput;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;
use AlertMonitor\Service\QueueService;

/**
 * Class RunCommand
 * @package AlertMonitor\Command
 */
class RunCommand extends Command
{
    /**
     * @const string
     */
    const OPTION_CONFIG_FILE = 'config_file';

    /**
     * {@parentDoc}
     */
    protected function configure()
    {
        $this->setName('start')
            ->addOption(
                static::OPTION_CONFIG_FILE,
                "c",
                InputOption::VALUE_REQUIRED,
                "Path to the config file (yaml) - relative to execution path"
            )
            ->setDescription('Start monitor amqp data');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configFile =  $input->getOption(static::OPTION_CONFIG_FILE);
        $configContent = $this->readConfig($configFile);
        $configParser = new ConfigParser($configContent);

        $consoleOutput = new ConsoleOutput($output);
        $mailOutput = new MailOutput(
            $configParser->getMailHost(),
            $configParser->getMailPort(),
            $configParser->getMailUser(),
            $configParser->getMailPassword(),
            $configParser->getMailFrom(),
            $configParser->getMailTo()
            );

        $smsOutput = new SmsOutput(
            $configParser->getCountry(),
            $configParser->getSmsFrom(),
            $configParser->getSmsTo(),
            $configParser->getSmsUrl(),
            $configParser->getSmsKey()
        );

        $monitorBuilder = new MonitorBuilder();

        $services = new QueueService($configContent, $configParser->getInterval(), $configParser->getTimes());
        $services->addOutputService($consoleOutput);
        $services->addOutputService($mailOutput);
        $services->addOutputService($smsOutput);
        foreach ($configParser->getMonitors() as $monitor) {
            $services->addMonitor($monitorBuilder->buildMonitor($monitor));
        }
        $services->monitor();
    }

    /**
     * Returns values from config yml in array
     * @param string $configFile
     * @return array
     */
    private function readConfig($configFile)
    {
        $configValues = array();
        $current_path = dirname(\Phar::running(false)) . DIRECTORY_SEPARATOR ;
        $configDirectories = array(
            $current_path,
            dirname($configFile)
        );

        $locator = new FileLocator($configDirectories);
        $yamlUserFiles = $locator->locate($configFile, null, false);

        foreach ($yamlUserFiles as $routes) {
            $configValues = Yaml::parse(file_get_contents($routes));
        }

        return $configValues;
    }
}