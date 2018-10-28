<?php


namespace Tests;


use Carbon\Carbon;
use function Eliepse\Deployer\base_path;
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

    protected $deployer;


    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->deployer = new Deployer(base_path("/tests/fixtures/projects/"));

        $filename = Carbon::now()->format("YmdHis");

        $log = new Logger('Test');
        $log->pushHandler(new StreamHandler(base_path("/storage/logs/$filename.log")));

        $this->deployer->setLogger($log);

        $log->debug(static::class);
        $log->debug("===============================");
    }


    protected function setUp()
    {
        $this->deployer->getLogger()->debug("# " . $this->getName());
    }

}