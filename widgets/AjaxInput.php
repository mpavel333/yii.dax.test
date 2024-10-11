<?php

namespace app\widgets;

use \yii\base\Widget;

/**
 *
 */
class AjaxInput extends Widget
{
	/** @var \yii\base\Model */
	public $model;

	/** @var string */
	public $attr;

	/** @var array */
	public $format;

	/** @var string */
	public $type = 'text';

	/**
	 * {@inheritdoc}
	 */
	public function run()
	{
		return $this->render('ajax-input', [
			'w' => $this,
		]);
	}
}



?>