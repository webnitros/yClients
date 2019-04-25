<?php

/**
 * @see http://docs.yclients.apiary.io
 */
class YclientsApi
{
    /*
     * URL для RestAPI
     */
    const URL = 'https://api.yclients.com/api/v1';

    /*
     * Методы используемые в API
     */
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    /**
     * Токен доступа для авторизации партнёра
     *
     * @var string
     * @access private
     */
    private $tokenPartner;

    /**
     * @param string $tokenPartner
     * @return void
     * @access public
     */
    public function __construct($tokenPartner = null)
    {
        $this->setTokenPartner($tokenPartner);
    }

    /**
     * Утановка токена можно сделать отдельно т.к. есть запросы не
     * требующие авторизации партнёра
     *
     * @param string $tokenPartner
     * @return self
     * @access public
     */
    public function setTokenPartner($tokenPartner)
    {
        $this->tokenPartner = $tokenPartner;

        return $this;
    }

    public function getTokenPartner()
    {
        return $this->tokenPartner;
    }

    /**
     * Получаем токен пользователя по логину-паролю
     *
     * @param string $login
     * @param string $password
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/0/0/0
     * @throws YclientsException
     */
    public function getAuth($login, $password)
    {
        return $this->request('auth', [
            'login' => $login,
            'password' => $password,
        ], self::METHOD_POST);
    }

    /**
     * Получаем настройки формы бронирования
     *
     * @param integer $id
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/-/0/0
     * @throws YclientsException
     */
    public function getBookform($id)
    {
        return $this->request('bookform/' . $id);
    }

    /**
     * Получаем параметры интернационализации
     *
     * @param string $locale - ru-RU, lv-LV, en-US, ee-EE, lt-LT, de-DE, uk-UK
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/-/1/0
     * @throws YclientsException
     */
    public function getI18n($locale = 'ru-RU')
    {
        return $this->request('i18n/' . $locale);
    }

    /**
     * Получить список услуг доступных для бронирования
     *
     * @param integer $companyId
     * @param integer $staffId - ID сотрудника. Фильтр по идентификатору сотрудника
     * @param \DateTime $datetime - дата (в формате iso8601). Фильтр по дате
     *                              бронирования услуги (например '2005-09-09T18:30')
     * @param array $serviceIds - ID услуг. Фильтр по списку идентификаторов уже
     *                            выбранных (в рамках одной записи) услуг. Имеет
     *                            смысл если зада фильтр по мастеру и дате.
     * @param array $eventIds - ID акций. Фильтр по списку идентификаторов уже выбранных
     *                          (в рамках одной записи) акций. Имеет смысл если зада
     *                          фильтр по мастеру и дате.
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/-/2/0
     * @throws YclientsException
     */
    public function getBookServices(
        $companyId,
        $staffId = null,
        \DateTime $datetime = null,
        array $serviceIds = null,
        array $eventIds = null
    ) {
        $parameters = [];

        if ($staffId !== null) {
            $parameters['staff_id'] = $staffId;
        }

        if ($datetime !== null) {
            $parameters['datetime'] = $datetime->format(\DateTime::ISO8601);
        }

        if ($serviceIds !== null) {
            $parameters['service_ids'] = $serviceIds;
        }

        if ($eventIds !== null) {
            $parameters['event_ids'] = $eventIds;
        }

        return $this->request('book_services/' . $companyId, $parameters);
    }

    /**
     * Получить список сотрудников доступных для бронирования
     *
     * @param integer $companyId
     * @param integer $staffId - ID сотрудника. Фильтр по идентификатору сотрудника
     * @param \DateTime $datetime - дата (в формате iso8601). Фильтр по дате
     *                              бронирования услуги (например '2005-09-09T18:30')
     * @param array $serviceIds - ID услуг. Фильтр по списку идентификаторов уже
     *                            выбранных (в рамках одной записи) услуг. Имеет
     *                            смысл если зада фильтр по мастеру и дате.
     * @param array $eventIds - ID акций. Фильтр по списку идентификаторов уже выбранных
     *                          (в рамках одной записи) акций. Имеет смысл если зада
     *                          фильтр по мастеру и дате.
     * @param bool $withoutSeances - Отключает выдачу ближайших свободных сеансов,
     *                               ускоряет получение данных.
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/-/3/0
     * @throws YclientsException
     */
    public function getBookStaff(
        $companyId,
        $staffId = null,
        \DateTime $datetime = null,
        array $serviceIds = null,
        array $eventIds = null,
        $withoutSeances = false
    ) {
        $parameters = [];

        if ($staffId !== null) {
            $parameters['staff_id'] = $staffId;
        }

        if ($datetime !== null) {
            $parameters['datetime'] = $datetime->format(\DateTime::ISO8601);
        }

        if ($serviceIds !== null) {
            $parameters['service_ids'] = $serviceIds;
        }

        if ($eventIds !== null) {
            $parameters['event_ids'] = $eventIds;
        }

        if ($withoutSeances) {
            $parameters['without_seances'] = true;
        }

        return $this->request('book_staff/' . $companyId, $parameters);
    }

