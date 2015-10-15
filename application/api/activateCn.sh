#!/bin/bash

echo Start activation

cd /etc/openvpn/servers/Armagnet-Conf/ccd/
touch "$1"
chmod 666 "$1"

echo Done !