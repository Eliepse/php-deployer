<?php


namespace Eliepse\Deployer\Compiler;


interface ShouldCompile
{
    public function getUncompiled(): string;


    public function setCompiled(string $compiled): void;
}