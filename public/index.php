<?php

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ERROR);

$program = new \App\Program();
$program->run();
