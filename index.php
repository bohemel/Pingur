<?php

$loader = require 'vendor/autoload.php';

$app = new \Slim\Slim(array(
  'debug' => true
));
  
$app->post('/upload', function() use ($app) {
  $images = \Pingur\Image::createFromFilesArray();
  if ($images->size() > 0) {
    $res = $app->response();
    $res['Content-Type'] = 'application/json';
    echo $images->jsonEncode();
  }
  else {
    $app->response()->status(405);
  }
});

$app->get('/', function() use ($app) {
  $images = \Pingur\Image::search();
  $app->render('index.tpl.php', array('images' => $images));
});

$app->get('/:uid', function ($uid) {
  echo "$uid";
});

$app->run();
