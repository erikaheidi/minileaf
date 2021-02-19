# Minileaf

A CLI PHP application to control Nanoleaf panels, using the Nanoleaf OpenAPI.

You'll need to know the IP address of your Nanoleaf controller. 

On Linux (Ubuntu), you can use the `arp-scan` utility to scan for devices connected to your network:
```shell
sudo arp-scan -l --interface=wlp2s0 
```
Change `wlp2s0` to the name of your current wifi device, find out with `ip a`.

_Minileaf is a highly experimental project, use at y our own risk._

## Setup
You'll need PHP 7.4+, Curl, Json, and Composer.

Clone this repository:

```shell
git clone 
```

```shell
cd minileaf
composer install
```

## Connect to a Device

```shell
./minileaf device connect
```

This will prompt you for the device's IP address, and an identifiable name that you will use to manage this device later on, for instance *office* or *living*.

Once this information is passed on, it will wait for you to hold the power button on your Nanoleaf control panel for 5 seconds. Press `ENTER` to continue.

When the pairing is finished, you'll should see a `success` message. Then you're ready to control the panel via commands.

## Power

### Power on

```shell
./minileaf power on device-name
```

### Power off

```shell
./minileaf power off device-name
```

## Brightness

`brightness-value` can be a value from `0` to `100`, where `100` is fully bright and `0` is off.

```shell
./minileaf set brihgtness device-name brihgtness-value
```

## Effects

### List Effects

```shell
./minileaf device effects device-name
```

### Set Effect

```shell
./minileaf set effect device-name "Effect Name"
```