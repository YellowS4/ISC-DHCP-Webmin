# ISC-DHCP-Webmin
This is the project of two students to create a web GUI for an isc-dhcp server.

**THIS IS NOT MEANT TO BE USED IN PRODUCTION !**

## Required to run
*  **aptitude** used to install/deinstall isc-dhcp-server and check status of the installation
*  **cat**
*  **Debian** _7(Wheezy)_ _kernel linux 3.2.57_
*  **ifconfig** used
*  **netstat** used to get knowledge of the routeur on the network
*  **PostgreSQL** _9.1 9.3_ Database we use
*  **PHP** _5.4 5.5_ needed to execute scripts
*  **sudo** _1.8.10.p3_ needed to enable the php script to install/deinstall isc-dhcp-server and controling it (reloading config...)

### For **nginx** installation ( *tested version_ ):
*  **nginx** _1.6_ HTTP server
*  **PHP-filter** _5.5_ needed to filter email addresses
*  **PHP-hash** _5.5_ needed for SHA512
*  **PHP-openssl** _5.5_ needed for pseudo random bytes generatir
*  **PHP-PDO** *5.5_ needed to connect to the database
*  **PHP-PDO-pgsql** _5.5_ needed since we use PostgreSQL and PDO
*  **PHP-session** _5.5_ needed for 
*  **PHP-fpm** _5.5_ needed to execute PHP scripts, could be executed by apache but what's the point?

### For **apache** installation ( *tested version_ ):
*  **apache** _2.2.22_ HTTP server

### Optionnal tools:
*  **phpPgAdmin** _5.0.4_

## Installation
Everything needed for installation should be in the **/install** directory.


Please note that the project had to be written in french but there's if you want to use english.