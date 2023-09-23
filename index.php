<?php

require(__DIR__ . '/vendor/autoload.php');

use Appwrite\Client;
use Appwrite\Exception;
use Appwrite\Services\Databases;


return function ($context) {
    $html = '<p>Test Html Data</p>';

  if ($context->req->method === 'GET') {
    return $context->res->send($html);
  }

  if ($context->req->method === 'POST' && $context->req->headers['content-type'] === 'application/x-www-form-urlencoded') {
    \parse_str($context->req->body, $formData);
    
    $message = [
      'name' => $formData['name'],
      'email' => $formData['email'],
      'content' => $formData['content']
    ];

    $client = new Client();
    $client
      ->setEndpoint('https://cloud.appwrite.io/v1')
      ->setProject(getenv('APPWRITE_FUNCTION_PROJECT_ID'))
      ->setKey(getenv('APPWRITE_API_KEY'));

    $databases = new Databases($client);
    $document = $databases->createDocument('[DATABASE_ID]', '[MESSAGES_COLLECTION_ID]', ID::unique(), $message);

    return $context->res->send("Message sent");
  }

  return $context->res->send('Not found', 404);
};