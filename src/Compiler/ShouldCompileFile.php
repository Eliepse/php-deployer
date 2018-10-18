<?php


namespace Eliepse\Deployer\Compiler;


interface ShouldCompileFile extends ShouldCompile
{
    public function getSourcePath(): string;
}