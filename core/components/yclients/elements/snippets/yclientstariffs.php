<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var yClients $yClients */
$yClients = $modx->getService('yClients', 'yClients', MODX_CORE_PATH . 'components/yclients/model/', $scriptProperties);
if (!$yClients) {
    return 'Could not load yClients class!';
}

$tpl = $modx->getOption('tpl', $scriptProperties, 'tpl.yclients.tariffs.row');
$tplOuter = $modx->getOption('tplOuter', $scriptProperties, 'tpl.yclients.tariffs.outer');

$company_id = $modx->getOption('company_id', $scriptProperties, null);
if (empty($company_id)) {
    $company_id = $modx->getOption('yclients_company_id');
}

if (empty($company_id)) {
    return 'Count not company_id';
}

$response = null;
if (!$yClients->loadService()) {
    return false;
}
$rows = $yClients->getCategories($company_id);

$idx = 0;
$output = array();
if ($rows and count($rows) > 0) {
    foreach ($rows as $row) {
        $idx++;
        $service = $yClients->getService($company_id, array('category_id' => $row['id']));
        $row['service'] = $service;
        $row['idx'] = $idx;
        $output[] = $yClients->pdoTools->getChunk($tpl, $row);
    }
}


// Return output
if (!empty($returnIds) && is_string($rows)) {
    if (!empty($toPlaceholder)) {
        $modx->setPlaceholder($toPlaceholder, $rows);
    } else {
        return $rows;
    }
} elseif (!empty($toSeparatePlaceholders)) {
    $modx->setPlaceholders($output, $toSeparatePlaceholders);
} else {
    if (empty($outputSeparator)) {
        $outputSeparator = "\n";
    }
    $output = implode($outputSeparator, $output);

    if (!empty($tplOuter) && (!empty($wrapIfEmpty) || !empty($output))) {
        $output = $yClients->pdoTools->getChunk($tplOuter, array(
            'output' => $output,
        ));
    }

    if (!empty($toPlaceholder)) {
        $modx->setPlaceholder($toPlaceholder, $output);
    } else {
        return $output;
    }
}