    /**
     * Получить список дат доступных для бронирования
     *
     * @param integer $companyId
     * @param integer $staffId - ID сотрудника. Фильтр по идентификатору сотрудника
     * @param array $serviceIds - ID услуг. Фильтр по списку идентификаторов уже
     *                            выбранных (в рамках одной записи) услуг. Имеет
     *                            смысл если зада фильтр по мастеру и дате.
     * @param \DateTime $date - Фильтр по месяцу бронирования (например '2015-09-01')
     * @param array $eventIds - ID акций. Фильтр по списку идентификаторов уже выбранных
     *                          (в рамках одной записи) акций. Имеет смысл если зада
     *                          фильтр по мастеру и дате.
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/-/4/0
     * @throws YclientsException
     */
    public function getBookDates(
        $companyId,
        $staffId = null,
        array $serviceIds = null,
        \DateTime $date = null,
        array $eventIds = null
    ) {
        $parameters = [];

        if ($staffId !== null) {
            $parameters['staff_id'] = $staffId;
        }

        if ($date !== null) {
            $parameters['date'] = $date->format('Y-m-d');
        }

        if ($serviceIds !== null) {
            $parameters['service_ids'] = $serviceIds;
        }

        if ($eventIds !== null) {
            $parameters['event_ids'] = $eventIds;
        }

        return $this->request('book_dates/' . $companyId, $parameters);
    }

    /**
     * Получить список сеансов доступных для бронирования
     *
     * @param integer $companyId
     * @param integer $staffId - ID сотрудника. Фильтр по идентификатору сотрудника
     * @param \DateTime $date - Фильтр по месяцу бронирования (например '2015-09-01')
     * @param array $serviceIds - ID услуг. Фильтр по списку идентификаторов уже
     *                            выбранных (в рамках одной записи) услуг. Имеет
     *                            смысл если зада фильтр по мастеру и дате.
     * @param array $eventIds - ID акций. Фильтр по списку идентификаторов уже выбранных
     *                          (в рамках одной записи) акций. Имеет смысл если зада
     *                          фильтр по мастеру и дате.
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/-/5/0
     * @throws YclientsException
     */
    public function getBookTimes(
        $companyId,
        $staffId,
        \DateTime $date,
        array $serviceIds = null,
        array $eventIds = null
    ) {
        $parameters = [];

        if ($serviceIds !== null) {
            $parameters['service_ids'] = $serviceIds;
        }

        if ($eventIds !== null) {
            $parameters['event_ids'] = $eventIds;
        }

        return $this->request('book_times/' . $companyId . '/' . $staffId . '/' . $date->format('Y-m-d'), $parameters);
    }

    /**
     * Отправить СМС код подтверждения номера телефона
     *
     * @param integer $companyId
     * @param string $phone - Телефон, на который будет отправлен код, вида 79991234567
     * @param string $fullname - Имя клиента
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/-/6/0
     * @throws YclientsException
     */
    public function postBookCode($companyId, $phone, $fullname = null)
    {
        $parameters = [
            'phone' => $phone
        ];

        if ($fullname !== null) {
            $parameters['fullname'] = $fullname;
        }

        return $this->request('book_code/' . $companyId, $parameters, self::METHOD_POST);
    }

    /**
     * Проверить параметры записи
     *
     * @param integer $companyId
     * @param array $appointments - Массив записей со следующими полями:
     *                              integer id - Идентификатор записи
     *                              array services - Массив идентификторов услуг
     *                              array events - Массив идентификторов акций
     *                              integer staff_id - Идентификатор специалиста
     *                              string datetime - Дата и время сеанса в формате ISO8601 (2015-09-29T13:00:00+04:00)
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/-/7/0
     * @throws YclientsException
     */
    public function postBookCheck($companyId, array $appointments)
    {
        // проверим наличие обязательных параметров
        foreach ($appointments as $appointment) {
            if (!isset($appointment['id'], $appointment['staff_id'], $appointment['datetime'])) {
                throw new YclientsException('Запись должна содержать все обязательные поля: id, staff_id, datetime.');
            }
        }

        return $this->request('book_check/' . $companyId, $appointments, self::METHOD_POST);
    }

