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

$lang["date_format"] = "d/m/Y";
$lang["time_format"] = "H:i";
$lang["datetime_format"] = "le {date} à {time}";

$lang["common_validate"] = "Valider";
$lang["common_delete"] = "Supprimer";
$lang["common_activate"] = "Activer";
$lang["common_add"] = "Ajouter";
$lang["common_modify"] = "Modifier";
$lang["common_reset"] = "Reset";
$lang["common_connect"] = "Connecter";
$lang["common_order"] = "Commander";

$lang["language_fr"] = "Français";
$lang["language_en"] = "Anglais";
$lang["language_de"] = "Allemand";

$lang["vpn_title"] = "ArmagNet VPN - Le VPN Associatif";

$lang["menu_logout"] = "Se déconnecter";
$lang["menu_login"] = "Se connecter";
$lang["menu_language"] = "Langue : {language}";
$lang["menu_index"] = "Accueil";
$lang["menu_vpns"] = "VPN";
$lang["menu_create_vpn"] = "Création d'un accès";
$lang["menu_servers"] = "Serveurs";

$lang["login_title"] = "Identifiez vous";
$lang["login_loginInput"] = "Identifiant";
$lang["login_passwordInput"] = "Mot de passe";
$lang["login_button"] = "Me connecter";
$lang["login_rememberMe"] = "Se souvenir de moi";
$lang["register_link"] = "ou m'enregistrer";
$lang["forgotten_link"] = "j'ai oublié mon mot de passe";

$lang["breadcrumb_index"] = "Accueil";
$lang["breadcrumb_vpns"] = "VPN";
$lang["breadcrumb_payment"] = "Paiement";
$lang["breadcrumb_create_vpn"] = "Création d'un accès VPN";
$lang["breadcrumb_servers"] = "Serveurs";

$lang["index_guide"] = "L'interface de gestion de votre VPN associatif.";

$lang["payment_done_guide"] = "Le paiement de votre commande a été effectué avec succès";
$lang["payment_failed_guide"] = "Le paiement de votre commande a échoué";
$lang["payment_referer_link"] = "Retour à votre page d'origine";
$lang["payment_home_link"] = "Retour à l'accueil";

$lang["vpn_configuration_label"] = "Libellé";
$lang["vpn_configuration_retrievefile"] = "Récupérer le fichier";
$lang["vpn_configuration_key"] = "key";
$lang["vpn_configuration_nokey"] = "Pas de clé privé disponible, vous avez dû la générer";
$lang["vpn_configuration_cert"] = "cert";
$lang["vpn_configuration_cacert"] = "cacert";
$lang["vpn_configuration_dh"] = "dh";
$lang["vpn_configuration_dev"] = "dev";
$lang["vpn_configuration_proto"] = "proto";
$lang["vpn_configuration_cipher"] = "cipher";
$lang["vpn_configuration_complzo"] = "comp-lzo";
$lang["vpn_configuration_remoteip"] = "remote ip";
$lang["vpn_configuration_remoteport"] = "remote port";
$lang["vpn_configuration_remotecerttls"] = "remote cert tls";
$lang["vpn_configuration_ovpn"] = "Fichier de configuration OpenVpn";
$lang["vpn_configuration_end_date"] = "Date limite de validité";
$lang["vpn_log_since"] = "Connecté depuis";
$lang["vpn_log_update"] = "Dernière mise-à-jour";
$lang["vpn_log_upload_rate"] = "Vitesse de téléversement";
$lang["vpn_log_download_rate"] = "Vitesse de téléchargement";
$lang["vpn_log_upload"] = "Téléversement";
$lang["vpn_log_download"] = "Téléchargement";

$lang["error_login_ban"] = "Votre IP a été bloquée pour 10mn.";
$lang["error_login_bad"] = "Vérifier vos identifiants, l'identification a échouée.";

$lang["mypreferences_guide"] = "Changer mes préférences.";
$lang["mypreferences_form_legend"] = "Configuration de vos accès";
$lang["mypreferences_form_passwordInput"] = "Mot de passe";
$lang["mypreferences_form_passwordPlaceholder"] = "le mot de passe de connexion";
$lang["mypreferences_form_languageInput"] = "Langage";
$lang["mypreferences_validation_mail_empty"] = "Le champ mail ne peut être vide";
$lang["mypreferences_validation_mail_not_valid"] = "Cette adresse mail n'est pas une adresse valide";
$lang["mypreferences_validation_mail_already_taken"] = "Cette adresse mail est déjà prise";
$lang["mypreferences_form_mailInput"] = "Adresse mail";
$lang["mypreferences_save"] = "Sauver mes préférences";

