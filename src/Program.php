<?php

namespace App;

use App\Graph\GraphFactory;
use Exception;

class Program
{
    /**
     * @return void
     */
    public function run(): void
    {
        try {
            // Fetch url and extract target column data
            $defaultUrl = 'https://en.wikipedia.org/wiki/Women%27s_high_jump_world_record_progression';
            if (php_sapi_name() === 'cli') {
                $url = $_SERVER['argv'][1] ?? $defaultUrl;
            } else {
                $url = $_GET['url'] ?? $defaultUrl;
            }

            // Extract data
            $extractor = new Extractor();
            $data = $extractor->fetch($url);

            // Fill dataset with empty label to draw graph
            $data = array_map(function ($value) {
                return ['', $value];
            }, $data);

            // Draw a graph
            GraphFactory::create(GraphFactory::BAR)->setData($data)->draw();
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }
}