    /**
     * Создать запись на сеанс
     *
     * @param integer $companyId
     * @param array $person - Массив обязательных данных клиента со следующими полями:
     *                        string phone - Телефон клиента вида 79161502239
     *                        string fullname
     *                        string email
     * @param array $appointments - Массив записей со следующими полями:
     *                              integer id - Идентификатор записи для обратной связи
     *                              array services - Массив идентификторов услуг
     *                              array events - Массив идентификторов акций
     *                              integer staff_id - Идентификатор специалиста
     *                              string datetime - Дата и время сеанса в формате ISO8601 (2015-09-29T13:00:00+04:00)
     * @param string $code - Код подтверждения номера телефона
     * @param array $notify - Массив используемых нотификацией со следующими ключами:
     *                        string notify_by_sms - За какое кол-во часов напоминанить по смс о записи (0 если не нужно)
     *                        string notify_by_email - За какое кол-во часов напоминанить по email о записи (0 если не нужно)
     * @param string $comment - Комментарий к записи
     * @param string $apiId - Внешний идентификатор записи
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/-/8/0
     * @throws YclientsException
     */
    public function postBookRecord(
        $companyId,
        array $person,
        array $appointments,
        $code = null,
        array $notify = null,
        $comment = null,
        $apiId = null
    ) {
        $parameters = [];

        // проверим наличие обязательных параметров клиента
        if (!isset($person['phone'], $person['fullname'], $person['email'])) {
            throw new YclientsException('Клиент должен содержать все обязательные поля: phone, fullname, email.');
        }

        $parameters = array_merge($parameters, $person);

        if (!count($appointments)) {
            throw new YclientsException('Должна быть хотя бы одна запись.');
        }

        // проверим наличие обязательных параметров записей
        foreach ($appointments as $appointment) {
            if (!isset($appointment['id'], $appointment['staff_id'], $appointment['datetime'])) {
                throw new YclientsException('Запись должна содержать все обязательные поля: id, staff_id, datetime.');
            }
        }

        $parameters['appointments'] = $appointments;

        if ($notify) {
            if (isset($notify['notify_by_sms'])) {
                $parameters['notify_by_sms'] = $notify['notify_by_sms'];
            }
            if (isset($notify['notify_by_email'])) {
                $parameters['notify_by_email'] = $notify['notify_by_email'];
            }
        }

        if ($code !== null) {
            $parameters['code'] = $code;
        }

        if ($comment !== null) {
            $parameters['comment'] = $comment;
        }

        if ($apiId !== null) {
            $parameters['api_id'] = $apiId;
        }

        return $this->request('book_record/' . $companyId, $parameters, self::METHOD_POST);
    }

    /**
     * Авторизоваться по номеру телефона и коду
     *
     * @param string $phone - Телефон, на который будет отправлен код вида 79161005050
     * @param string $code - Код подтверждения номера телефона, высланный по смс
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/1/0/0
     * @throws YclientsException
     */
    public function postUserAuth($phone, $code)
    {
        $parameters = [
            'phone' => $phone,
            'code' => $code,
        ];

        return $this->request('user/auth', $parameters, self::METHOD_POST);
    }

    /**
     * Получить записи пользователя
     *
     * @param integer $recordId - ID записи, достаточно для удаления записи если пользователь
     *                            авторизован, получить можно из ответа bookRecord()
     * @param string $recordHash - HASH записи, обязательно для удаления записи если пользователь
     *                             не авторизован, получить можно из ответа bookRecord()
     * @param string $userToken - токен для авторизации пользователя, обязательный, если $recordHash не указан
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/1/1/0
     * @throws YclientsException
     */
    public function getUserRecords($recordId, $recordHash = null, $userToken = null)
    {
        if (!$recordHash && !$userToken) {
            trigger_error('getUserRecords() expected Argument 2 or Argument 3 required', E_USER_WARNING);
        }

        return $this->request('user/records/' . $recordId . '/' . $recordHash, [], self::METHOD_GET,
            $userToken ?: true);
    }

    /**
     * Удалить записи пользователя
     *
     * @param integer $recordId - ID записи, достаточно для удаления записи если пользователь
     *                            авторизован, получить можно из ответа bookRecord()
     * @param string $recordHash - HASH записи, обязательно для удаления записи если пользователь
     *                             не авторизован, получить можно из ответа bookRecord()
     * @param string $userToken - Токен для авторизации пользователя, обязательный, если $recordHash не указан
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/1/1/1
     * @throws YclientsException
     */
    public function deleteUserRecords($recordId, $recordHash = null, $userToken = null)
    {
        if (!$recordHash && !$userToken) {
            trigger_error('deleteUserRecords() expected Argument 2 or Argument 3 required', E_USER_WARNING);
        }

        return $this->request('user/records/' . $recordId . '/' . $recordHash, [], self::METHOD_DELETE,
            $userToken ?: true);
    }

    /**
     * Получить список компаний
     *
     * @param integer $groupId - ID сети компаний
     * @param bool $active - Если нужно получить только активные для онлайн-записи компании
     * @param bool $moderated - Если нужно получить только прошедшие модерацию компании
     * @param bool $forBooking - Если нужно получить поле next_slot по каждой компании
     * @param bool $my - Если нужно компании, на управление которыми пользователь имеет права ($userToken тогда обязательно)
     * @param string $userToken - Токен для авторизации пользователя, обязательный, если $my указан
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/2/0/0
     * @throws YclientsException
     */
    public function getCompanies(
        $groupId = null,
        $active = null,
        $moderated = null,
        $forBooking = null,
        $my = null,
        $userToken = null
    ) {
        if ($my && !$userToken) {
            trigger_error('getCompanies() expected Argument 6 if set Argument 5', E_USER_WARNING);
        }

        $parameters = [];

        if ($groupId !== null) {
            $parameters['group_id'] = $groupId;
        }

        if ($active !== null) {
            $parameters['active'] = $active;
        }

        if ($moderated !== null) {
            $parameters['moderated'] = $moderated;
        }

        if ($forBooking !== null) {
            $parameters['forBooking'] = $forBooking;
        }

        if ($my !== null) {
            $parameters['my'] = $my;
        }

        return $this->request('companies', $parameters, self::METHOD_GET, $userToken ?: true);
    }

