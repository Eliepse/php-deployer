<?php


namespace Eliepse\Deployer\Compiler;


class Compiler
{
    /**
     * Resources to extract data from
     * @var array
     */
    protected $resources = [];

    /**
     * Additional data
     * @var array
     */
    protected $additional = [
        "test" => "Hello world !",
    ];


    public function __construct(CompilerResource $_resources = null)
    {
        array_map(function (CompilerResource $resource) {

            $this->addResource($resource);

        }, func_get_args());
    }


    public function compile(ShouldCompile $object): void
    {
        $tempPath = $this->generateTempFile($object);

        ob_start() && extract($this->getData());

        require $tempPath;

        $compiled = ob_get_clean();

        @unlink($tempPath);

        $object->setCompiled($compiled);
    }


    public function addResource(CompilerResource $resource): self
    {
        array_push($this->resources, $resource);

        return $this;
    }


    public function addAdditionalData(string $key, $value): self
    {
        $this->additional[ $key ] = $value;

        return $this;
    }


    private function generateTempFile(ShouldCompile $object): string
    {
        $filename = uniqid() . ".php";
        $filepath = __DIR__ . "/../../resources/temp/";

        if (!is_dir($filepath)) {
            mkdir($filepath, '0744', true);
        }

        file_put_contents($filepath . $filename, $object->getRaw());

        return $filepath . $filename;
    }


    public function getData(): array
    {
        return array_merge(
            array_reduce(
                $this->resources,
                function (array $data, CompilerResource $resource) {

                    return array_merge($data ?? [], $resource->getCompilingData());

                },
                []
            ),
            $this->additional
        );
    }
}