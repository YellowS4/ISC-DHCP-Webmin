#arreter le serveur dhcp au demarrage
update-rc.d isc-dhcp-server disable
#On l'éteinds
/etc/init.d/isc-dhcp-server stop