    /**
     * Создать компанию
     *
     * @param array $fields - Остальные необязательные поля для создания компании
     * @param string $userToken - Токен для авторизации пользователя
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/2/0/1
     * @throws YclientsException
     */
    public function postCompany(array $fields, $userToken)
    {
        if (!isset($fields['title'])) {
            throw new YclientsException('Для создании компании обязательно название компании.');
        }

        return $this->request('companies', $fields, self::METHOD_POST, $userToken);
    }

    /**
     * Получить компанию
     *
     * @param integer $id
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/2/1/0
     * @throws YclientsException
     */
    public function getCompany($id)
    {
        return $this->request('company/' . $id);
    }

    /**
     * Изменить компанию
     *
     * @param integer $id
     * @param array $fields - Остальные необязательные поля для создания компании
     * @param string $userToken - Токен для авторизации пользователя
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/2/1/1
     * @throws YclientsException
     */
    public function putCompany($id, array $fields, $userToken)
    {
        return $this->request('company/' . $id, $fields, self::METHOD_PUT, $userToken);
    }

    /**
     * Удалить компанию
     *
     * @param integer $id
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/2/1/2
     * @throws YclientsException
     */
    public function deleteCompany($id)
    {
        return $this->request('company/' . $id, [], self::METHOD_DELETE);
    }

    /**
     * Получить список категорий услуг
     *
     * @param integer $companyId - ID компании
     * @param integer $categoryId - ID категории услуг
     * @param integer $staffId - ID сотрудника (для получения категорий, привязанных к сотруднику)
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/3/0/0
     * @throws YclientsException
     */
    public function getServiceCategories($companyId, $categoryId, $staffId = null)
    {
        $parameters = [];

        if ($staffId !== null) {
            $parameters['staff_id'] = $staffId;
        }

        return $this->request('service_categories/' . $companyId . '/' . $categoryId, $parameters);
    }

    /**
     * Создать категорию услуг
     *
     * @param integer $companyId - ID компании
     * @param integer $categoryId - ID категории услуг
     * @param array $fields - Обязательные поля для категории со следующими полями:
     *                        string title - Название категории
     *                        integer api_id - Внешний идентификатор записи
     *                        integer weight
     *                        array staff
     * @param string $userToken - Токен для авторизации пользователя
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/3/0/1
     * @throws YclientsException
     */
    public function postServiceCategories($companyId, $categoryId, $fields, $userToken)
    {
        return $this->request('service_categories/' . $companyId . '/' . $categoryId, $fields, self::METHOD_POST,
            $userToken);
    }

    /**
     * Получить категорию услуг
     *
     * @param integer $companyId - ID компании
     * @param integer $categoryId - ID категории услуг
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/3/1/0
     * @throws YclientsException
     */
    public function getServiceCategory($companyId, $categoryId)
    {
        return $this->request('service_category/' . $companyId . '/' . $categoryId);
    }

    /**
     * Изменить категорию услуг
     *
     * @param integer $companyId - ID компании
     * @param integer $categoryId - ID категории услуг
     * @param array $fields - Обязательные поля для категории со следующими полями:
     *                        string title - Название категории
     *                        integer weight
     *                        array staff
     * @param string $userToken - Токен для авторизации пользователя
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/3/1/1
     * @throws YclientsException
     */
    public function putServiceCategory($companyId, $categoryId, $fields, $userToken)
    {
        return $this->request('service_category/' . $companyId . '/' . $categoryId, $fields, self::METHOD_PUT,
            $userToken);
    }

    /**
     * Удалить категорию услуг
     *
     * @param integer $companyId - ID компании
     * @param integer $categoryId - ID категории услуг
     * @param string $userToken - Токен для авторизации пользователя
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/3/1/2
     * @throws YclientsException
     */
    public function deleteServiceCategory($companyId, $categoryId, $userToken)
    {
        return $this->request('service_category/' . $companyId . '/' . $categoryId, [], self::METHOD_DELETE,
            $userToken);
    }

