<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Aws\S3\S3Client;

require 'vendor/autoload.php';

$app = new \Slim\App;


$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name. Este é o PHPeste 2016 2!");

    return $response;
});

$app->post('/files/{content}', function (Request $request, Response $response) {
    $content = $request->getAttribute('content');
    $response->getBody()->write("Arquivo feito com sucesso!");

	try {
	    $s3 = new S3Client([
		    'version' => 'latest',
		    'region'  => 'sa-east-1',
		    'credentials' => [
		        'key'    => 'AKIAJJCENL6ALHXTUQPQ',
		        'secret' => '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'
		    ]
		]);

	    $s3->putObject([
	        'Bucket' => 'phpeste2016',
	        'Key'    => rand() . '.txt',
	        'Body'   => $content,
	        'ACL'    => 'public-read',
	    ]);
	} catch (Aws\Exception\S3Exception $e) {
	    echo "There was an error uploading the file.\n";
	}

    return $response;
});

$app->run();