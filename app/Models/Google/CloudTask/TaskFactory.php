<?php

namespace App\Models\Google\CloudTask;

use Google\Cloud\Tasks\V2\HttpMethod;
use Google\Cloud\Tasks\V2\HttpRequest;
use Google\Cloud\Tasks\V2\OidcToken;
use Google\Cloud\Tasks\V2\Task;

use App\Models\Slack\Payload;

class TaskFactory
{
	protected $serviceAccountEmail;

	public function __construct()
	{
		$this->serviceAccountEmail = env("SERVICE_ACCOUNT_EMAIL");
	}

	public function createTask(Payload $payload): Task
	{
		// Add your service account email to construct the OIDC token
		// in order to add an authentication header to the request.
		$oidcToken = new OidcToken();
		$oidcToken->setServiceAccountEmail($this->serviceAccountEmail);

		// Create an Http Request Object.
		$httpRequest = new HttpRequest();
		// The full url path that the task request will be sent to.
		$httpRequest->setUrl($payload->getUrl());
		// POST is the default HTTP method, but any HTTP method can be used.
		$httpRequest->setHttpMethod(HttpMethod::POST);
		//The oidcToken used to assert identity.
		$httpRequest->setOidcToken($oidcToken);

		$httpRequest->setHeaders(["Content-Type" => "application/json"]);
		// Setting a body value is only compatible with HTTP POST and PUT requests.
   		$httpRequest->setBody($payload->toJson());

		$task = new Task();
		$task->setHttpRequest($httpRequest);

		return $task;
	}
}
