<?php

class CacheManager
{
    /** @var modX $modx */
    protected $modx;
    /** @var string|null $key */
    protected $key;
    /** @var modCacheManager $cacheManager */
    protected $cacheManager;

    public $lifetime = 10000;
    public $options = array();

    public function __construct(modX $modx)
    {
        $this->modx = $modx;
        $this->cacheManager = $modx->getCacheManager();

        $this->options = array(
            xPDO::OPT_CACHE_KEY => 'default/yclients'
        );

        $this->lifetime = $this->modx->getOption('yclients_lifetime', null, 10000);

    }

    /**
     * Запись ключа для запроса
     * @param array $request
     */
    public function setKey($key, $request)
    {
        $request = $this->modx->toJSON($request);
        $hash = md5($request);
        $this->key = 'response/'.$key.'/' . $hash;
    }


    /**
     * @return bool|mixed
     */
    public function get()
    {
        $data = $this->cacheManager->get($this->key, $this->options);
        return !empty($data) ? $data : false;
    }

    /**
     * @param mixed $data
     * @return bool
     */
    public function set($data)
    {
        if (!$response = $this->cacheManager->set($this->key, $data, $this->lifetime, $this->options)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Не удалось записать в кэш ответ от сервера");
        }
        return $response;
    }

}