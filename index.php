<?php
use Core\Controller;

use Core\Config;
//Constante que define que o usuário está acessando páginas internas através da página "index.php".
//define('M3L2C1TECH2023', true);

require_once __DIR__.'/core/Config.php';
require_once __DIR__.'/vendor/autoload.php';

//$this->config();

$Rota = new Controller();
$Rota->carregar();