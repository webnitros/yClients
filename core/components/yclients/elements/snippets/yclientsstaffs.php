<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var yClients $yClients */
$yClients = $modx->getService('yClients', 'yClients', MODX_CORE_PATH . 'components/yclients/model/', $scriptProperties);
if (!$yClients) {
    return 'Could not load yClients class!';
}


$tpl = $modx->getOption('tpl', $scriptProperties, 'tpl.yclients.staffs.row');
$tplOuter = $modx->getOption('tplOuter', $scriptProperties, 'tpl.yclients.staffs.outer');


$company_id = $modx->getOption('company_id', $scriptProperties, null);
if (empty($company_id)) {
    $company_id = $modx->getOption('yclients_company_id');
}

if (empty($company_id)) {
    return 'Count not company_id';
}

$date = date('Y-m-d', strtotime('+1 days', time()));
$response = null;
if (!$yClients->loadService()) {
    return false;
}
$rows = $yClients->getStaffs($company_id);
$output = array();
if ($rows and count($rows) > 0) {
    foreach ($rows as $staff) {
        $id = $staff['id'];

        if (strripos($staff['avatar'], 'no-master') !== false) {
            $staff['avatar'] = 'assets/components/yclients/images/no-master.png';
            $staff['avatar_big'] = 'assets/components/yclients/images/no-master-big.png';
        }

        $name = $staff['name'];
        if ($name == 'график') {
            continue;
        }
        $output[] = $yClients->pdoTools->getChunk($tpl, $staff);
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