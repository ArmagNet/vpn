<?php /*
	Copyright 2014-2015 Cédric Levieux, Jérémy Collot, ArmagNet

	This file is part of VPN.

    VPN is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    VPN is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with VPN.  If not, see <http://www.gnu.org/licenses/>.
*/

$lang["date_format"] = "m/d/Y";
$lang["time_format"] = "H:i";
$lang["datetime_format"] = "the {date} at {time}";

$lang["common_validate"] = "Validate";
$lang["common_delete"] = "Delete";
$lang["common_activate"] = "Activate";
$lang["common_add"] = "Add";
$lang["common_modify"] = "Modify";
$lang["common_reset"] = "Reset";
$lang["common_connect"] = "Connect";
$lang["common_order"] = "Order";

$lang["language_fr"] = "French";
$lang["language_en"] = "English";
$lang["language_de"] = "German";

$lang["vpn_title"] = "ArmagNet VPN - The Associative VPN";

$lang["menu_logout"] = "Log out";
$lang["menu_login"] = "Log in";
$lang["menu_language"] = "Language : {language}";
$lang["menu_index"] = "Home";
$lang["menu_vpns"] = "VPN";
$lang["menu_create_vpn"] = "Create an access";

$lang["login_title"] = "Log in";
$lang["login_loginInput"] = "Identifier";
$lang["login_passwordInput"] = "Password";
$lang["login_button"] = "Log in";
$lang["login_rememberMe"] = "Remember me";
$lang["register_link"] = "or sign in";
$lang["forgotten_link"] = "I forgot my password";

$lang["breadcrumb_index"] = "Home";
$lang["breadcrumb_vpns"] = "VPN";
$lang["breadcrumb_payment"] = "Payment";
$lang["breadcrumb_create_vpn"] = "Create a VPN access";

$lang["index_guide"] = "The manager interface of your associative VPN.";

$lang["payment_done_guide"] = "The payment for your order successfully finished";
$lang["payment_failed_guide"] = "The payment for your order failed";
$lang["payment_referer_link"] = "Back to your original page";
$lang["payment_home_link"] = "Back to home page";

$lang["vpn_configuration_label"] = "Label";
$lang["vpn_configuration_retrievefile"] = "Retrieve file";
$lang["vpn_configuration_key"] = "key";
$lang["vpn_configuration_nokey"] = "no private key available, you may have generate it";
$lang["vpn_configuration_cert"] = "cert";
$lang["vpn_configuration_cacert"] = "cacert";
$lang["vpn_configuration_dev"] = "dev";
$lang["vpn_configuration_dh"] = "dh";
$lang["vpn_configuration_proto"] = "proto";
$lang["vpn_configuration_cipher"] = "cipher";
$lang["vpn_configuration_complzo"] = "comp-lzo";
$lang["vpn_configuration_remoteip"] = "remote ip";
$lang["vpn_configuration_remoteport"] = "remote port";
$lang["vpn_configuration_remotecerttls"] = "remote cert tls";
$lang["vpn_configuration_ovpn"] = "OpenVpn configuration file";
$lang["vpn_configuration_end_date"] = "Validity limit date";

$lang["error_login_ban"] = "Your IP has been blocked for 10mn.";
$lang["error_login_bad"] = "Vérifier vos identifiants, l'identification a échouée.";

$lang["mypreferences_guide"] = "Change my preferences.";
$lang["mypreferences_form_legend"] = "Configuration of your access";
$lang["mypreferences_form_passwordInput"] = "Password";
$lang["mypreferences_form_passwordPlaceholder"] = "the password of your connection";
$lang["mypreferences_form_languageInput"] = "Language";
$lang["mypreferences_form_mailInput"] = "Mail address";
$lang["mypreferences_validation_mail_empty"] = "The mail field can't be empty";
$lang["mypreferences_validation_mail_not_valid"] = "This mail is not a valid mail";
$lang["mypreferences_validation_mail_already_taken"] = "This mail is already taken";
$lang["mypreferences_save"] = "Save my preferences";

$lang["register_guide"] = "Welcome to the register page of VPN";
$lang["register_form_legend"] = "Configuration of your access";
$lang["register_form_loginInput"] = "Login";
$lang["register_form_loginHelp"] = "Preferably use your Twitter ID if you want to receive notifications on Twitter";
$lang["register_form_mailInput"] = "Mail address";
$lang["register_form_passwordInput"] = "Password";
$lang["register_form_passwordHelp"] = "Your password doesn't have to inevitably contain strange characters, but it should preferably be long and memorizable";
$lang["register_form_confirmationInput"] = "Password confirmation";
$lang["register_form_languageInput"] = "Language";
$lang["register_form_iamabot"] = "I'm a bot and i don't know how to uncheck a checkbox";
$lang["register_form_notificationInput"] = "Validation notification";
$lang["register_form_notification_none"] = "None";
$lang["register_form_notification_mail"] = "By mail";
$lang["register_form_notification_simpledm"] = "By simple DM";
$lang["register_form_notification_dm"] = "By multiple DM";
$lang["register_success_title"] = "Successful sign in";
$lang["register_success_information"] = "Your registration is done.
<br>You will soon receive a mail with a link to click letting you activate your account.";
$lang["register_mail_subject"] = "[OTB] Registration mail";
$lang["register_mail_content"] = "Hello {login},

It seems that you registered yourself on VPN. To confirm your registration, please click the link below :
{activationUrl}

The #VPN Team";
$lang["register_save"] = "Sign in";
$lang["register_validation_user_empty"] = "The user field can't be empty";
$lang["register_validation_user_already_taken"] = "This username is already taken";
$lang["register_validation_mail_empty"] = "The mail field can't be empty";
$lang["register_validation_mail_not_valid"] = "This mail is not a valid mail";
$lang["register_validation_mail_already_taken"] = "This mail is already taken";
$lang["register_validation_password_empty"] = "The password field can't be empty";

$lang["activation_guide"] = "Welcome on the activation screen of your user account";
$lang["activation_title"] = "Activation status";
$lang["activation_information_success"] = "The activation of your user account succeeded. You can now <a id=\"connectButton\" href=\"#\">sign-in</a> yourself.";
$lang["activation_information_danger"] = "The activation of your user account failed.";

$lang["forgotten_guide"] = "You forgot your password, welcome on the page that will let you recover your access";
$lang["forgotten_form_legend"] = "Access retrieving";
$lang["forgotten_form_mailInput"] = "Mail address";
$lang["forgotten_save"] = "Send me a mail !";
$lang["forgotten_success_title"] = "Recory in progress";
$lang["forgotten_success_information"] = "An email has been sent.<br>This email contains a new password. Be sure to change it as soon as possible.";
$lang["forgotten_mail_subject"] = "[OTB] I Forgot my password";
$lang["forgotten_mail_content"] = "Hello,

It seems that you forgot your password on VPN. Your new password is {password} .
Please change it as soon as you are connected.

The #VPN Team";

$lang["about_footer"] = "About";
$lang["vpn_footer"] = "<a href=\"https://www.armagnet.fr/vpn/\" target=\"_blank\">VPN</a> is an application provided by <a href=\"https://www.armagnet.fr\" target=\"_blank\">ArmagNet</a>";
?>