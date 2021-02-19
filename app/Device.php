<?php

namespace App;

use Minicli\Minicache\FileCache;

class Device
{
    public string $name;

    public string $ip_address;

    public $token;

    /**
     * Device constructor.
     * @param string $ip_address
     * @param string $name
     */
    public function __construct(string $ip_address, string $name = "default")
    {
        $this->ip_address = $ip_address;
        $this->name = $name;
    }

    public function load(FileCache $storage)
    {
        $json = $storage->getCached($this->name);

        if ($json) {
            $data = json_decode($json, 1);
            $this->name = $data['name'];
            $this->token = $data['token'];
        }
    }

    public function save(FileCache $storage)
    {
        $storage->save(json_encode([
            'name' => $this->name,
            'token' => $this->token,
            'ip_address' => $this->ip_address
        ]), $this->name);
    }
}