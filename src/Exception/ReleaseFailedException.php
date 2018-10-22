<?php


namespace Eliepse\Deployer\Exception;


use Eliepse\Deployer\Release\Release;
use Eliepse\Deployer\Release\RunnableRelease;
use Throwable;

class ReleaseFailedException extends \Exception
{

    /**
     * @var Release
     */
    private $release;


    public function __construct(Release $release, string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->release = $release;
    }


    /**
     * @return Release|RunnableRelease
     */
    public function getRelease(): Release
    {
        return $this->release;
    }

}