<?php

namespace Eliepse\Deployer {

    function base_path(string $path = ""): string
    {
        return __DIR__ . "/../$path";
    }

}
