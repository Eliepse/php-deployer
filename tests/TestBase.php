<?php


namespace Tests;


use Carbon\Carbon;
use Eliepse\Deployer\Deployer;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

/**
 * Class TestBase
 * @package Tests
 * @uses CreateDeployer
 */
class TestBase extends TestCase
{

    /**
     * @throws \Exception
     */
    public static function setUpBeforeClass()
    {
        Deployer::make(base_path("/tests/fixtures/projects/"));

        $filename = Carbon::now()->format("YmdHis-");

        $log = new Logger('Test');
        $log->pushHandler(new StreamHandler(base_path("/storage/logs/$filename.log")));

        Deployer::getInstance()->setLogger($log);

        $log->debug(static::class);
        $log->debug("===============================");
    }


    protected function setUp()
    {
        Deployer::getInstance()
            ->getLogger()
            ->debug("# " . $this->getName());
    }

}