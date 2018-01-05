Simple streaming radio powered by RaspberryPi and PHP

#### Setup
1) Download the latest [Raspbian](https://www.raspberrypi.org/downloads/raspbian/)
2) Copy the image to an SD Card
3) Setup wifi information in /boot (/boot/wpa_supplicant.conf)

Example: /boot/wpa_supplicant.conf

```
ctrl_interface=DIR=/var/run/wpa_supplicant GROUP=netdev
 update_config=1
 country=«your_ISO-3166-1_two-letter_country_code»
 
 network={
     ssid="«your_SSID»"
     psk="«your_PSK»"
     key_mgmt=WPA-PSK
 }
```
 
Replace «your_ISO-3166-1_two-letter_country_code» with your [ISO Country Code](https://www.iso.org/obp/ui/#search/code/) (such as CA for Canada), «your_SSID» with your wireless access point name and «your_PSK» with your wifi password.
 
 
4) Setup ssh access in boot (/boot/ssh - no extension)
5) SSH into your RaspberryPi, download installer