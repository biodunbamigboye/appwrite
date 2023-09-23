<?php

require(__DIR__ . '/vendor/autoload.php');

use Appwrite\Client;
use Appwrite\Exception;
use Appwrite\Services\Databases;

$html = '<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Contact Form</title>
  </head>
  <body>
    <form action="/" method="POST">
      <input type="text" id="name" name="name" placeholder="Name" required>
      <input type="email" id="email" name="email" placeholder="Email" required>
      <textarea id="content" name="content" placeholder="Message" required></textarea>
      <button type="submit">Submit</button>
    </form>
  </body>
</html>';

return function ($context) {
  global $html;

  if ($context->req->method === 'GET') {
    // return $context->res->send($html, 200, ['content-type' => 'text/html']);
    return $context->log('Hello from Appwrite!');
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