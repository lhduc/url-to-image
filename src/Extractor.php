<?php

namespace App;

use Exception;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;

class Extractor
{
    private HttpBrowser $client;
    private Crawler $crawler;
    private array $cols = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->setClient(new HttpBrowser());
    }

    /**
     * @param HttpBrowser $client
     */
    public function setClient(HttpBrowser $client): void
    {
        $this->client = $client;
    }

    /**
     * @param string $url
     * @return array
     * @throws Exception
     */
    public function fetch(string $url): array
    {
        try {
            $this->crawler = $this->client->request('GET', $url);
        } catch (Exception) {
            throw new Exception('Invalid URL.');
        }

        // Find first table matching with selector
        return $this->crawl();
    }

    /**
     * This crawl and store table column values if selector exist.
     *
     * @param int $position
     * @return array
     * @throws Exception
     */
    private function crawl(int $position = 0): array
    {
        // Find first table matching with selector
        $table = $this->crawler->filter('table')->eq($position);
        if (!$table->count()) {
            throw new Exception('Matching table is unavailable.');
        }

        // Store headers to map column value by headers
        $headers = [];
        $table->filter('th')->each(function ($h) use (&$headers) {
            $headers[] = trim($h->text());
        });

        // Mapping col value by headers to easily retrieve data of columns
        $this->cols = [];
        foreach ($headers as $k => $header) {
            $table->filter('tr > td:nth-child(' . ($k + 1) . ')')
                ->each(function (Crawler $r) use ($header) {
                    $this->cols[$header][] = $r->text();
                });
        }

        // Check matching column with condition
        $data = $this->getTargetColumn();
        if (!$data) {
            return $this->crawl($position + 1);
        }

        return $data;
    }

    /**
     * @return array
     */
    private function getTargetColumn(): array
    {
        // This pattern for filter numeric string like "12.4 m"
        $pattern = '/^([\d,.]+)(\sm)*\s*/';

        // Find first column having only numeric values
        foreach ($this->cols as $colValues) {
            // Loop all values in column to make sure all values are numeric
            $isNumeric = true;
            $values = [];
            foreach ($colValues as $value) {
                preg_match($pattern, $value, $matches);
                if (!isset($matches[1])) {
                    $isNumeric = false;
                    break;
                }
                $values[] = $matches[1];
            }

            if ($isNumeric) {
                return $values;
            }
        }

        return [];
    }
}