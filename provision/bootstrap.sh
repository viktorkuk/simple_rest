#!/usr/bin/env bash

username=$(whoami)

sudo chmod -R 775 /vagrant
sudo usermod -a -G ubuntu www-data

echo -e "\033[32mimport repository keys... \033[0m"
sudo apt-get install software-properties-common python-software-properties -y
sudo apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xcbcb082a1bb943db
sudo add-apt-repository 'deb http://ftp.cc.uoc.gr/mirrors/mariadb/repo/10.0/ubuntu trusty main' -y
sudo add-apt-repository ppa:ubuntu-toolchain-r/test -y
sudo add-apt-repository ppa:ondrej/php -y

echo -e "\033[32mupdate system... \033[0m"
sudo apt-get update -y

echo -e "\033[32minstall required packages... \033[0m"
sudo apt-get install mc git g++-4.9 debconf-utils -y

echo -e "\033[32minstall mariadb... \033[0m"
export DEBIAN_FRONTEND=noninteractive
debconf-set-selections <<< "mariadb-server-5.5 mysql-server/root_password password \"''\""
debconf-set-selections <<< "mariadb-server-5.5 mysql-server/root_password_again password \"''\""
sudo -E apt-get -q install mariadb-server mariadb-client -y

echo -e "\033[32mstart mysql server...\033[0m"
sudo /etc/init.d/mysql start

echo -e "\033[32mCREATE DATABASE spotware_talent...\033[0m"
sudo mysql -e "CREATE DATABASE IF NOT EXISTS spotware_talent DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;"

echo -e "\033[32mImport BX-Books.sql...\033[0m"
sudo mysql spotware_talent < /vagrant/BX-Books.sql

echo -e "\033[32mImport BX-Users.sql...\033[0m"
sudo mysql spotware_talent < /vagrant/BX-Users.sql

echo -e "\033[32mImport BX-Book-Ratings.sql...\033[0m"
sudo mysql spotware_talent < /vagrant/BX-Book-Ratings.sql

echo -e "\033[32mSET NAMES 'utf8'...\033[0m"
sudo mysql -e "SET NAMES 'utf8';"
sudo mysql -e "SET CHARACTER SET utf8;"

echo -e "\033[32minstall nginx...\033[0m"
sudo apt-get install nginx -y

echo -e "\033[32mconfigure nginx...\033[0m"
sudo ln -s /vagrant/provision/spotware-talent.conf /etc/nginx/sites-enabled/
sudo ln -s /vagrant/provision/spotware-client.conf /etc/nginx/sites-enabled/

sudo rm -rf /etc/nginx/sites-available/default
sudo rm -rf /etc/nginx/sites-enabled/default

sudo service nginx restart

echo -e "\033[32minstall php and modules...\033[0m"
sudo apt-get install php7.0 php7.0-cli php7.0-dev php7.0-cgi php7.0-fpm php7.0-curl php7.0-intl php7.0-gd php7.0-mcrypt php7.0-mysql php7.0-mbstring php7.0-imap php7.0-zip php7.0-xml pkg-config -y

sudo service php7.0-fpm restart

sudo curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer


echo -e "\033[32mclean installation...\033[0m"
sudo apt-get autoremove -y

echo -e "\033[32minstall docroot...\033[0m"
if ! [ -L /var/www ]; then
    sudo rm -rf /var/www
    sudo ln -fs /vagrant /var/www
fi

echo -e "\033[32minstall silex...\033[0m"
composer install -d /vagrant/spotware-talent/

