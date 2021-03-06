#!/bin/sh
if [ `whoami` != root ]; then
  echo "Please run with sudo or as root"
  exit
fi
IFS='%'
LOGO="

\e[0;31m
===========================================================================
\e[0;37m
         .~~.   .~~.
        '. \ ' ' / .'\e[0;31m
   /#/ /#\e[0;37m.~ .~~~..~.\e[0;31m#\ \#\    \e[0;31m                                                     \e[0;31m
  /#/ /#\e[0;37m: .~.'~'.~. :\e[0;31m#\ \#\   \e[0;34m   ______           __ __        \e[0;31m ______ __  \e[0;31m
 /#/ /#\e[0;37m~ (   ) (   ) ~\e[0;31m#\ \#\  \e[0;34m  |   __ \.---.-.--|  |__|.-----.\e[0;31m|   __ \__| \e[0;31m
|#| |#\e[0;37m( : '~'.~.'~' : )\e[0;31m#| |#| \e[0;34m  |      <|  _  |  _  |  ||  _  |\e[0;31m|    __/  | \e[0;31m
 \#\ \#\e[0;37m~ .~ (   ) ~. ~\e[0;31m#/ /#/  \e[0;34m  |___|__||___._|_____|__||_____|\e[0;31m|___|  |__| \e[0;31m
  \#\ \#\e[0;37m(  : '~' :  )\e[0;31m#/ /#/   \e[0;31m
   \#\ \#\e[0;37m'~ .~~~. ~'\e[0;31m#/ /#/    \e[0;37m
             '~' \e[0;31m

===========================================================================\e[0;40m";
echo $LOGO;
echo "
Welcome to RadioPi's Installation script!

This will update apt and install any software required. (mpd mpc apache2 php php-curl php-dom)
It will then download the files needed, delete the contents of /var/www and place them there.
There will be some small permission updates on files to allow apache to update a config file
and for apache to save radio files.

After everything is done, it will reboot the raspberrypi for all the changes to take effect.

If you don't want these changes please answer 'n' to quit the installer
";
echo -n "\e[0;31mInstall RadioPi? (y/n): \e[0;40m";
read answer
if echo "$answer" | grep -iq "^y" ;then
    echo "\e[0;31m === Setting Hostname to 'radiopi' === \e[0;40m";
    sleep 3;
    sed -i 's/raspberrypi/radiopi/g' /etc/hosts;
    sed -i 's/raspberrypi/radiopi/g' /etc/hostname;
    echo "\e[0;34mDone \e[0;40m";

    echo "\e[0;31m === Updating .bashrc === \e[0;40m";
    sleep 3;
    if [ -e /home/pi/.bashrc.bak ]
    then
        cp -rf /home/pi/.bashrc.bak /home/pi/.bashrc
        chown pi:pi /home/pi/.bashrc
    else
        cp -rf /home/pi/.bashrc /home/pi/.bashrc.bak
        chown pi:pi /home/pi/.bashrc.bak
    fi
    sed -i "s/#alias ll='ls -l'/alias ll='ls -al'/g" /home/pi/.bashrc;
    echo "$(cat /home/pi/.bashrc) \necho -e \"$LOGO\"" > /home/pi/.bashrc
    echo "\e[0;34mDone \e[0;40m";

    echo "\e[0;31m === Updating/Upgrading Apt === \e[0;40m";
    apt-get update && apt-get upgrade -y;


    echo "\e[0;31m === Installing MPD MPC (Radio Player) and Apache2/PHP === \e[0;40m";
    sleep 5;
    apt-get install mpd mpc apache2 php php-curl php-dom -y;
    systemctl enable mpd;
    systemctl start mpd;
    echo "\e[0;34mDone \e[0;40m";

    echo "\e[0;31m === Downloading RadioPi === \e[0;40m";
    sleep 5;
    chown -R pi:root /var/www;
    wget https://github.com/jeffmillies/radiopi/archive/master.zip -P /tmp;
    rm -rf /var/www/*;
    unzip /tmp/master.zip -d /tmp;
    rsync -av /tmp/radiopi-master/ /var/www/;
    rm -rf /tmp/radiopi-master;
    rm -rf /tmp/master.zip;
    echo "\e[0;34mDone \e[0;40m";

    echo "\e[0;31m === Updating directories === \e[0;40m";
    sleep 5;
    chown mpd:www-data /var/lib/mpd/playlists;
    chown www-data:root /var/www/lib/radiopi.json;
    chmod -R g+rw /var/lib/mpd/playlists;
    echo "\e[0;34mDone \e[0;40m";

    echo "\e[0;31m === Adding cron for hosts file === \e[0;40m";
    chmod +x /var/www/hosts-cron.sh
    if ! grep -q "radiopi.cron" /etc/crontab
    then
        echo "# radiopi.cron" >> /etc/crontab
        echo "* * * * * root /var/www/hosts-cron.sh > /tmp/radiopi.log 2>&1" >> /etc/crontab
    fi
    sleep 3;
    echo "\e[0;34mDone \e[0;40m";

    echo "\e[0;31m === Rebooting now === \e[0;40m";
    echo "\e[0;31mBRB! \e[0;40m";
    sleep 3;
    unset IFS
    reboot now;
else
    echo "Bye!"
fi

