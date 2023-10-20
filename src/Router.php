<?php
namespace Qolio\Apihub\Router;

use Qolio\Apihub\Router\Default\Controller;
use Qolio\Apihub\Router\Exception\RouterException;
use Qolio\Apihub\Router\Interface\IAuth;
use Qolio\Apihub\Router\Interface\IResponse;
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
			print_r($urn);
			$controller = $this->namespace . $this->getPathController();
			print_r($controller);
			$parts = $this->getParts(explode('/', substr($urn, 1)));
			print_r($parts);
			$partController = $parts[2] ?? null;
			print_r($partController);
			$partMethod = $parts[3] ?? '';
			print_r($partMethod);

			if (preg_match('#^(\/([a-z0-9])[a-z0-9_]*([a-z0-9]))+$#', $urn) == 0) {
				throw new RouterException("Urn is wrong: $urn", 2);
			}
			
			if (isset($partController)) {
				$controller .= $this->defineName($partController) . 'Controller';
				print_r($controller);
				if (class_exists($controller)) {
					$method = 'action' . $this->defineName($partMethod);
					print_r($method);
					if (method_exists($controller, $method)) {
						print_r('run');
						return $this->runAuth() ?? (new $controller)->$method();
					} else {
						print_r('no_method');
						throw new RouterException("There is no method in controller: $controller $method", 5);
					}
				} else {
					print_r('No controller');
					throw new RouterException("There is no controller: $controller", 4);
				}
			} else {
				throw new RouterException("There is no controller name: $urn", 3);
			}
		} catch (RouterException $e) {
			print_r('catch1');
			return (new Controller)->routerException($e);
		} catch (Throwable $e) {
			print_r('catch2');
			return (new Controller)->serverError($e);
		}
	}
}