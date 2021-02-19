<?php

namespace App\Command\Device;

use App\Command\NanoleafController;
use App\Device;
use App\Service\NanoleafService;
use Minicli\Output\Filter\ColorOutputFilter;
use Minicli\Output\Helper\TableHelper;

class EffectsController extends NanoleafController
{
    public function execute(Device $device, NanoleafService $service)
    {
        $effects = $service->getEffects($device);

        $table = new TableHelper();
        foreach ($effects as $effect) {
            $table->addRow([$effect]);
        }

        $this->getPrinter()->info("Effects available for this device:", true);
        $this->getPrinter()->rawOutput($table->getFormattedTable(new ColorOutputFilter()));
        $this->getPrinter()->newline();
    }
}