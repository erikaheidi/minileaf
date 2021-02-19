<?php

namespace App\Command\Set;

use App\Command\NanoleafController;
use App\Device;
use App\Service\NanoleafService;

class BrightnessController extends NanoleafController
{
    public function execute(Device $device, NanoleafService $service)
    {
        $brightness = 50;
        if (isset($this->getArgs()[4])) {
            $brightness = (int) $this->getArgs()[4];
        }

        $service->setBrightness($device, $brightness);
    }
}