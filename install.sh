#!/bin/bash

# Packages needed to provide hosting
apt-get install -y \
  apache2-mpm-itk libapache2-mod-security2 \
  php5 php5-gd php5-mcrypt php5-mysql php5-gmp php5-sqlite \
  openssh-server \
  curl wget \
  git \
  build-essential \
  mysql-server mysql-client

rm /etc/apache2/sites-enabled/000-default.conf
cp vhost.conf /etc/apache2/sites-enabled/vhost.conf

a2dismod status
service apache2 restart
