#!/bin/sh
echo "
=================================================================
\033[0;32m
    .~~.   .~~.
   '. \ ' ' / .'\033[0;31m
    .~ .~~~..~.    \033[0;37m                                             \033[0;31m
   : .~.'~'.~. :   \033[0;37m  ______           __ __         ______ __   \033[0;31m
  ~ (   ) (   ) ~  \033[0;37m  |   __ \.---.-.--|  |__|.-----.|   __ \__| \033[0;31m
 ( : '~'.~.'~' : ) \033[0;37m  |      <|  _  |  _  |  ||  _  ||    __/  | \033[0;31m
  ~ .~ (   ) ~. ~  \033[0;37m  |___|__||___._|_____|__||_____||___|  |__| \033[0;31m
   (  : '~' :  )
    '~ .~~~. ~'
        '~' \033[0;37m
=================================================================";
echo "\033[0;31m === Updating/Upgrading Apt === \033[0;37m";
apt-get update && apt-get upgrade -y;

echo "\033[0;31m === Setting Hostname to 'radiopi' === \033[0;37m";
sed -i 's/raspberrypi/radiopi/g' /etc/hosts;
sed -i 's/raspberrypi/radiopi/g' /etc/hostname;

echo "\033[0;31m === Installing MPD MPC (Radio Player) and Apache2/PHP === \033[0;37m";
apt-get install unzip mpd mpc apache2 php php-curl php-dom -y;

echo "\033[0;31m === Updating directory === \033[0;37m";
chown -R pi:root /var/www;
wget https://github.com/jeffmillies/radiopi/archive/master.zip -P /tmp;
rm -rf /var/www/*;
unzip /tmp/master.zip -d /tmp;
rsync -av /tmp/radiopi-master/ /var/www/;
rm -rf /tmp/radiopi-master;
rm -rf /tmp/master.zip;


chown mpd:www-data /var/lib/mpd/playlists;
chown www-data:root /var/www/lib/radiopi.json;
chmod -R g+rw /var/lib/mpd/playlists;
