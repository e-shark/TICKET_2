<?php

namespace frontend\modules\facilityeq;

/**
 * catalog module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    //public $layout= '/facilityeq';    //---Will use application layout
    public $controllerNamespace = 'frontend\modules\facilityeq\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
