<?php

namespace App\Graph\Types;

use App\Graph\AbstractGraph;

class Bar extends AbstractGraph
{
    /**
     * @return void
     */
    public function draw()
    {
        $this->client->SetImageBorderType('plain');

        $this->client->SetPlotType('bars');
        $this->client->SetDataType('text-data');
        $this->client->SetDataValues($this->data);

        // No 3-D shading of the bars:
        $this->client->SetShading(0);

        // Turn off X tick labels and ticks because they don't apply here:
        $this->client->SetXTickLabelPos('none');
        $this->client->SetXTickPos('none');

        // Export file if cli. Otherwise, display image
        if (php_sapi_name() === 'cli') {
            $this->client->SetIsInline(true);
            $this->client->SetOutputFile(getcwd() . '/public/images/graph.png');
        }

        $this->client->DrawGraph();
    }
}