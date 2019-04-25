<?php
include_once 'YclientsApi.php';

/**
 * @see http://docs.yclients.apiary.io
 */
class YclientsApiClient
{
    /*
   * URL для RestAPI
   */
    const PARTNER = 'eucr5aeat4wfwnngrd8p';

    /*
     * Методы используемые в API
     */
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    private $appToken = null;
    private $appUrl = null;

    /* @var modX $modx */
    public $modx = null;

    /* @var yClients $yClients */
    public $yClients = null;

    /* @var CacheManager|null $cachemanager */
    protected $cachemanager = null;


    public function __construct(yClients $yClients, $config = array())
    {
        $this->yClients = $yClients;
        $this->modx = $yClients->modx;
        $appUrl = $this->modx->getOption('yclients_api_url', $config, 'https://api.yclients.com/api/v1/');
        $this->appUrl = trim($appUrl, '/');


        if (!$this->cachemanager) {
            if (!class_exists('CacheManager')) {
                include_once dirname(__FILE__) . '/cache.class.php';
            }
            $this->cachemanager = new CacheManager($this->modx);
        }


    }

    /**
     * Запись токена пользователя для первого запуска
     * @return bool
     */
    public function setToken()
    {
        $token = trim($this->modx->getOption('yclients_api_token', null, ''));
        if (!empty($token)) {
            $this->appToken = $token;
            return true;
        }

        $login = trim($this->modx->getOption('yclients_api_login', null, ''));
        if (empty($login)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": " . $this->modx->lexicon('yclients_err_login'));
            return false;
        }

        $password = trim($this->modx->getOption('yclients_api_password', null, ''));
        if (empty($password)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": " . $this->modx->lexicon('yclients_err_password'));
            return false;
        }

        $response = $this->request('auth', array(
            'login' => $login,
            'password' => $password,
        ), 'POST', false);

        if (isset($response['user_token'])) {
            $token = trim($response['user_token']);
            if ($SystemSetting = $this->modx->getObject('modSystemSetting', array('key' => 'yclients_api_token'))) {
                $SystemSetting->set('value', $token);
                if ($SystemSetting->save()) {
                    $this->appToken = $token;
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * @param string $service
     * @param array|null $params
     * @param string $method
     * @param boolean $auth
     *
     * @return mixed
     * @throws YclientsException
     */
    public function request($service = '', $params = null, $method = 'POST', $auth = true)
    {
        $service = trim($service, '/');

        $headers = ['Content-Type: application/json'];

        if ($auth) {
            if (empty($this->appToken)) {
                $this->yClients->error('yclients_err_token');
            }
        }
        $token = self::PARTNER;
        if ($service == 'auth') {
            $token .= (is_string($this->appToken) ? ', User ' . $this->appToken : '');
        }

        $headers[] = 'Authorization: Bearer ' . $token;


        // Запись ключа
        $response = false;
        $this->cachemanager->setKey($service, $params);
        if ($res = $this->cachemanager->get()) {
            $response = $this->response = $res['response'];
            $this->info = $response['info'];
            $this->error = $response['error'];
        }

        if (!$response) {
            $this->requestCurl($service, $params, $method, $headers);
            $this->cachemanager->set($this->getResponse());
        }


        $res = $this->getResponse();
        if ($this->error) {
            $this->yClients->error($this->error);
        }

        return $res['response'];
    }


    /**
     * Выполнение непосредственно запроса с помощью curl
     *
     * @param string $service
     * @param array|null $parameters
     * @param string $method
     * @param array $headers
     * @param integer $timeout
     * @return array
     * @access protected
     * @throws YclientsException
     */
    protected function requestCurl($service, $parameters = [], $method = 'GET', $headers = [], $timeout = 30)
    {
        $url = $this->appUrl . '/' . $service;

        $ch = curl_init();
        if (count($parameters)) {
            if ($method === self::METHOD_GET) {
                $url .= '?' . http_build_query($parameters);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
            }
        }

        if ($method === self::METHOD_POST) {
            curl_setopt($ch, CURLOPT_POST, true);
        } elseif ($method === self::METHOD_PUT) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, self::METHOD_PUT);
        } elseif ($method === self::METHOD_DELETE) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, self::METHOD_DELETE);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);

        if (count($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);
        $response = json_decode($response, true);
        $this->response = $response;
        $this->info = curl_getinfo($ch);
        if (isset($response['errors'])) {
            $this->error = $response['errors'];
        } else {
            $this->error = curl_error($ch);
        }
        curl_close($ch);
        return $response;
    }


    protected $response;
    protected $error;
    protected $info;
    protected $response_status_lines;

    public function getResponse()
    {
        return array(
            'response' => $this->response,
            'error' => $this->error,
            'info' => $this->info,
            'response_status_lines' => $this->response_status_lines,
        );

    }
}