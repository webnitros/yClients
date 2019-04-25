<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var yClients $yClients */
$yClients = $modx->getService('yClients', 'yClients', MODX_CORE_PATH . 'components/yclients/model/', $scriptProperties);
if (!$yClients) {
    return 'Could not load yClients class!';
}

$tplModel = $modx->getOption('tplModel', $scriptProperties, 'tpl.yClients.modal');
$html = $yClients->pdoTools->getChunk($tplModel, array());
$modx->regClientHTMLBlock($html);

$yClients->initialize($modx->context->key);

/** @var pdoFetch $pdoFetch */
if (!$modx->loadClass('pdofetch', MODX_CORE_PATH . 'components/pdotools/model/pdotools/', false, true)) {
    return false;
}
$pdoFetch = new pdoFetch($modx, array());
$pdoFetch->addTime('pdoTools loaded.');

// Do your snippet code here. This demo grabs 5 items from our custom table.
$tpl = $modx->getOption('tpl', $scriptProperties, 'tpl.yClients.event.outer');
return $pdoFetch->getChunk($tpl, array());
