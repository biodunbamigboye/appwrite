<?php

require(__DIR__ . '/vendor/autoload.php');

use Appwrite\Client;
use Appwrite\Exception;
use Appwrite\Services\Databases;


return function ($context) {
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

  if ($context->req->method === 'GET') {
    return $context->res->send($html, 200, ['content-type' => 'text/html']);
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
      ->setProject('650f49d75871723e54d7')
      ->setKey('91605aee67be074084206623fa6a0854c1dfcb69c7157bb98aa21a7f14b8817a8054ffec3e2b00e0261594ac542040d1f6ea1597fb4a38d079328df1c7ce56e882296cee8701ef215a4617ecfe8db3e14f1aeef97c30d48fda15d52455669010228d8b3781053b7782bdf0e462c4d8b71f26823327d71e7fb59ed1c6f4403518');

    $databases = new Databases($client);
    return $context->res->send(json_encode($formData));
    $document = $databases->createDocument('test_db_id', 'test_collection_id', 'unique()' , $message);

    return $context->res->send("Message sent");
  }

  return $context->res->send('Not found', 404);
};