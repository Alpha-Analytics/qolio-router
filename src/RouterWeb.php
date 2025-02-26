<?php
namespace Qolio\Apihub\Router;

use Qolio\Helper\Logger;

class RouterWeb extends Router {

    public function __construct(string $namespace)
    {
        Logger::init('web');

        parent::__construct($namespace);
    }

    protected function getUrn(): string
	{
        Logger::log('HTTP_X_REQUEST_URI: ' . $_SERVER['HTTP_X_REQUEST_URI']);
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