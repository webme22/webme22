<?php
if (file_exists(dirname(__FILE__)."/env.php")) {
    include (dirname(__FILE__)."/env.php");
}
$DB_HOST = isset($DB_HOST) ? $DB_HOST: "127.0.0.1,";
$DB_DATABASE = isset($DB_DATABASE) ? $DB_DATABASE:'hamayel';
$DB_USERNAME = isset($DB_USERNAME) ? $DB_USERNAME:'hamayel';
$DB_PASSWORD = isset($DB_PASSWORD) ? $DB_PASSWORD:'secret';

$siteUrl =  isset($siteUrl) ? $siteUrl : "https://alhamayel.emcan-group.com";
$RELATIVE_PATH = isset($RELATIVE_PATH) ? $RELATIVE_PATH : "/";

$MAIL_DRIVER=isset($MAIL_DRIVER)? $MAIL_DRIVER :'smtp';
$MAIL_HOST=isset($MAIL_HOST) ? $MAIL_HOST : 'smtp.mailtrap.io';
$MAIL_PORT=isset($MAIL_PORT) ? $MAIL_PORT : 2525;
$MAIL_USERNAME=isset($MAIL_USERNAME) ? $MAIL_USERNAME : null;
$MAIL_PASSWORD=isset($MAIL_PASSWORD) ? $MAIL_PASSWORD : null;
$MAIL_ENCRYPTION=isset($MAIL_ENCRYPTION) ? $MAIL_ENCRYPTION: null;
$MAIL_FROM_ADDRESS=isset($MAIL_FROM_ADDRESS) ? $MAIL_FROM_ADDRESS: "hello@example.com";
$MAIL_FROM_NAME=isset($MAIL_FROM_NAME) ? $MAIL_FROM_NAME: "Example";
$APP_KEY = isset($APP_KEY) ? $APP_KEY:base64_encode("SECRET_KEY");
$RECAPTCHA = isset($RECAPTCHA) ? $RECAPTCHA:base64_encode("SECRET_KEY");
