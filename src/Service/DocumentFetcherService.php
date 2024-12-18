<?php
namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DocumentFetcherService
{
    private string $storageDir;

    public function __construct(ParameterBagInterface $params)
    {
        $this->storageDir = $params->get('document_storage_dir');

        if (!is_dir($this->storageDir)) {
            mkdir($this->storageDir, 0777, true);
        }
    }

    public function fetchDocuments(string $apiUrl): void
    {
        $httpClient = HttpClient::create();

        $response = $httpClient->request('GET', $apiUrl);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('Failed to fetch documents: ' . $response->getStatusCode());
        }

        $documents = $response->toArray();

        foreach ($documents as $document) {
            $this->processDocument($document);
        }
    }

    private function processDocument(array $document): void
    {
        if (!isset($document['certificate'], $document['description'], $document['doc_no'])) {
            throw new \InvalidArgumentException('Invalid document data. Missing required fields.');
        }

        $decodedFile = base64_decode($document['certificate']);

        if ($decodedFile === false) {
            throw new \RuntimeException('Failed to decode certificate for doc_no: ' . $document['doc_no']);
        }

        $filename = sprintf('%s_%s.pdf', $document['description'], $document['doc_no']);
        $filePath = $this->storageDir . DIRECTORY_SEPARATOR . $filename;

        file_put_contents($filePath, $decodedFile);
    }
}
