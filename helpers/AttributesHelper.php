<?php

namespace app\helpers;

use Yii;
use \yii\helpers\Inflector;

class AttributesHelper {

	public static function roleHandle($attributes)
	{
		$fileAttributes = []; // Атрибуты файлов
		$excludeAttributes = []; // Атрибуты которые будут исключены

		foreach ($attributes as $attr => $label) { // Ищем атрибуты файлов, которые начинаются с "file"
			if(substr($attr, 0, 4) == 'file'){
				$fileAttributes[$attr] = $label; 				
			}
		}

		// Отбрасываем у атрибутов файлов слово "file" и меняем загланые буквы на разделитель "_", чтобы они соответствовали полям в базе
		foreach ($fileAttributes as $attr => $label) {
			$excludeAttributes[] = Inflector::camel2id(substr($attr, 4), '_', true);		
		}

		// Исключаем из атрибутов атрибуты которые лежат в $excludeAttributes
		$fileAttributes = array_filter($attributes, function($value, $key) use ($excludeAttributes){
			return isset(array_combine(array_values($excludeAttributes), array_values($excludeAttributes))[$key]) == false;
		}, ARRAY_FILTER_USE_BOTH);

		return $fileAttributes;
	}

}