<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var yClients $yClients */
$yClients = $modx->getService('yClients', 'yClients', MODX_CORE_PATH . 'components/yclients/model/', $scriptProperties);
if (!$yClients) {
    return 'Could not load yClients class!';
}
$yClients->initialize($modx->context->key);

/** @var pdoFetch $pdoFetch */
if (!$modx->loadClass('pdofetch', MODX_CORE_PATH . 'components/pdotools/model/pdotools/', false, true)) {
    return false;
}
$pdoFetch = new pdoFetch($modx, array());
$pdoFetch->addTime('pdoTools loaded.');


// Do your snippet code here. This demo grabs 5 items from our custom table.
$company_id = $modx->getOption('company_id', $scriptProperties, null);
if (empty($company_id)) {
    $company_id = $modx->getOption('yclients_company_id');
}

if (empty($company_id)) {
    return 'Count not company_id';
}

$from = $modx->getOption('from', $scriptProperties, null);
$till = $modx->getOption('till', $scriptProperties, null);
$day = $modx->getOption('day', $scriptProperties, date('Y-m-d', time()));


$tpl = $modx->getOption('tpl', $scriptProperties, 'tpl.yClients.event.row');
$tplOuter = $modx->getOption('tplOuter', $scriptProperties, 'tpl.yClients.event.outer');
$tplEmpty = $modx->getOption('tplEmpty', $scriptProperties, 'tpl.yClients.event.empty');


$formatDate = 'd-m-Y';
$today = date($formatDate, time());
$tomorrow = date($formatDate, strtotime('+1 days', time()));


$response = null;
if (!$yClients->loadService()) {
    return false;
}

$params = array();
if ($from) {
    $params['from'] = $from;
}
if ($till) {
    $params['till'] = $till;
}

if (!$from and !$till) {
    switch ($day) {
        case 'today':
            $today = date('Y-m-d', time());
            $params = array(
                'from' => $today,
                'till' => $today
            );
            break;
        case 'tomorrow':
            $tomorrow = date('Y-m-d', strtotime('+1 days', time()));
            $params = array(
                'from' => $tomorrow,
                'till' => $tomorrow
            );
            break;
        default:
            break;
    }
}

#$BookStaff = $yClients->getBookStaff($company_id);
$response = $yClients->getGroupEventsFrom($company_id, $params);
#$dates = $yClients->getBookDates($company_id);


$rows = $response['success'] ? $response['data'] : array();
$output = array();
if ($rows and count($rows) > 0) {
    $idx = 0;
    foreach ($rows as $row) {
        $idx++;
        $row['idx'] = $idx;
        $date = date($formatDate, strtotime($row['date']));
        $row['time'] = date('H:i', strtotime($row['date']));
        $row['places'] = $row['capacity'] - $row['records_count'];

        $row['date_modification'] = date('d.m.y', strtotime($date)) . ' ' . $row['time'];
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