$lang["register_guide"] = "Bienvenue sur la page d'enregistrement d'VPN";
$lang["register_form_legend"] = "Configuration de votre accès";
$lang["register_form_loginInput"] = "Identifiant";
$lang["register_form_loginHelp"] = "Utilisez de préférence votre identifiant Twitter si vous voulez recevoir des notifications sur Twitter";
$lang["register_form_mailInput"] = "Adresse mail";
$lang["register_form_passwordInput"] = "Mot de passe";
$lang["register_form_passwordHelp"] = "Votre mot de passe ne doit pas forcement contenir de caractères bizarres, mais doit de préférence être long et mémorisable";
$lang["register_form_confirmationInput"] = "Confirmation du mot de passe";
$lang["register_form_languageInput"] = "Langage";
$lang["register_form_iamabot"] = "Je suis un robot et je ne sais pas décocher une case";
$lang["register_form_notificationInput"] = "Notification pour validation";
$lang["register_form_notification_none"] = "Aucune";
$lang["register_form_notification_mail"] = "Par mail";
$lang["register_form_notification_simpledm"] = "Par simple DM";
$lang["register_form_notification_dm"] = "DM multiple";
$lang["register_success_title"] = "Enregistrement réussi";
$lang["register_success_information"] = "Votre enregistrement a réussi.
<br>Vous allez recevoir un mail avec un lien à cliquer permettant l'activation de votre compte.";
$lang["register_mail_subject"] = "[OTB] Mail d'enregistrement";
$lang["register_mail_content"] = "Bonjour {login},

Il semblerait que vous vous soyez enregistré sur VPN. Pour confirmer votre enregistrement, veuillez cliquer sur le lien ci-dessous :
{activationUrl}

L'équipe #VPN";
$lang["register_save"] = "S'enregistrer";
$lang["register_validation_user_empty"] = "Le champ utilisateur ne peut être vide";
$lang["register_validation_user_already_taken"] = "Cet utilisateur est déjà pris";
$lang["register_validation_mail_empty"] = "Le champ mail ne peut être vide";
$lang["register_validation_mail_not_valid"] = "Cette adresse mail n'est pas une adresse valide";
$lang["register_validation_mail_already_taken"] = "Cette adresse mail est déjà prise";
$lang["register_validation_password_empty"] = "Le champ mot de passe ne peut être vide";

$lang["activation_guide"] = "Bienvenue sur l'écran d'activation de votre compte";
$lang["activation_title"] = "Statut de votre activation";
$lang["activation_information_success"] = "L'activation de votre compte utilisateur a réussi. Vous pouvez maintenant vous <a id=\"connectButton\" href=\"#\">identifier</a>.";
$lang["activation_information_danger"] = "L'activation de votre compte utilisateur a échoué.";

$lang["forgotten_guide"] = "Vous avez oublié votre mot de passe, bienvenue sur la page qui vour permettra de récuperer un accès";
$lang["forgotten_form_legend"] = "Récupération d'accès";
$lang["forgotten_form_mailInput"] = "Adresse mail";
$lang["forgotten_save"] = "Envoyez moi un mail !";
$lang["forgotten_success_title"] = "Récupération en cours";
$lang["forgotten_success_information"] = "Un mail vous a été envoyé.<br>Ce mail contient un nouveau mot de passe. Veillez à le changer aussitôt que possible.";
$lang["forgotten_mail_subject"] = "[OTB] J'ai oublié mon mot de passe";
$lang["forgotten_mail_content"] = "Bonjour,

Il semblerait que vous ayez oublié votre mot de passe sur VPN. Votre nouveau mot de passe est {password} .
Veuillez le changer aussitôt que vous serez connecté.

L'équipe #VPN";

$lang["about_footer"] = "À Propos";
$lang["vpn_footer"] = "<a href=\"https://www.armagnet.fr/vpn/\" target=\"_blank\">VPN</a> est une application fournie par <a href=\"https://www.armagnet.fr\" target=\"_blank\">ArmagNet</a>";
?>