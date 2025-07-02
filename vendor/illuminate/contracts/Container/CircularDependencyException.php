<?php

namespace SmokeTestgen202507\Illuminate\Contracts\Container;

use Exception;
use SmokeTestgen202507\Psr\Container\ContainerExceptionInterface;
class CircularDependencyException extends Exception implements ContainerExceptionInterface
{
    //
}
