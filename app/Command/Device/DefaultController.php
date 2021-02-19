<?php

namespace App\Command\Device;

use Minicli\App;
use Minicli\Command\CommandController;

class DefaultController extends CommandController
{
    public function handle()
    {
        $this->getPrinter()->info('run "device connect" to add a new nanoleaf panel. Youll need its IP address.');
    }
}