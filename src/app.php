<?php

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

require_once('bootstrap.php');

$app = new Silex\Application();

$app->register(new Silex\Extension\TwigExtension(), array(
    'twig.path'       => __DIR__.'/views',
    'twig.class_path' => __DIR__.'/../vendor/twig/lib',
));

// List people
$app->get('/', function () use ($app) {
    
    $document = simplexml_load_file(__DIR__.'/people.xml');
    $people = $document->xpath('//person');
    $peopleArray = array();
    
    foreach ($people as $person) {
        $array = array(
            'id' => $person->attributes()->id,
            'name' => $person->name,
            'image' => $person->image,
        );
        
        array_push($peopleArray, $array);
    }
    
    return $app['twig']->render('index.twig', array(
        'people' => $peopleArray,
    ));
    
});

// Person
$app->get('/{id}', function ($id) use ($app) {
    
    $document = simplexml_load_file(__DIR__.'/people.xml');
    $result = $document->xpath("//person[@id='{$id}']");
    
    if(! $result || empty($result)) {
        throw new NotFoundHttpException();
    }
    
    $person = $result[0];
    
    $quotes = array();
    foreach ($person->quotes->quote as $quote) {
        $quotes[] = (string)$quote;
    }
    
    $array = array(
        'id' => $person->attributes()->id,
        'name' => $person->name,
        'image' => $person->image,
        'service' => $person->service,
        'quotes' => $quotes
    );
    
    return $app['twig']->render('person.twig', array(
        'person' => $array,
    ));
    
});

return $app;