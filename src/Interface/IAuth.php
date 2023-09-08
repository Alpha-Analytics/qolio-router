<?php
namespace Introvert\Apihub\Router\Interface;

interface IAuth {
	public function process(): ?IResponse;
}