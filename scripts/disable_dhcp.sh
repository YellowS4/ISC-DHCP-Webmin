#arreter le serveur dhcp au demarrage
update-rc.d isc-dhcp-server disable
#On l'�teinds
/etc/init.d/isc-dhcp-server stop