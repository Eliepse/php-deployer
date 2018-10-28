<?php


namespace Tests\Unit;


use function Eliepse\Deployer\base_path;
use Eliepse\Deployer\Config\Config;
use Eliepse\Deployer\Exception\ConfigurationException;
use Tests\TestBase;

class ConfigTest extends TestBase
{

    public function testLoad()
    {
        $config = Config::load(base_path("tests/fixtures/projects/test_dry.yaml"));

        $this->assertEquals("dev", $config->get("branch"));
    }


    public function testMissingRequired()
    {
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage("The configuration is missing required elements: fooz, barz");

        Config::load(base_path("tests/fixtures/projects/test_dry.yaml"), new Config(["fooz", "barz"]));
    }


    public function testEmptyRequired()
    {
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage("The configuration is missing required values for keys: baz");

        Config::load(base_path("tests/fixtures/projects/test_dry.yaml"), new Config(["foo", "baz"]));
    }


    public function testFilter()
    {
        $config = Config::load(base_path("tests/fixtures/projects/test_dry.yaml"), new Config([], ["branch"]));

        $this->assertEquals(["branch" => "dev"], $config->getAll());
    }
}