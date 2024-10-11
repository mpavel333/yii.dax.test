<?php

namespace app\widgets;

use yii\helpers\ArrayHelper;
use yii\base\Widget;
use yii\data\Pagination;

class CardGrid extends Widget
{
	public $dataProvider;
	public $colSize;
	public $titleAttribute;
	public $serialAttribute;
	public $buttonsTemplate;
	public $buttons;
	public $listOptions;
	public $list;
	public $pjax = false;
	public $noEmpty = false;

	public function run()
	{
		$content = '';
		
		// $pages = new Pagination(['totalCount' => $this->dataProvider->totalCount, 'pageSize' => 5]);
		// $this->dataProvider->query->offset($pages->offset)->limit($pages->pageSize);

		$size = 0;
		foreach($this->dataProvider->models as $model){
			$list = $this->handleList($model);
			$buttons = $this->handleButtons($model);
			\Yii::warning($buttons, '$buttons');
			$content .= $this->render('card-grid-item', [
				'w' => $this,
				'model' => $model,
				'list' => $list,
				'buttons' => $buttons,
			]);

			if($this->colSize == 3)
			{
				$size = $size + 3;
			}

			if($this->colSize == 3 && $size == 12){
				$content .= '<div class="col-md-12"></div>';
				$size = 0;
			}
		}


		$html = $this->render('card-grid', [
			'w' => $this,
			'content' => $content,
			'dataProvider' => $this->dataProvider,
		]);

		return $html;
	}

	private function handleButtons($model)
	{
		$buttons = $this->buttonsTemplate;
		
		if(is_array($this->buttons) == false){
			$this->buttons = [];
		}

		foreach($this->buttons as $buttonKey => $button){
			if(is_callable($button)){
				$buttons = str_replace('{'.$buttonKey.'}', call_user_func($button, $model), $buttons);
			}
		}

		return is_array($buttons) ? null : $buttons;
	}

	private function handleList($model)
	{
		$newList = [];
		foreach($this->list as $list)
		{
			if(isset($list['visible']) && $list['visible']==false){
				continue;
			}
			if(is_array($list)){
				$label = '—';
				if(isset($list['label'])){
					$label = $list['label'];
				} else {
					if($model instanceof yii\db\ActiveRecord && isset($list['attribute'])){
						$attr = explode('.', $list['attribute']);
						$attr = $attr[0];
						$label = $model->getAttributeLabel($attr);
					}
				}

				$content = null;
				if(isset($list['content']) && is_callable($list['content'])){
					$content = call_user_func($list['content'], $model);
				} elseif(isset($list['value'])){
					if(is_callable($list['value'])){
						$content = call_user_func($list['value'], $model);
					} else {
						$content = ArrayHelper::getValue($model, $list['value']);
					}

					if(isset($list['format'])){
						$content = \Yii::$app->formatter->format($content, $list['format']);
					} else {
						$content = \Yii::$app->formatter->asNtext($content);
					}
				} elseif(isset($list['attribute'])) {
					$content = ArrayHelper::getValue($model, $list['attribute']);
				}

				if($this->noEmpty && empty($content)) continue;

				$newList[] = [
					'label' => $label,
					'content' => $content,
				];

			} else {
				$label = '—';
				if($model instanceof \yii\db\ActiveRecord){
					$label = $model->getAttributeLabel($list);
				}

				$content = ArrayHelper::getValue($model, $list);

				if($this->noEmpty && empty($content)) continue;

				$newList[] = [
					'label' => $label,
					'content' => $content,
				];
			}
		}
		return $newList;
	}
}


?>