<?php

namespace app\components;

use app\models\Claim;
use app\models\Client;
use app\models\User;
use php_rutils\RUtils;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * Class TagsHelper
 * @package app\components
 */
class TagsHelper extends Component
{
    /** @var User */
    public $user;

    /** @var string */
    public $content;

    /** @var IPrintable */
    public $printable;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @return string
     */
    public function handle()
    {
        $attributes = $this->printable->printValues();
        return strtr($this->content, $attributes);
    }
}