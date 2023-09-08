<?php
namespace Introvert\Apihub\Router\Default;

use Introvert\Apihub\Router\Interface\IResponse;
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