    /**
     * Получить список услуг / конкретную услугу
     *
     * @param integer $companyId - ID компании
     * @param integer $serviceId - ID услуги, если нужно работать с конкретной услугой
     * @param integer $staffId - ID сотрудника, если нужно отфильтровать по сотруднику
     * @param integer $categoryId - ID категории, если нужно отфильтровать по категории
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/4/0//
     * @throws YclientsException
     */
    public function getServices($companyId, $serviceId = null, $staffId = null, $categoryId = null)
    {
        $parameters = [];

        if ($staffId !== null) {
            $parameters['staff_id'] = $staffId;
        }

        if ($categoryId !== null) {
            $parameters['category_id'] = $categoryId;
        }

        return $this->request('services/' . $companyId . '/' . $serviceId, $parameters);
    }

    /**
     * Создать услугу
     *
     * @param integer $companyId - ID компании
     * @param integer $serviceId - ID услуги
     * @param string $title - Название услуги
     * @param integer $categoryId - ID категории услуг
     * @param string $userToken - Токен для авторизации пользователя
     * @param array $fields - Остальные необязательные поля для услуги
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/4/0/0
     * @throws YclientsException
     */
    public function postServices($companyId, $serviceId, $categoryId, $title, $userToken, array $fields = null)
    {
        $parameters = [
            'category_id' => $categoryId,
            'title' => $title,
        ];

        $parameters = array_merge($parameters, $fields);

        return $this->request('services/' . $companyId . '/' . $serviceId, $parameters, self::METHOD_POST, $userToken);
    }

    /**
     * Изменить услугу
     *
     * @param integer $companyId - ID компании
     * @param integer $serviceId - ID услуги
     * @param string $title - Название услуги
     * @param integer $categoryId - ID категории услуг
     * @param string $userToken - Токен для авторизации пользователя
     * @param array $fields - Остальные необязательные поля для услуги
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/4/0/1
     * @throws YclientsException
     */
    public function putServices($companyId, $serviceId, $categoryId, $title, $userToken, array $fields = null)
    {
        $parameters = [
            'category_id' => $categoryId,
            'title' => $title,
        ];

        $parameters = array_merge($parameters, $fields);

        return $this->request('services/' . $companyId . '/' . $serviceId, $parameters, self::METHOD_PUT, $userToken);
    }

    /**
     * Удалить услугу
     *
     * @param integer $companyId - ID компании
     * @param integer $serviceId - ID услуги
     * @param string $userToken - Токен для авторизации пользователя
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/4/0/2
     * @throws YclientsException
     */
    public function deleteServices($companyId, $serviceId, $userToken)
    {
        return $this->request('services/' . $companyId . '/' . $serviceId, [], self::METHOD_DELETE, $userToken);
    }

    /**
     * Получить список акций / конкретную акцию
     *
     * @param integer $companyId - ID компании
     * @param integer $eventId - ID услуги, если нужно работать с конкретной услугой.
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/5//
     * @throws YclientsException
     */
    public function getEvents($companyId, $eventId = null)
    {
        return $this->request('events/' . $companyId . '/' . $eventId);
    }

    /**
     * Получить список сотрудников / конкретного сотрудника
     *
     * @param integer $companyId - ID компании
     * @param integer $staffId - ID сотрудника, если нужно работать с конкретным сотрудником
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/6//
     * @throws YclientsException
     */
    public function getStaff($companyId, $staffId = null)
    {
        return $this->request('staff/' . $companyId . '/' . $staffId);
    }

    /**
     * Добавить нового сотрудника
     *
     * @param integer $companyId - ID компании
     * @param integer $staffId - ID сотрудника
     * @param string $name - Имя сотрудника
     * @param string $userToken - Токен для авторизации пользователя
     * @param array $fields - Остальные необязательные поля для сотрудника
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/6/0/0
     * @throws YclientsException
     */
    public function postStaff($companyId, $staffId, $name, $userToken, array $fields = null)
    {
        $parameters = [
            'name' => $name,
        ];

        $parameters = array_merge($parameters, $fields);

        return $this->request('staff/' . $companyId . '/' . $staffId, $parameters, self::METHOD_POST, $userToken);
    }

    /**
     * Изменить сотрудника
     *
     * @param integer $companyId - ID компании
     * @param integer $staffId - ID сотрудника
     * @param array $fields - Остальные необязательные поля для услуги
     * @param string $userToken - Токен для авторизации пользователя
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/6/0/1
     * @throws YclientsException
     */
    public function putStaff($companyId, $staffId, array $fields, $userToken)
    {
        return $this->request('staff/' . $companyId . '/' . $staffId, $fields, self::METHOD_PUT, $userToken);
    }

    /**
     * Удалить сотрудника
     *
     * @param integer $companyId - ID компании
     * @param integer $staffId - ID сотрудника
     * @param string $userToken - Токен для авторизации пользователя
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/6/0/2
     * @throws YclientsException
     */
    public function deleteStaff($companyId, $staffId, $userToken)
    {
        return $this->request('staff/' . $companyId . '/' . $staffId, [], self::METHOD_DELETE, $userToken);
    }

