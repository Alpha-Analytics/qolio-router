<?php
namespace Qolio\Apihub\Router;

class RouterWeb extends Router {

	protected function getUrn(): string
	{
		file_put_contents('php://stdout', "HTTP_X_REQUEST_URI: " . $_SERVER['HTTP_X_REQUEST_URI'] . PHP_EOL);
		return rtrim(parse_url($_SERVER['HTTP_X_REQUEST_URI'], PHP_URL_PATH), '/');
	}

	protected function getParts(array $parts): array
	{
		return array_slice($parts, (int)($_ENV['PARTS_START'] ?? 0));
	}

	protected function getPathController(): string
	{
		return 'Controller\\Web\\';
	}
}