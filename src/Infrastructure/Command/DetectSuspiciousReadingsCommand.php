<?php

namespace App\Infrastructure\Command;

// Factory: Alternative approach using a factory to retrieve readers
// use App\Infrastructure\Reader\Factory\ReaderFactory;

use App\Application\ReaderService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

#[AsCommand(name: 'app:detect-suspicious-readings')]
class DetectSuspiciousReadingsCommand extends Command
{
    private ReaderService $readerService;

    /**
     * Constructor to inject the ReaderService dependency.
     *
     * @param ReaderService $readerService Service responsible for handling suspicious reading detection logic.
     */
    public function __construct(ReaderService $readerService)
    {
        parent::__construct();
        $this->readerService = $readerService;
    }

    // Factory: Alternative approach using a factory to retrieve readers
    // public function __construct()
    // {
    //     parent::__construct();
    //     $this->readerService = new ReaderService(ReaderFactory::getReaders());
    // }

    /**
     * Configures the command details, including its description and required arguments.
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Detects suspicious readings in a file')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to the readings file.');
    }

    /**
     * Executes the command, processes the input file, and outputs suspicious readings in a table format.
     *
     * @param InputInterface $input The input interface for retrieving command arguments.
     * @param OutputInterface $output The output interface for displaying results.
     * @return int Returns Command::SUCCESS if execution completes successfully.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getArgument('file');

        // Process the file and detect suspicious readings
        $suspiciousReadings = $this->readerService->detectSuspiciousReadings($filePath);

        // Prepare and render the output table
        $table = new Table($output);
        $table->setHeaders(['Client', 'Month', 'Suspicious', 'Median']);

        foreach ($suspiciousReadings as $reading) {
            $table->addRow($reading);
        }

        $table->render();
        return Command::SUCCESS;
    }

}
