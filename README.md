# ISC-DHCP-Webmin
This is the project of two students to create a web GUI for an isc-dhcp server.

**THIS IS NOT MEANT TO BE USED IN PRODUCTION !**

## Required to run
*  _*aptitude*_ used to install/deinstall isc-dhcp-server and check status of the installation
*  _*cat*_
*  _*Debian*_ *7(Wheezy)_ *kernel linux 3.2.57_
*  _*ifconfig*_ used
*  _*netstat*_ used to get knowledge of the routeur on the network
*  _*PostgreSQL*_ *9.1 9.3_ Database we use
*  _*PHP*_ *5.4 5.5_ needed to execute scripts
*  _*sudo*_ *1.8.10.p3_ needed to enable the php script to install/deinstall isc-dhcp-server and controling it (reloading config...)

### For _*nginx*_ installation ( *tested version_ ):
*  **nginx** *1.6_ HTTP server
*  **PHP-filter** *5.5_ needed to filter email addresses
*  **PHP-hash** *5.5_ needed for SHA512
*  **PHP-openssl** *5.5_ needed for pseudo random bytes generatir
*  **PHP-PDO** *5.5_ needed to connect to the database
*  **PHP-PDO-pgsql** *5.5_ needed since we use PostgreSQL and PDO
*  **PHP-session** *5.5_ needed for 
*  **PHP-fpm** *5.5_ needed to execute PHP scripts, could be executed by apache but what's the point?

### For _*apache*_ installation ( *tested version_ ):
*  **apache** *2.2.22_ HTTP server

### Optionnal tools:
*  **phpPgAdmin** *5.0.4_
## Installation
Everything needed for installation should be in the _*/install*_ directory.


Please note that the project had to be written in french but there's if you want to use english.