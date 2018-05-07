<?php

if (!file_exists('../vendor/autoload.php')){
	echo '<p>Vous devez installer les dépendances du projet avec la commande <code>composer install</code>. En effet, ceux-ci ne sont pas versionnés.</p>';
	die();
}
require '../vendor/autoload.php';

if (!file_exists('../app/config.php')){
	echo '<p>Vous devez créer le fichier <code>app/config.php</code>, en créant une copie du fichier <code>app/config.dist.php</code></p>';
	die();
}
require '../app/config.php';

$app = new OSW3\App();
$app->run();