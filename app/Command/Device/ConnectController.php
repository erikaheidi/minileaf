<?php

namespace App\Command\Device;

use App\Device;
use App\Exception\DeviceNotFoundException;
use App\Service\NanoleafService;
use Minicli\App;
use Minicli\Command\CommandController;
use Minicli\Input;

class ConnectController extends CommandController
{
    public function handle()
    {
        /** @var NanoleafService $nanoleaf */
        $nanoleaf = $this->getApp()->nanoleaf;

        $input = new Input();
        $input->setPrompt("");
        $this->getPrinter()->info("What is the device's IP address?");
        $device_ip = $input->read();

        $this->getPrinter()->info("Give a name to this panel.");
        $device_name = $input->read();

        $device = new Device($device_ip, $device_name);
        $nanoleaf->addDevice($device);

        $this->getPrinter()->info("Connecting to new Device...", true);
        $this->getPrinter()->info("Hold your nanoleaf panel power button for 7+ seconds until it starts to blink.", true);
        $this->getPrinter()->info("Press any key to continue...");
        $input->read();

        try {
            $nanoleaf->auth($device->name);
        } catch (\Exception $e) {
            $this->getPrinter()->error($e->getMessage());
            exit(1);
        }

        $this->getPrinter()->success('Successfully connected to device "'.$device->name.'" ...');
    }
}