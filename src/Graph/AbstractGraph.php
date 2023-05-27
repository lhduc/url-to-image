<?php

namespace App\Graph;

use PHPlot;

abstract class AbstractGraph implements GraphInterface
{
    /**
     * @var PHPlot
     */
    protected PHPlot $client;

    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @param int $width
     * @param int $height
     */
    public function __construct(int $width, int $height)
    {
        $this->client = new PHPlot($width, $height);
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data = []): GraphInterface
    {
        $this->data = $data;

        return $this;
    }
}