<?php

namespace App\Command\Device;

use App\Device;
use App\Exception\DeviceNotFoundException;
use App\Service\NanoleafService;
use Minicli\App;
use Minicli\Command\CommandController;

class TestController extends CommandController
{
    /** @var NanoleafService */
    protected $nanoleaf;
    
    public function handle()
    {
        $this->nanoleaf = $this->getApp()->nanoleaf;
        $device = $this->nanoleaf->getDefault();

        if (isset($this->getArgs()[3])) {
            $device_name = $this->getArgs()[3];
            $device = $this->nanoleaf->getDevice($device_name);
        }

        if ($device === null) {
            $this->getPrinter()->error("No device found. Are you sure you registered that?");
            exit(1);
        }

        if ($device->token == null) {
            $this->getPrinter()->error("token not found.");
            exit;
        }

        $this->nanoleaf->powerOn($device);
        $this->nanoleaf->powerOff($device);
    }
}