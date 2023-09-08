<?php
namespace Introvert\Apihub\Router\Default;

use Introvert\Apihub\Router\Exception\RouterException;
use Introvert\Apihub\Router\Interface\IResponse;
use Throwable;

class Controller {
	public function routerException(RouterException $e): IResponse {
		return new View('Not Found', 404);
	}

	public function serverError(Throwable $e): IResponse {
		return new View('Internal Server Error', 500);
	}
}