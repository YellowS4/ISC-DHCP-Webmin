sudo /usr/bin/apt-get install isc-dhcp-server
sudo /bin/mkdir /etc/dhcp/backup/
date_sauv=`date "+%Y_%m_%d"`
sudo /bin/cp /etc/dhcp/dhcpd.conf /etc/dhcp/backup/dhcpd_$date_sauv.conf
sudo chmod 777 /etc/default/isc-dhcp-server


