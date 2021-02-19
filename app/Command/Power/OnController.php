<?php

namespace App\Command\Power;

use App\Command\NanoleafController;
use App\Device;
use App\Service\NanoleafService;


class OnController extends NanoleafController
{
    public function execute(Device $device, NanoleafService $service)
    {
        $service->powerOn($device);
    }
}