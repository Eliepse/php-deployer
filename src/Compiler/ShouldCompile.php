<?php


namespace Eliepse\Deployer\Compiler;


interface ShouldCompile
{
    public function getRaw(): string;


    public function setCompiled(string $compiled): void;
}