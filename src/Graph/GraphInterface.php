<?php

namespace App\Graph;

interface GraphInterface
{
    /**
     * @param array $data
     * @return GraphInterface
     */
    public function setData(array $data = []): GraphInterface;

    /**
     * @return void
     */
    public function draw();
}