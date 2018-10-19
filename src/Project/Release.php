<?php

namespace Eliepse\Deployer\Project;

use Carbon\Carbon;

class Release
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

}