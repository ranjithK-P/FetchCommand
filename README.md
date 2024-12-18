# Fetch Documents Command

This README provides guidance on setting up and using the `app:my` command in your Symfony application.

## Overview
The `app:my` command is a custom Symfony console command designed to fetch documents from a specified storage directory. This guide assumes you have already created the command class `App\Command\FetchDocumentsCommand` and registered it as a service.

## Prerequisites

- Symfony 6.4 or higher
- PHP 8.2 or higher
- Properly configured `config/services.yaml` file
- A functional Symfony project

## Installation

1. **Create the Command Class**

   Ensure the command class exists at `src/Command/MyCommand.php`:

   ```php
   namespace App\Command;

  use Symfony\Component\Console\Attribute\AsCommand;
  use Symfony\Component\Console\Command\Command;
  use Symfony\Component\Console\Input\InputInterface;
  use Symfony\Component\Console\Output\OutputInterface;
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
        // fetch logic here..
    }
}

   ```

2. **Register the Command as a Service**

   Add the following configuration to your `config/services.yaml` file:

   ```yaml
   parameters:
    document_storage_dir: '%kernel.project_dir%/var/documents'
   ```

3. **Clear Cache**

   After creating or updating the command, clear the Symfony cache to ensure it is recognized:

   ```bash
   php bin/console cache:clear
   ```

4. **Verify Autoloading**

   Ensure your autoloading configuration in `composer.json` is correct:

   ```json
   {
       "autoload": {
           "psr-4": {
               "App\\": "src/"
           }
       }
   }
   ```

   Then, update autoloading:

   ```bash
   composer dump-autoload
   ```

## Usage

Run the command using the Symfony Console:

```bash
php bin/console app:my
```

If the command runs successfully, it will display the message `Fetching documents...` in the console.

## Troubleshooting

### Error: "There are no commands defined in the \"app\" namespace."

1. Ensure the namespace and `defaultName` of the command class match.
2. Verify the command registration in `config/services.yaml`.
3. Clear the Symfony cache:

   ```bash
   php bin/console cache:clear
   ```

4. Regenerate the Composer autoload files:

   ```bash
   composer dump-autoload
   ```

5. Confirm the command file exists at `src/Command/MyCommand.php`.

## Notes

- Make sure the `fetch:documents` command logic is implemented as per your application requirements.
- Check Symfony logs for additional error details if the command does not work as expected.

## Support

For further assistance, refer to the [Symfony documentation](https://symfony.com/doc) or consult the Symfony community forums.

