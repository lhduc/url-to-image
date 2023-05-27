<?php

namespace App\Graph;

use App\Graph\Types\Bar;
use Exception;

class GraphFactory
{
    const BAR = 'bar';

    /**
     * @param string $type
     * @param int|null $width
     * @param int|null $height
     * @return GraphInterface
     * @throws Exception
     */
    public static function create(string $type, int $width = null, int $height = null): GraphInterface
    {
        $width = !empty($width) ? $width : 800;
        $height = !empty($height) ? $height : 600;

        switch ($type) {
            case static::BAR:
                return new Bar($width, $height);
            default:
                throw new Exception('Graph type is not supported.');
        }
    }
}