<?php
namespace Qolio\Apihub\Router;

use Qolio\Apihub\Router\Default\Controller;
use Qolio\Apihub\Router\Exception\RouterException;
use Qolio\Apihub\Router\Interface\IAuth;
use Qolio\Apihub\Router\Interface\IResponse;
use Qolio\Helper\Logger;
use Throwable;

abstract class Router {

	protected readonly string $namespace;
	private ?IAuth $auth = null;

	abstract protected function getUrn(): string;
	abstract protected function getParts(array $parts): array;
	abstract protected function getPathController(): string;

	private function defineName(string $partUrn): string {
		return ucfirst(preg_replace_callback('/_([a-z])/', function($matches){
			return strtoupper($matches[1]);
		}, $partUrn));
	}

	private function runAuth(): ?IResponse {
		return isset($this->auth) ? $this->auth->process() : null;
	}

	public function setAuth(IAuth $auth): self {
		$this->auth = $auth;
		return $this;
	}

	public function __construct(string $namespace)
	{
		$this->namespace = $namespace;
	}

	public function route(): IResponse {
		try {
			$urn = $this->getUrn();
            Logger::log("urn: $urn");
			$controller = $this->namespace . $this->getPathController();
            Logger::log("controller: $controller");
			$parts = $this->getParts(explode('/', substr($urn, 1)));
            Logger::log("parts: " . json_encode($parts));
			$partController = $parts[2] ?? null;
			$partMethod = $parts[3] ?? '';

			if (preg_match('#^(\/([a-z0-9])[a-z0-9_]*([a-z0-9]))+$#', $urn) == 0) {
				throw new RouterException("Urn is wrong: $urn", 2);
			}
			
			if (isset($partController)) {
				$controller .= $this->defineName($partController) . 'Controller';
				if (class_exists($controller)) {
					$method = 'action' . $this->defineName($partMethod);
					if (method_exists($controller, $method)) {
						return $this->runAuth() ?? (new $controller)->$method();
					} else {
						throw new RouterException("There is no method in controller: $controller $method", 5);
					}
				} else {
					throw new RouterException("There is no controller: $controller", 4);
				}
			} else {
				throw new RouterException("There is no controller name: $urn", 3);
			}
		} catch (RouterException $e) {
			return (new Controller)->routerException($e);
		} catch (Throwable $e) {
			return (new Controller)->serverError($e);
		}
	}
}