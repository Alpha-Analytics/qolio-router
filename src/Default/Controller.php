<?php
namespace Qolio\Apihub\Router\Default;

use Qolio\Apihub\Router\Exception\RouterException;
use Qolio\Apihub\Router\Interface\IResponse;
use Throwable;

class Controller {
	public function routerException(RouterException $e): IResponse {
		return new View($e , 404);
	}

	public function serverError(Throwable $e): IResponse {
		return new View('Internal Server Error', 500);
	}
}