    /**
     * Получить список клиентов
     *
     * @param integer $companyId - ID компании
     * @param string $userToken - Токен для авторизации пользователя
     * @param string $fullname
     * @param string $phone
     * @param string $email
     * @param string $page
     * @param string $count
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/7/0/0
     * @throws YclientsException
     */
    public function getClients(
        $companyId,
        $userToken,
        $fullname = null,
        $phone = null,
        $email = null,
        $page = null,
        $count = null
    ) {
        $parameters = [];

        if ($fullname !== null) {
            $parameters['fullname'] = $fullname;
        }

        if ($phone !== null) {
            $parameters['phone'] = $phone;
        }

        if ($email !== null) {
            $parameters['email'] = $email;
        }

        if ($page !== null) {
            $parameters['page'] = $page;
        }

        if ($count !== null) {
            $parameters['count'] = $count;
        }

        return $this->request('clients/' . $companyId, $parameters, self::METHOD_GET, $userToken);
    }

    /**
     * Добавить клиента
     *
     * @param integer $companyId - ID компании
     * @param string $name - Имя клиента
     * @param integer $phone - Телефон клиента
     * @param string $userToken - Токен для авторизации пользователя
     * @param array $fields - Остальные необязательные поля для клиента
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/7/0/1
     * @throws YclientsException
     */
    public function postClients($companyId, $name, $phone, $userToken, array $fields = null)
    {
        $parameters = [
            'name' => $name,
            'phone' => $phone,
        ];

        $parameters = array_merge($parameters, $fields);

        return $this->request('clients/' . $companyId, $parameters, self::METHOD_POST, $userToken);
    }

    /**
     * Получить клиента
     *
     * @param integer $companyId - ID компании
     * @param integer $id - ID клиента
     * @param string $userToken - Токен для авторизации пользователя
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/7/1/0
     * @throws YclientsException
     */
    public function getClient($companyId, $id, $userToken)
    {
        return $this->request('client/' . $companyId . '/' . $id, [], self::METHOD_GET, $userToken);
    }

    /**
     * Редактировать клиента
     *
     * @param integer $companyId - ID компании
     * @param integer $id - ID клиента
     * @param string $userToken - Токен для авторизации пользователя
     * @param array $fields
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/7/1/1
     * @throws YclientsException
     */
    public function putClient($companyId, $id, $userToken, array $fields)
    {
        return $this->request('client/' . $companyId . '/' . $id, $fields, self::METHOD_PUT, $userToken);
    }

    /**
     * Удалить клиента
     *
     * @param integer $companyId - ID компании
     * @param integer $id - ID клиента
     * @param string $userToken - Токен для авторизации пользователя
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/7/1/2
     * @throws YclientsException
     */
    public function deleteClient($companyId, $id, $userToken)
    {
        return $this->request('client/' . $companyId . '/' . $id, [], self::METHOD_DELETE, $userToken);
    }

    /**
     * Получить список записей
     *
     * @param integer $companyId - ID компании
     * @param string $userToken - Токен для авторизации пользователя
     * @param integer $page
     * @param integer $count
     * @param integer $staffId
     * @param integer $clientId
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param \DateTime $cStartDate
     * @param \DateTime $cEndDate
     * @param \DateTime $changedAfter
     * @param \DateTime $changedBefore
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/8/0/0
     * @throws YclientsException
     */
    public function getRecords(
        $companyId,
        $userToken,
        $page = null,
        $count = null,
        $staffId = null,
        $clientId = null,
        \DateTime $startDate = null,
        \DateTime $endDate = null,
        \DateTime $cStartDate = null,
        \DateTime $cEndDate = null,
        \DateTime $changedAfter = null,
        \DateTime $changedBefore = null
    ) {
        $parameters = [];

        if ($page !== null) {
            $parameters['page'] = $page;
        }

        if ($count !== null) {
            $parameters['count'] = $count;
        }

        if ($staffId !== null) {
            $parameters['staff_id'] = $staffId;
        }

        if ($clientId !== null) {
            $parameters['client_id'] = $clientId;
        }

        if ($startDate !== null) {
            $parameters['start_date'] = $startDate->format('Y-m-d');
        }

        if ($endDate !== null) {
            $parameters['end_date'] = $endDate->format('Y-m-d');
        }

        if ($cStartDate !== null) {
            $parameters['c_start_date'] = $cStartDate->format('Y-m-d');
        }

        if ($cEndDate !== null) {
            $parameters['c_end_date'] = $cEndDate->format('Y-m-d');
        }

        if ($changedAfter !== null) {
            $parameters['changed_after'] = $changedAfter->format(\DateTime::ISO8601);
        }

        if ($changedBefore !== null) {
            $parameters['changed_before'] = $changedBefore->format(\DateTime::ISO8601);
        }

        return $this->request('records/' . $companyId, $parameters, self::METHOD_GET, $userToken);
    }

