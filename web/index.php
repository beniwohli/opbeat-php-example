<?php

require('../vendor/autoload.php');

$organization_id = getenv('OPBEAT_ORGANIZATION_ID');
$application_id = getenv('OPBEAT_APP_ID');
$secret_token = getenv('OPBEAT_SECRET_TOKEN');

class New_Opbeat_Client extends Opbeat_Client {
  protected $default_endpoint = 'https://opbeat.com/api/v1/';
}

if ($organization_id && $application_id && $secret_token){
  $client = new New_Opbeat_Client($organization_id, $application_id, $secret_token);
  $handler = new Opbeat_Handler();
  $handler->addClient($client);
  $handler->registerErrorHandler();
  $handler->registerExceptionHandler();
}

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Our web handlers

$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return 'Hello';
});

$app->get('/error', function() use($app) {
    throw new Exception('Oh.');
});

$app->run();

?>
