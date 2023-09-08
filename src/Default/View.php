<?php
namespace Qolio\Apihub\Router\Default;

use Qolio\Apihub\Router\Interface\IResponse;
use Throwable;

class View implements IResponse {

	public function __construct(
		private readonly string $message,
		private readonly int $code,
	)
	{}

	public function output(): void
	{
		http_response_code($this->code);
		echo json_encode([
			'message' => $this->message
		]);
	}
}