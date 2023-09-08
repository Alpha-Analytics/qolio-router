<?php
namespace Introvert\Apihub\Router;

use Introvert\Apihub\Router\Exception\RouterException;

class RouterCron extends Router {

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