<?php
namespace Qolio\Apihub\Router\Interface;

interface IAuth {
	public function process(): ?IResponse;
}