<?php
/** @var modX $modx */
switch ($modx->event->name) {
    case 'OnHandleRequest':
        if ($modx->context->key == 'mgr' or !isset($_REQUEST['action']) or $_REQUEST['service'] != 'yclients') {
            return '';
        }

        unset($_REQUEST['service']);


        /* @var yClients $yClients */
        $yClients = $modx->getService('yclients', 'yClients', MODX_CORE_PATH . 'components/yclients/model/');
        if (!$yClients->loadService()) {
            return '';
        }

        $success = true;
        $data = array();
        $action = $_REQUEST['action'];
        switch ($action) {
            case 'events':
                $date = (string)$_REQUEST['date'];
                $date = date('Y-m-d', strtotime($date));
                $params = array(
                    'from' => $date,
                    'till' => $date
                );

                $params['tplOuter'] = '';
                $data = $modx->runSnippet('yClientsSchedule', $params);
                if (empty($data)) {
                    $success = false;
                }

                break;
            case 'records':
            case 'activity':

                /* @var yClients $yClients */
                $yClients = $modx->getService('yclients', 'yClients', MODX_CORE_PATH . 'components/yclients/model/');
                if (!$yClients->loadService()) {
                    return '';
                }
                $params = $_REQUEST;

                $message = '';
                $success = true;
                $datetime = date("Y-m-d\TH:i:sO", strtotime($params['date']));


                switch ($action) {
                    case 'records':
                        $schedule = $params['schedule'];
                        $staff_id = $params['staff'];
                        $data = array(
                            'phone' => $params['phone'],
                            'email' => $params['email'],
                            'fullname' => $params['fullname'],
                            /*'appointments' => array(
                                array(
                                    'services' => array($schedule),
                                    'staff_id' => $staff_id,
                                    'datetime' => $datetime,
                                )
                            ),*/
                        );


                        $response = $yClients->createGroupsEvent($yClients->company_id, $activity, $params);
                        break;
                    case 'activity':
                        $activity = $params['activity'];
                        $params = array(
                            'phone' => $params['phone'],
                            'email' => $params['email'],
                            'fullname' => $params['fullname'],
                            'code' => '',
                            'comment' => '',
                            'notify_by_sms' => 0,
                            'notify_by_email' => 0,
                            'type' => 'mobile',
                        );
                        $response = array(
                            'success' => 1
                        );

                        #$response = $yClients->createGroupsEvent($yClients->company_id, $activity, $params);
                        break;
                    default:
                        break;
                }

                if (!$response['success']) {
                    if ($yClients->isError()) {
                        $success = false;
                        $message = $yClients->getErrors();
                    }
                }

                break;
            default:
                break;
        }


        $response = $modx->toJSON(array(
            'success' => $success,
            'message' => $message,
            'data' => $data
        ));
        exit($response);
        break;
}