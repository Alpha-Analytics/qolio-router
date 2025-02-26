<?php
namespace Qolio\Apihub\Router;

use Qolio\Apihub\Router\Exception\RouterException;
use Qolio\Helper\Logger;

class RouterCron extends Router {
    public function __construct(string $namespace)
    {
        Logger::init('cron');

        parent::__construct($namespace);
    }

	protected function getUrn(): string
	{
		$urn = $_SERVER['argv'][1] ?? null;
		if (empty($urn)) {
			throw new RouterException('Server param is empty', 6);
		}
		return $urn;
	}

	protected function getParts(array $parts): array
	{
		return $parts;
	}

	protected function getPathController(): string
	{
		return 'Controller\\Cron\\';
	}
}