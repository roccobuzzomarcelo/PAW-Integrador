<?php

namespace PAW\src\Core;

use Dotenv\Dotenv;

class Config{
    private array $configs;
    public function __construct()
    {
        $dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../../');
        $dotenv->load();
        $this->configs["LOG_LEVEL"] = getenv("LOG_LEVEL") ?: "debug";
        $path = getenv("LOG_PATH") ?: __DIR__ . "/logs/app.log";
        $this->configs["LOG_PATH"] = $this->joinPaths('..', $path);
    }

    public function get(string $key): mixed
    {
        return $this->configs[$key] ?? null;
    }

    public function joinPaths(){
        $paths = array();
        foreach(func_get_args() as $arg){
            if($arg !== ''){
                $paths[] = $arg;
            }
        }
        return preg_replace('#/+#', '/', join('/', $paths));
    }
}