<?php

$app = require_once __DIR__.'/../src/app.php';

try {
    $app->run();
}
catch(Exception $e) {
    
}