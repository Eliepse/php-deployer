<?php

namespace Eliepse\Deployer\Project;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Eliepse\Deployer\Compiler\CompilerResource;

class Release implements CompilerResource
{

    /**
     * @var Carbon
     */
    private $name;

    /**
     * @var Carbon
     */
    private $deploy_started_at;

    /**
     * @var Carbon
     */
    private $deploy_ended_at;


    /**
     * Release constructor.
     * @param string|null $name
     */
    public function __construct(string $name = null)
    {
        $this->name = $time ?? Carbon::now()->format("YmdHis");
        $this->deploy_started_at = Carbon::now();
    }


    public function getName(): Carbon { return $this->name; }


    public function getDeployStartedAt(): Carbon { return $this->deploy_started_at; }


    public function getDeployEndedAt(): Carbon { return $this->deploy_ended_at; }


    public function getFolderName(): string { return $this->name; }


    public function getCompilingData(): array
    {
        return [
            "release_name" => $this->getFolderName(),
        ];
    }


    /**
     * Set the time when the deployment started. If null, "now" is used.
     * @param Carbon|null $started_at The date deploy started
     * @return Release
     */
    public function setDeployStartedAt(Carbon $started_at = null): self
    {
        $this->deploy_started_at = $started_at ?? Carbon::now();

        return $this;
    }


    /**
     * Set the time when the deployment ended. If null, "now" is used.
     * @param Carbon|null $ended_at The date deploy ended
     * @return Release
     */
    public function setDeployEndedAt(Carbon $ended_at = null): self
    {
        $this->deploy_ended_at = $ended_at ?? Carbon::now();

        return $this;
    }


    public function getDeployDuration(): CarbonInterval
    {
        return $this->deploy_started_at->diffAsCarbonInterval($this->deploy_ended_at);
    }


}