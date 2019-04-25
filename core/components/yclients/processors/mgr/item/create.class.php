<?php

class yClientsItemCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'yClientsItem';
    public $classKey = 'yClientsItem';
    public $languageTopics = ['yclients'];
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('yclients_item_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name])) {
            $this->modx->error->addField('name', $this->modx->lexicon('yclients_item_err_ae'));
        }

        return parent::beforeSet();
    }

}

return 'yClientsItemCreateProcessor';