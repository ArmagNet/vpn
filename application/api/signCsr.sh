#!/bin/bash

cd /etc/openvpn/keys/armagnet
openssl ca -batch -in $1.csr -out $1.crt -keyfile ca.pem -cert ca.crt -policy policy_anything -config ../../openvpn-ssl-php.cnf