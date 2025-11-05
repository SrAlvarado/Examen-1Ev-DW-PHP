<?php
require_once __DIR__ . '/utils/SessionHelper.php';

$pagina_destino = SessionHelper::getRedirectPage();

header("Location: " . $pagina_destino);
exit;