    /**
     * Создать новую запись
     *
     * @param integer $companyId - ID компании
     * @param string $userToken - Токен для авторизации пользователя
     * @param integer $staffId
     * @param array $services
     * @param array $client
     * @param \DateTime $datetime
     * @param integer $seanceLength
     * @param bool $saveIfBusy
     * @param bool $sendSms
     * @param string $comment
     * @param integer $smsRemainHours
     * @param integer $emailRemainHours
     * @param integer $apiId
     * @param integer $attendance
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/8/0/1
     * @throws YclientsException
     */
    public function postRecords(
        $companyId,
        $userToken,
        $staffId,
        $services,
        $client,
        \DateTime $datetime,
        $seanceLength,
        $saveIfBusy,
        $sendSms,
        $comment = null,
        $smsRemainHours = null,
        $emailRemainHours = null,
        $apiId = null,
        $attendance = null
    ) {
        $parameters = [];

        if ($staffId !== null) {
            $parameters['staff_id'] = $staffId;
        }

        if ($services !== null) {
            $parameters['services'] = $services;
        }

        if ($client !== null) {
            $parameters['client'] = $client;
        }

        if ($datetime !== null) {
            $parameters['datetime'] = $datetime->format(\DateTime::ISO8601);
        }

        if ($seanceLength !== null) {
            $parameters['seance_length'] = $seanceLength;
        }

        if ($saveIfBusy !== null) {
            $parameters['save_if_busy'] = $saveIfBusy;
        }

        if ($sendSms !== null) {
            $parameters['send_sms'] = $sendSms;
        }

        if ($comment !== null) {
            $parameters['comment'] = $comment;
        }

        if ($smsRemainHours !== null) {
            $parameters['sms_remain_hours'] = $smsRemainHours;
        }

        if ($emailRemainHours !== null) {
            $parameters['email_remain_hours'] = $emailRemainHours;
        }

        if ($apiId !== null) {
            $parameters['api_id'] = $apiId;
        }

        if ($attendance !== null) {
            $parameters['attendance'] = $attendance;
        }

        return $this->request('records/' . $companyId, $parameters, self::METHOD_POST, $userToken);
    }

    /**
     * Получить запись
     *
     * @param integer $companyId - ID компании
     * @param integer $recordId
     * @param string $userToken - Токен для авторизации пользователя
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/8/1/0
     * @throws YclientsException
     */
    public function getRecord($companyId, $recordId, $userToken)
    {
        return $this->request('record/' . $companyId . '/' . $recordId, [], self::METHOD_GET, $userToken);
    }

    /**
     * Изменить запись
     *
     * @param integer $companyId - ID компании
     * @param integer $recordId
     * @param string $userToken - Токен для авторизации пользователя
     * @param array $fields
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/8/1/1
     * @throws YclientsException
     */
    public function putRecord($companyId, $recordId, $userToken, array $fields)
    {
        return $this->request('record/' . $companyId . '/' . $recordId, $fields, self::METHOD_PUT, $userToken);
    }

    /**
     * Удалить запись
     *
     * @param integer $companyId - ID компании
     * @param integer $recordId
     * @param string $userToken - Токен для авторизации пользователя
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/8/1/2
     * @throws YclientsException
     */
    public function deleteRecord($companyId, $recordId, $userToken)
    {
        return $this->request('record/' . $companyId . '/' . $recordId, [], self::METHOD_DELETE, $userToken);
    }

    /**
     * Изменить расписание работы сотрудника
     *
     * @param integer $companyId - ID компании
     * @param integer $staffId
     * @param string $userToken - Токен для авторизации пользователя
     * @param array $fields
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/9/0
     * @throws YclientsException
     */
    public function putSchedule($companyId, $staffId, $userToken, $fields)
    {
        return $this->request('schedule/' . $companyId . '/' . $staffId, $fields, self::METHOD_PUT, $userToken);
    }

    /**
     * Получить список дат для журнала
     *
     * @param integer $companyId - ID компании
     * @param \DateTime $date
     * @param integer $staffId
     * @param string $userToken - Токен для авторизации пользователя
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/10/0/0
     * @throws YclientsException
     */
    public function getTimetableDates($companyId, \DateTime $date, $staffId, $userToken)
    {
        $parameters = [];

        if ($staffId !== null) {
            $parameters['staff_id'] = $staffId;
        }

        return $this->request('timetable/dates/' . $companyId . '/' . $date->format('Y-m-d'), $parameters,
            self::METHOD_GET, $userToken);
    }

    /**
     * Получить список сеансов для журнала
     *
     * @param integer $companyId - ID компании
     * @param \DateTime $date
     * @param integer $staffId
     * @param string $userToken - Токен для авторизации пользователя
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/11/0/0
     * @throws YclientsException
     */
    public function getTimetableSeances($companyId, \DateTime $date, $staffId, $userToken)
    {
        return $this->request('timetable/seances/' . $companyId . '/' . $staffId . '/' . $date->format('Y-m-d'), [],
            self::METHOD_GET, $userToken);
    }

