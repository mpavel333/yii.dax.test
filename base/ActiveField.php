<?php

namespace app\base;

use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\widgets\ActiveForm;
use app\base\BaseHtml;

class ActiveField extends \yii\widgets\ActiveField
{
	public $cols = null;

	public $colsOptionsStr = '';

    public $checkPermission = true;

	/**
     * {@inheritdoc}
     */
    public function render($content = null)
    {
        if ($content === null) {
            if (!isset($this->parts['{input}'])) {
                $this->textInput();
            }
            if (!isset($this->parts['{label}'])) {
                $this->label();
            }
            if (!isset($this->parts['{error}'])) {
                $this->error();
            }
            if (!isset($this->parts['{hint}'])) {
                $this->hint(null);
            }
            $content = strtr($this->template, $this->parts);
        } elseif (!is_string($content)) {
            $content = call_user_func($content, $this);
        }

        // $className = str_replace(['{', '}', '%'], ['', '', ''], );

        if($this->checkPermission){
            if($this->model instanceof \app\base\ActiveRecord){
                $allow = $this->model::isVisibleAttr($this->attribute);

                if($allow == false){
                    return '';
                }
            }
        }




        if($this->cols == null){
	        return $this->begin() . "\n" . $content . "\n" . $this->end();
        } else {
        	// if(isset($this->colsOptions['id']) == false){
        		// $this->colsOptions['id'] = 'div-'.Inflector::camel2id(StringHelper::basename($this->attribute));;
        	// }
        	return "<div class='col-md-{$this->cols}' ".$this->colsOptionsStr.">".$this->begin() . "\n" . $content . "\n" . $this->end().'</div>'; 
        }
    }

    /**
     * {@inheritdoc}
     */
    public function checkbox($options = [], $enclosedByLabel = true)
    {
        if ($this->form->validationStateOn === ActiveForm::VALIDATION_STATE_ON_INPUT) {
            $this->addErrorClassIfNeeded($options);
        }

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);

        if ($enclosedByLabel) {
            $this->parts['{input}'] = BaseHtml::activeCheckbox($this->model, $this->attribute, $options);
            $this->parts['{label}'] = '';
        } else {
            if (isset($options['label']) && !isset($this->parts['{label}'])) {
                $this->parts['{label}'] = $options['label'];
                if (!empty($options['labelOptions'])) {
                    $this->labelOptions = $options['labelOptions'];
                }
            }
            unset($options['labelOptions']);
            $options['label'] = null;
            $this->parts['{input}'] = BaseHtml::activeCheckbox($this->model, $this->attribute, $options);
        }

        return $this;
    }
}