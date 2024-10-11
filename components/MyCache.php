<?php

namespace app\components;

use yii\base\Component;

class MyCache extends Component
{
	public $data;

	public function get($key, $callback = null)
	{
		if(isset($this->data[$key]) == false){
			if($callback){
				$this->data[$key] = call_user_func($callback);
				return $this->data[$key];
			} else {
				return null;
			}
		} else {
			return $this->data[$key];
		}
	}

	public function set($key, $value)
	{
		$this->data[$key] = $value;
	}	
}

?>