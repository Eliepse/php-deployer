<?php

namespace Eliepse\Deployer\Project;

use Carbon\Carbon;
use Eliepse\Deployer\Compiler\CompilerResource;

class Release implements CompilerResource
{

    /**
     * @var Carbon
     */
    private $time;


    public function __construct(Carbon $time = null)
    {

        $this->time = $time ?? Carbon::now();
    }


    public function getFolderName(): string
    {
        return $this->time->format("YmdHis");
    }


    public function getCompilingData(): array
    {
        return [
            "release_name" => $this->getFolderName(),
        ];
    }
}