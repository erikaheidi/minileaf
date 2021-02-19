<?php

namespace App\Command\Set;

use App\Command\NanoleafController;
use App\Device;
use App\Service\NanoleafService;

class EffectController extends NanoleafController
{
    public function execute(Device $device, NanoleafService $service)
    {
        $effect = "Cotton  Candy";
        if (isset($this->getArgs()[4])) {
            $effect = (string) $this->getArgs()[4];
        }

        $service->setEffect($device, $effect);
    }
}