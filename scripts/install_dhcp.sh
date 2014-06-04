sudo apt-get install isc-dhcp-server
sudo mkdir /etc/dhcp/backup/
date_sauv=`date "+%Y_%m_%d"`
sudo cp /etc/dhcp/dhcpd.conf /etc/dhcp/backup/dhcpd_$date_sauv.conf