    /**
     * Получить комментарии
     *
     * @param integer $companyId - ID компании
     * @param string $userToken - Токен для авторизации пользователя
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param integer $staffId
     * @param integer $rating
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/12/0/0
     * @throws YclientsException
     */
    public function getComments(
        $companyId,
        $userToken,
        \DateTime $startDate = null,
        \DateTime $endDate = null,
        $staffId = null,
        $rating = null
    ) {
        $parameters = [];

        if ($startDate !== null) {
            $parameters['start_date'] = $startDate->format('Y-m-d');
        }

        if ($endDate !== null) {
            $parameters['end_date'] = $endDate->format('Y-m-d');
        }

        if ($staffId !== null) {
            $parameters['staff_id'] = $staffId;
        }

        if ($rating !== null) {
            $parameters['rating'] = $rating;
        }

        return $this->request('comments/' . $companyId, $parameters, self::METHOD_GET, $userToken);
    }

    /**
     * Получить пользователей компании
     *
     * @param integer $companyId - ID компании
     * @param string $userToken - Токен для авторизации пользователя
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/13/0/0
     * @throws YclientsException
     */
    public function getCompanyUsers($companyId, $userToken)
    {
        return $this->request('company_users/' . $companyId, [], self::METHOD_GET, $userToken);
    }

    /**
     * Получить кассы компании
     *
     * @param integer $companyId - ID компании
     * @param string $userToken - Токен для авторизации пользователя
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/14/0/0
     * @throws YclientsException
     */
    public function getAccounts($companyId, $userToken)
    {
        return $this->request('accounts/' . $companyId, [], self::METHOD_GET, $userToken);
    }

    /**
     * Отправить SMS
     *
     * @param integer $companyId - ID компании
     * @param string $userToken - Токен для авторизации пользователя
     * @param integer[] $clientIds - ID клиентов
     * @param string $text - Тест сообщения
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/14/0/0
     * @throws YclientsException
     */
    public function sendSMS($companyId, $userToken, $clientIds, $text)
    {
        $parameters = [];
        $parameters['client_ids'] = $clientIds;
        $parameters['text'] = $text;

        return $this->request('sms/clients/by_id/' . $companyId, $parameters, self::METHOD_POST, $userToken);
    }

    /**
     * Получить склады компании
     *
     * @param integer $companyId - ID компании
     * @param string $userToken - Токен для авторизации пользователя
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/15/0/0
     * @throws YclientsException
     */
    public function getStorages($companyId, $userToken)
    {
        return $this->request('storages/' . $companyId, [], self::METHOD_GET, $userToken);
    }

    /**
     * Получить настройки уведомлений о событиях
     *
     * @param integer $companyId - ID компании
     * @param string $userToken - Токен для авторизации пользователя
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/18/0/0
     * @throws YclientsException
     */
    public function getHooks($companyId, $userToken)
    {
        return $this->request('hooks_settings/' . $companyId, [], self::METHOD_GET, $userToken);
    }

    /**
     * Изменить настройки уведомлений о событиях
     *
     * @param integer $companyId - ID компании
     * @param array $fields
     * @param string $userToken - Токен для авторизации пользователя
     * @return array
     * @access public
     * @see http://docs.yclients.apiary.io/#reference/18/0/1
     * @throws YclientsException
     */
    public function postHooks($companyId, $fields, $userToken)
    {
        if (!isset($fields['url'])) {
            throw new YclientsException('Не передан обязательный параметр url');
        }
        if (!isset($fields['active'])) {
            throw new YclientsException('Не передан обязательный параметр active');
        }
        return $this->request('hooks_settings/' . $companyId, $fields, self::METHOD_POST, $userToken);
    }

    /**
     * Подготовка запроса
     *
     * @param string $url
     * @param array $parameters
     * @param string $method
     * @param bool|string $auth - если true, то авторизация партнёрская
     *                            если string, то авторизация пользовательская
     * @return array
     * @access protected
     * @throws YclientsException
     */
    protected function request($url, $parameters = [], $method = 'GET', $auth = true)
    {
        $headers = ['Content-Type: application/json'];
        if ($auth) {
            if (!$this->tokenPartner) {
                throw new YclientsException('Не указан токен партнёра');
            }
            $headers[] = 'Authorization: Bearer ' . $this->tokenPartner . (is_string($auth) ? ', User ' . $auth : '');
        }

        return $this->requestCurl($url, $parameters, $method, $headers);
    }

    /**
     * Выполнение непосредственно запроса с помощью curl
     *
     * @param string $url
     * @param array $parameters
     * @param string $method
     * @param array $headers
     * @param integer $timeout
     * @return array
     * @access protected
     * @throws YclientsException
     */
    protected function requestCurl($url, $parameters = [], $method = 'GET', $headers = [], $timeout = 30)
    {
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

        curl_setopt($ch, CURLOPT_URL, self::URL . '/' . $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);

        if (count($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);

        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if ($errno) {
            throw new YclientsException('Запрос произвести не удалось: ' . $error, $errno);
        }

        return json_decode($response, true);
    }
}