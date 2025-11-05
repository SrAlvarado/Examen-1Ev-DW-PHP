<?php
require_once 'utils/SessionHelper.php';

$pagina_destino = SessionHelper::getRedirectPage();

header("Location: " . $pagina_destino);

exit;