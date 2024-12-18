<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Service\DocumentFetcherService;

#[AsCommand(name: "app:my", description: "Fetch documents from API and store them locally.")]
class MyCommand extends Command
{

    private DocumentFetcherService $documentFetcherService;

    public function __construct(DocumentFetcherService $documentFetcherService)
    {
        parent::__construct();
        $this->documentFetcherService = $documentFetcherService;
    }

    protected function configure(): void
    {
        $this->setDescription('Fetch documents from API and store them locally.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Fetching documents...</info>');
        $apiUrl = 'https://raw.githubusercontent.com/RashitKhamidullin/Educhain-Assignment/refs/heads/main/get-documents'; 

        $result = $this->documentFetcherService->fetchDocuments($apiUrl);

        if (isset($result['error'])) {
            $output->writeln('<error>' . $result['error'] . '</error>');
            return Command::FAILURE;
        }

        $output->writeln('<info>Completed.... You can now verify the files in the declared directory path.</info>');

        return Command::SUCCESS;
    }
}
