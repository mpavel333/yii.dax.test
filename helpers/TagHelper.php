<?php

namespace app\helpers;

use app\models\Candidate;
use Yii;
use yii\base\Model;


/**
 * Занимается обработкой текста с тэгами
 * Class TagHelper
 * @package app\components\helpers
 */
class TagHelper
{

    const LEFT_PRETAG = '{';
    const RIGHT_PRETAG = '}';

    /**
     * Обрабатывают строку по модели
     * @param string $text
     * @param Model|Model[] $model
     * @return string
     */
    public static function handleModel($text, $model)
    {
        $output = $text;

        if(!is_array($model) && $model instanceof Model)
        {
            Yii::info('Not array', 'test');
            $className = self::getClassName($model);
            $tags = [];

            foreach ($model->attributes as $name => $value)
            {
                Yii::info($name . ' => ' . $value, 'test');

                $tags[self::LEFT_PRETAG."{$className}.{$name}".self::RIGHT_PRETAG] = $value;
            }

            $output = str_ireplace(array_keys($tags), array_values($tags), $output);

        } else if (is_array($model))
        {
            Yii::info('Is array', 'test');
            Yii::info(count($model), 'test');

            /** @var Candidate $modelItem */
            foreach ($model as $modelItem)
            {
                Yii::info($modelItem->attributes, 'test');

                $className = self::getClassName($modelItem);

                Yii::info($className, 'test');

                $tags = [];

                foreach ($modelItem->attributes as $name => $value)
                {
                    $tags[self::LEFT_PRETAG."{$className}.{$name}".self::RIGHT_PRETAG] = $value;
                }

                Yii::info($tags, 'test');


                $output = str_ireplace(array_keys($tags), array_values($tags), $output);
            }

        }

        Yii::info($output, 'test');


        return $output;
    }

    public static function handleArray($text, $arr)
    {
        Yii::info($text, 'test');
        Yii::info($arr, 'test');

        $output = $text;
        $tags = [];

        foreach ($arr as $key => $value) {
            $tags[self::LEFT_PRETAG."{$key}".self::RIGHT_PRETAG] = $value;
        }

        Yii::info($tags, 'test');

        $output = str_ireplace(array_keys($tags), array_values($tags), $output);

        return $output;
    }

    /**
     * Обрабатывают строку по справочнику
     * @param string $text
     * @param \app\models\TemplateMessages[] $templateMessages
     * @return string
     */
    public static function handleTemplateMessages($text, $templateMessages)
    {
        $output = $text;

        foreach ($templateMessages as $templateMessage) {
            $contents = $templateMessage->templateMessagesContents;
            $count = count($contents);

            $number = rand(0, $count-1);

            $content = $contents[$number]->content;

            $output = str_replace($templateMessage->tag, $content, $output);
        }

        return $output;
    }

    /**
     * @param Model $model
     * @return string
     */
    private static function getClassName($model)
    {
        return lcfirst(\yii\helpers\StringHelper::basename(get_class($model)));
    }
}