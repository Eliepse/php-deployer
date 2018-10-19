<?php


namespace Tests\Unit;


use Eliepse\Deployer\Config\Config;
use Eliepse\Deployer\Exception\ConfigurationException;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{

    public function testLoad()
    {
        $config = Config::load(base_path("tests/fixtures/projects/test.json"));

        $this->assertEquals("dev", $config->get("branch"));
    }


    public function testMissingRequired()
    {
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage("The configuration is missing required elements: foo, bar");

        Config::load(base_path("tests/fixtures/projects/test.json"), new Config(["foo", "bar"]));
    }


    public function testFilter()
    {
        $config = Config::load(base_path("tests/fixtures/projects/test.json"), new Config([], ["branch"]));

        $this->assertEquals(["branch" => "dev"], $config->getAll());
    }
}