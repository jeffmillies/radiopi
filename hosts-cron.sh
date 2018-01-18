#!/bin/sh
if [ `whoami` != root ]; then
  echo "Please run with sudo or as root"
  exit
fi

FILE=/etc/hosts
IP=$(hostname -I | head -n1 | cut -d " " -f1)

if grep -q "radiopi.cron" ${FILE}
then
    LINE=$(awk '/radiopi.cron/{ print NR }' ${FILE})
    sed -i "${LINE}d" ${FILE}
fi
echo "$IP radiopi radiopi.local # radiopi.cron" >> ${FILE}