<?php

namespace App\Service;

use App\Device;
use App\Exception\DeviceNotFoundException;
use Minicli\App;
use Minicli\Curly\Client;
use Minicli\Minicache\FileCache;
use Minicli\ServiceInterface;

class NanoleafService implements ServiceInterface
{
    static $API_BASE = '/api/v1';
    static $PORT = '16021';

    protected array $devices = [];
    public FileCache $storage;
    public $last_request_info;

    public function load(App $app)
    {
        $this->storage = new FileCache($app->config->data_dir);

        foreach (glob($app->config->data_dir . '/*.json') as $device_config_file) {
            $device_info = json_decode(file_get_contents($device_config_file), 1);
            $device = new Device($device_info['ip_address'], $device_info['name']);
            $device->token = $device_info['token'];
            $this->addDevice($device);
        }
    }

    public function addDevice(Device $device)
    {
        $this->devices[$device->name] = $device;
    }

    public function getDevice(string $name)
    {
        return $this->devices[$name] ?? null;
    }

    /**
     * returns the first device registered
     */
    public function getDefault()
    {
        return count($this->devices) ? $this->devices[array_key_first($this->devices)] : null;
    }

    public function getDeviceURL(Device $device):string
    {
        return 'http://' . $device->ip_address . ':' . self::$PORT . self::$API_BASE;
    }

    public function auth(string $name)
    {
        /** @var Device $device */
        $device = $this->getDevice($name);
        $url = $this->getDeviceURL($device) . '/new';

        if ($device ===  null) {
            throw new DeviceNotFoundException("Device $name not found.");
        }

        $client = new Client();

        $response = $client->post($url, []);

        if ($response['code'] == 200) {
            $body = json_decode($response['body'], 1);
            $code = $body['auth_token'];
            $device->token = $code;

            $device->save($this->storage);
        } else {
            throw new \Exception("An error occurred when trying to connect to the Nanoleafs. Make sure you press the start button to 5+ seconds to pair the connection.");
        }
    }

    public function powerOn(Device $device)
    {
        $url = $this->getDeviceURL($device) . '/' . $device->token . '/state';

        $response = $this->curl_put($url, ['on' => [ 'value' => true] ]);

        if ($response['code'] !== 204) {
            throw new \Exception("An error occurred when trying to connect to your Nanoleaf panel.");
        }
    }

    public function powerOff(Device $device)
    {
        $url = $this->getDeviceURL($device) . '/' . $device->token . '/state';

        $response = $this->curl_put($url, ['on' => [ 'value' => false] ]);

        if ($response['code'] !== 204) {
            throw new \Exception("An error occurred when trying to connect to your Nanoleaf panel.");
        }
    }

    public function setBrightness(Device $device, $value)
    {
        $url = $this->getDeviceURL($device) . '/' . $device->token . '/state';

        $response = $this->curl_put($url, ['brightness' => [ 'value' => $value] ]);

        if ($response['code'] !== 204) {
            var_dump($response);
            throw new \Exception("An error occurred when trying to connect to your Nanoleaf panel.");
        }
    }

    public function setEffect(Device $device, $value)
    {
        $url = $this->getDeviceURL($device) . '/' . $device->token . '/effects';

        $response = $this->curl_put($url, ['select' => $value]);

        if ($response['code'] !== 204) {
            print_r($this->last_request_info);
            throw new \Exception("An error occurred when trying to connect to your Nanoleaf panel.");
        }
    }

    public function getEffects(Device $device)
    {
        $url = $this->getDeviceURL($device) . '/' . $device->token . '/effects/effectsList';

        $client = new Client();
        $response = $client->get($url);

        if ($response['code'] !== 200) {
            var_dump($response);
            throw new \Exception("An error occurred when trying to connect to your Nanoleaf panel.");
        }

        return json_decode($response['body'], 1);
    }

    protected function curl_put(string $url, array $data = [])
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($data));

        $response = curl_exec($curl);
        $this->last_request_info = curl_getinfo($curl);
        $response_code = $this->last_request_info['http_code'];

        curl_close($curl);

        return [ 'code' => $response_code, 'body' => $response ];
    }
}