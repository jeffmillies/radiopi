Simple streaming radio powered by RaspberryPi and PHP

#### Setup
1) Download the latest [Raspbian](https://www.raspberrypi.org/downloads/raspbian/)
2) Copy the image to an SD Card
3) Setup wifi information in /boot (/boot/wpa_supplicant.conf)

Example: /boot/wpa_supplicant.conf

    ctrl_interface=DIR=/var/run/wpa_supplicant GROUP=netdev
    update_config=1
    country=«your_ISO-3166-1_two-letter_country_code»
     
    network={
        ssid="«your_SSID»"
        psk="«your_PSK»"
        key_mgmt=WPA-PSK
        id_str="«your_unique_name»"
    }
    
    network={
        ssid="«your_second_SSID»"
        psk="«your_second_PSK»"
        key_mgmt=WPA-PSK
        id_str="«your_second_unique_name»"
    }
 
Replace «your_ISO-3166-1_two-letter_country_code» with your [ISO Country Code](https://www.iso.org/obp/ui/#search/code/) (such as CA for Canada), 
«your_SSID» with your wireless access point name and 
«your_PSK» with your wifi password.
«your_unique_name» should be used if you're going to list more than 1 network, this will let the Raspberry Pi join the available network.
 
 
4) Create ssh file in boot. 
5) SSH into your RaspberryPi, your IP should be shown above the login prompt.
6) Update your password for pi. 

    ```pi@raspberry:~ $ passwd```
5) Download the installer. 

    ```pi@raspberry:~ $ wget https://raw.githubusercontent.com/jeffmillies/radiopi/master/install-radiopi.sh```
6) Make it executable. 

    ```pi@raspberry:~ $ chmod +x install-radiopi.sh```
7) Run! 

    ```pi@raspberry:~ $ sudo ./install-radiopi.sh```

After Rebooting you will be able to view and control [RadioPi](http://radiopi/) as long as you are connected to the same network.

Addons 
https://learn.adafruit.com/adafruit-16x2-character-lcd-plus-keypad-for-raspberry-pi
