<?php

namespace App\Command;

use App\Device;
use App\Service\NanoleafService;
use Minicli\Command\CommandController;

abstract class NanoleafController extends CommandController
{
    /** @var NanoleafService */
    protected $nanoleaf;

    public function handle()
    {
        /** @var NanoleafService $nanoleaf */
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

        $this->execute($device, $this->nanoleaf);
    }

    abstract public function execute(Device $device, NanoleafService $service);
}