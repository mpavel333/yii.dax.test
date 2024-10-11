<?php
namespace app\base;

use yii\helpers\Html;
use yii\helpers\BaseHtml as YiiBaseHtml;

class BaseHtml extends YiiBaseHtml
{

	public static function activeCheckbox($model, $attribute, $options = [])
	{
	    return static::activeBooleanInput('checkbox', $model, $attribute, $options);
	}

	protected static function activeBooleanInput($type, $model, $attribute, $options = [])
	{
	    $name = isset($options['name']) ? $options['name'] : Html::getInputName($model, $attribute);
	    $value = Html::getAttributeValue($model, $attribute);
	    if (!array_key_exists('value', $options)) {
	        $options['value'] = '1';
	    }
	    if (!array_key_exists('uncheck', $options)) {
	        $options['uncheck'] = '0';
	    } elseif ($options['uncheck'] === false) {
	        unset($options['uncheck']);
	    }
	    if (!array_key_exists('label', $options)) {
	        $options['label'] = Html::encode($model->getAttributeLabel(Html::getAttributeName($attribute)));
	    } elseif ($options['label'] === false) {
	        unset($options['label']);
	    }
	    $checked = "$value" === "{$options['value']}";
	    if (!array_key_exists('id', $options)) {
	        $options['id'] = Html::getInputId($model, $attribute);
	    }
	    return self::$type($name, $checked, $options);
	}


	public static function checkbox($name, $checked = false, $options = [])
	{
	    return static::booleanInput('checkbox', $name, $checked, $options);
	}

	protected static function booleanInput($type, $name, $checked = false, $options = [])
	{
	    // 'checked' option has priority over $checked argument
	    if (!isset($options['checked'])) {
	        $options['checked'] = (bool) $checked;
	    }
	    $value = array_key_exists('value', $options) ? $options['value'] : '1';
	    if (isset($options['uncheck'])) {
	        // add a hidden field so that if the checkbox is not selected, it still submits a value
	        $hiddenOptions = [];
	        if (isset($options['form'])) {
	            $hiddenOptions['form'] = $options['form'];
	        }
	        // make sure disabled input is not sending any value
	        if (!empty($options['disabled'])) {
	            $hiddenOptions['disabled'] = $options['disabled'];
	        }
	        $hidden = static::hiddenInput($name, $options['uncheck'], $hiddenOptions);
	        unset($options['uncheck']);
	    } else {
	        $hidden = '';
	    }
	    if (isset($options['label'])) {
	        $label = $options['label'];
	        $labelOptions = isset($options['labelOptions']) ? $options['labelOptions'] : [];
	        unset($options['label'], $options['labelOptions']);
	        $content = static::label(static::input($type, $name, $value, $options) . '<span></span> ' . $label, null, $labelOptions);
	        return '<div class="checkbox checbox-switch switch-success">'.$hidden . $content.'</div>';
	    }
	    return '<div class="checkbox checbox-switch switch-success">'.$hidden . static::input($type, $name, $value, $options).'</div>';
	}
}

?>