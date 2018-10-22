<?php


namespace Tests;


use Eliepse\Deployer\Deployer;
use PHPUnit\Framework\TestCase;

/**
 * Class TestBase
 * @package Tests
 * @uses CreateDeployer
 */
class TestBase extends TestCase
{

    public static function setUpBeforeClass()
    {
        Deployer::make(base_path("/tests/fixtures/projects/"));
    }

}