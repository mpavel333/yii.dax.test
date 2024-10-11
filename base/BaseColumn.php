<?php

/**
 * @link https://github.com/unclead/yii2-multiple-input
 * @copyright Copyright (c) 2014 unclead
 * @license https://github.com/unclead/yii2-multiple-input/blob/master/LICENSE.md
 */

namespace app\base;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\DynamicModel;
use yii\db\ActiveRecordInterface;
use yii\helpers\Html;
use unclead\multipleinput\components\BaseColumn as BaseColumnBase;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * Class MultipleInputColumn
 * @package unclead\multipleinput
 *
 * @property MultipleInput $context
 */
class BaseColumn extends BaseColumnBase
{
    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if ($this->enableError && !$this->context->model instanceof Model) {
            throw new InvalidConfigException('Property "enableError" available only when model is defined.');
        }
    }

    /**
     * Returns element's name.
     *
     * @param int|null|string $index current row index
     * @param bool $withPrefix whether to add prefix.
     *
     * @return string
     */
    public function getElementName($index, $withPrefix = true)
    {
        if ($index === null) {
            $index = '{' . $this->renderer->getIndexPlaceholder() . '}';
        }

        $elementName = $this->isRendererHasOneColumn()
            ? '[' . $this->name . '][' . $index . ']'
            : '[' . $index . '][' . $this->name . ']';

        if (!$withPrefix) {
            return $elementName;
        }

        $prefix = $this->getInputNamePrefix();
        if ($this->context->isEmbedded && strpos($prefix, $this->context->name) === false) {
            $prefix = $this->context->name;
        }

        return $prefix . $elementName . (empty($this->nameSuffix) ? '' : ('_' . $this->nameSuffix));
    }

    /**
     * @return bool
     */
    private function isRendererHasOneColumn()
    {
        return count($this->renderer->columns) === 1; 
    }

    /**
     * Return prefix for name of input.
     *
     * @return string
     */
    protected function getInputNamePrefix()
    {
        $model = $this->context->model;
        if ($model instanceof Model) {
            if (empty($this->renderer->columns) || ($this->isRendererHasOneColumn() && $this->hasModelAttribute($this->name))) {
                return $model->formName();
            }
            
            return Html::getInputName($this->context->model, $this->context->attribute);
        }
        
        return $this->context->name;
    }

    protected function hasModelAttribute($name)
    {
        $model = $this->context->model;

        if ($model->hasProperty($name)) {
            return true;
        }

        if ($model instanceof ActiveRecordInterface && $model->hasAttribute($name)) {
            return true;
        }

        if ($model instanceof DynamicModel && isset($model->{$name})) {
            return true;
        }

        return false;
    }

    /**
     * @param int|string|null $index
     * @return null|string
     */
    public function getFirstError($index)
    {
        if ($index === null) {
            return null;
        }
        
        if ($this->isRendererHasOneColumn()) {
            $attribute = $this->name . '[' . $index . ']';
        } else {
            $attribute = $this->context->attribute . $this->getElementName($index, false);
        }

        $model = $this->context->model;
        if ($model instanceof Model) {
            return $model->getFirstError($attribute);
        }

        return null;
    }

/**
     * Renders the input.
     *
     * @param string $name the name of the input
     * @param array $options the HTML options of input
     * @param array $contextParams the params who passed to closure:
     * string $id the id of input element
     * string $name the name of input element
     * string $indexPlaceholder The index placeholder of multiple input. The {$indexPlaceholder} template will be replace by $index
     * int $index The index of multiple input
     * int $columnIndex The index of current model attributes
     * @return string
     * @throws InvalidConfigException
     */
    public function renderInput($name, $options, $contextParams = [])
    {
        if ($this->options instanceof \Closure) {
            $optionsExt = call_user_func($this->options, $this->getModel());
        } else {
            $optionsExt = $this->options;
        }

        $options = ArrayHelper::merge($options, $optionsExt);
        $method = 'render' . Inflector::camelize($this->type);

        // @see https://github.com/unclead/yii2-multiple-input/issues/261
        if (isset($contextParams['index']) && isset($contextParams['indexPlaceholder'])) {
            $options = $this->replaceIndexPlaceholderInOptions($options, $contextParams['indexPlaceholder'], $contextParams['index']);
        }

        $value = null;
        if ($this->type !== self::TYPE_DRAGCOLUMN) {
            $value = $this->prepareValue($contextParams);
        }

        if (isset($options['items'])) {
            $options['items'] = $this->prepareItems($options['items']);
        }
        
        if ($this->name == 'condition_select' && isset($options['data'])) {
            $data = $this->getModel();
            
            if($data):

                $conditions = \app\models\RequisiteEnsuranceConditions::find()
                ->where(['requisite_ensurance_id' => $data['id']])
                ->all();

                $out = [];
                foreach ($conditions as $item){
                    $out +=[$item->id=>$item->condition.' | '.$item->percent];
                }
                $options['data'] = $out;
            
            endif;

        }         

        if (method_exists($this, $method)) {
            $input = $this->$method($name, $value, $options);
        } else {
            $input = $this->renderDefault($name, $value, $options);
        }

        return strtr($this->inputTemplate, ['{input}' => $input]);
    }

    /**
     * @inheritdoc
     */
    protected function renderWidget($type, $name, $value, $options)
    {
        // Extend options in case of rendering embedded MultipleInput
        // We have to pass to the widget an original model and an attribute to be able get a first error from model
        // for embedded widget.
        if ($type === \unclead\multipleinput\MultipleInput::className()) {
            $model = $this->context->model;

            // in case of embedding level 2 and more
            if (preg_match('/^([\w\.]+)(\[.*)$/', $this->context->attribute, $matches)) {
                $search = sprintf('%s[%s]%s', $model->formName(), $matches[1], $matches[2]);
            } else {
                $search = sprintf('%s[%s]', $model->formName(), $this->context->attribute);
            }

            $replace = $this->context->attribute;

            $attribute = str_replace($search, $replace, $name);

            $options['model'] = $model;
            $options['attribute'] = $attribute;

            // Remember current name and mark the widget as embedded to prevent
            // generation of wrong prefix in case when column is associated with AR relation
            // @see https://github.com/unclead/yii2-multiple-input/issues/92
            $options['name'] = $name;
            $options['isEmbedded'] = true;
        }

        return parent::renderWidget($type, $name, $value, $options);
    }
    
    private function replaceIndexPlaceholderInOptions($options, $indexPlaceholder, $index)
    {
        $result = [];
        foreach ($options as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->replaceIndexPlaceholderInOptions($value, $indexPlaceholder, $index);
            } elseif (is_string($value)) {
                $result[$key] = str_replace('{' . $indexPlaceholder . '}', $index, $value);
            } else {
                if ($value instanceof JsExpression) {
                    $value->expression = str_replace('{' . $indexPlaceholder . '}', $index, $value->expression);
                }

                $result[$key] = $value;
            }
        }

        return $result;
    }
}