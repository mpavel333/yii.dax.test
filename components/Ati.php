<?php

namespace app\components;

use app\models\Security;
use app\models\User;
use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * API для взаимодействия с Ati.su
 */
class Ati extends Component
{

	/** @var string */
	private $token;

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();

		$security = Security::find()->one();

		if($security){
			$this->token = $security->api_token;
		}
	}


	public function addCargo($flight)
	{
		$loadingCityId = null;
		$uploadingCityId = null;

		$user = User::findOne($flight->user_id);
		if($user == null){
			return null;
		}

		\Yii::warning($user->phone, 'phone');

		$contacts = $this->getContacts($user->phone);

		\Yii::warning($contacts, '$contacts');

		if($flight->rout){
			$route = explode('-', $flight->rout);
			if(isset($route[0])){
				$loadingCity = trim($route[0]);
				$loadingCityId = $this->getCityId($loadingCity);
			}
			if(isset($route[1])){
				$uploadingCity = trim($route[1]);
				$uploadingCityId = $this->getCityId($uploadingCity);
			}
		}

		\Yii::warning($flight->cargo_weight, 'cargo_weight');
		\Yii::warning($flight->volume, 'volume');

		$flight->cargo_weight = str_replace(',', '.', $flight->cargo_weight);
		$flight->cargo_weight = str_replace('т', '', $flight->cargo_weight);
		$flight->cargo_weight = str_replace('кг', '', $flight->cargo_weight);
		$flight->cargo_weight = trim($flight->cargo_weight);

        $data = [
        	'cargo_application' => [
	        	// <Маршрут>
	            'route' => [
	            	// <Загрузка>
	            	'loading' => [
	            		'location' => [
	            			'type' => 'manual',
	            			'city_id' => $loadingCityId,
	            		],
	            		'dates' => [
	            			'type' => 'from-date',
	            			'first_date' => $flight->shipping_date,
	            			// 'first_date' => "2023-08-31",
	            			'last_date' => $flight->date_out4,
	            			// 'last_date' => "2023-09-05",
	            		],
	            		'cargos' => [
	            			[
								'id' => $flight->id,
		            			'name' => $flight->name,
		            			'weight' => [
		            				'quantity' => $flight->cargo_weight ? $flight->cargo_weight : 1,
		            				'type' => $flight->type_weight,
		            			],
		            			'volume' => [
		            				'quantity' => $flight->volume ? $flight->volume : 1,
		            			],
		            			// 'sizes' => [
		            			// 	'length' => [
		            			// 		'value' => $flight->length,
		            			// 	],
		            			// 	'height' => [
		            			// 		'value' => $flight->height,
		            			// 	],
		            			// 	'width' => [
		            			// 		'value' => $flight->width,
		            			// 	],
		            			// 	'diameter' => $flight->diameter,
		            			// ],
	            			]
	            		],
	            	],
	            	// </Загрузка>
	            	// <Разгрузка>
	            	'unloading' => [
	            		'location' => [
	            			'type' => 'manual',
	            			'city_id' => $uploadingCityId,
	            		],
	            	],
	            	// </Разгрузка>
	            ],
	            // </Маршрут>
	            // <Транспорт>
	            'truck' => [
	            	'load_type' => 'ftl',
	            	'body_types' => [$flight->body_type],
	            	'requirements' => [
	            		'logging_truck' => (boolean) $flight->logging_truck,
	            		'road_train' => (boolean) $flight->road_train,
	            		'air_suspension' => (boolean) $flight->air_suspension,
	            	],
	            	'body_loading' => [
	            		'types' => [$flight->loading_type],
	            	],
	            	'body_unloading' => [
	            		'types' => [$flight->uploading_type],
	            	],
	            	'belts_count' => $flight->belts_count,
	            ],
	            // </Транспорт>
	            // <Оплата>
	            'payment' => [
	            	'type' => 'without-bargaining',
	            	'currency_type' => 1,
	            	'rate_with_vat' => $flight->we,
	            	'rate_without_vat' => $flight->we,
	            ],
	            // </Оплата>
	            'contacts' => [ArrayHelper::getValue($contacts, '0.id')],
        	],
        ];

        if($flight->length && $flight->height && $flight->width){
        	$data['cargo_application']['route']['loading']['cargos'][0]['sizes'] = [
				'length' => [
					'value' => $flight->length,
				],
				'height' => [
					'value' => $flight->height,
				],
				'width' => [
					'value' => $flight->width,
				],
				'diameter' => $flight->diameter,
        	];
        }

        if($flight->priority_rate || ($flight->priority_limit || $flight->priority_daily_limit)){
        	$data['cargo_application']['paid_features'] = [
        		'priority_view' => [
        			'rate' => $flight->priority_rate,
        			'limit' => $flight->priority_limit,
        			'daily_limit' => $flight->priority_daily_limit,
        			'only_for_paid_users' => (boolean) $flight->only_for_paid_users,
        		],
        	];
        }


        \Yii::warning($this->token, '$this->token');

        $data_string = json_encode($data, JSON_UNESCAPED_UNICODE);
        $curl = curl_init('https://api.ati.su/v2/cargos');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
           'Content-Type: application/json',
           "Authorization: Bearer {$this->token}")
        );
        $result = curl_exec($curl);

        \Yii::warning($result, 'addCargo() $result');

        return $result;
	}

	/**
	 *
	 */
	public function getContacts($phone = null)
	{
		$phone = str_replace('+', '', $phone);
		$phone = str_replace('-', '', $phone);
		$phone = str_replace(' ', '', $phone);
		$phone = str_replace('(', '', $phone);
		$phone = str_replace(')', '', $phone);

	    $curl = curl_init('https://api.ati.su/v1.0/firms/contacts');
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	       'Content-Type: application/json',
	       "Authorization: Bearer {$this->token}")
	    );
	    $result = json_decode(curl_exec($curl), true);

	    if($result == null){
	    	$result = [];
	    }

	    if($phone){
			$filtered = array_filter($result, function($item) use($phone){
		    	$mobile = str_replace(' ', '', $item['mobile']);
		    	$mobile = str_replace('-', '', $mobile);
		    	$mobile = str_replace('+', '', $mobile);
		    	$mobile = str_replace('(', '', $mobile);
		    	$mobile = str_replace(')', '', $mobile);

		    	\Yii::warning("{$mobile} == {$phone}", "\$mobile == \$phone");

		    	return $mobile == $phone;
		    });

	    	return count($filtered) > 0 ? array_values($filtered) : $result;
	    }

	    return $result;

	}

	/**
	 *
	 */
	public function getCarTypes()
	{
	    $curl = curl_init('https://api.ati.su/v1.0/dictionaries/carTypes');
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	       'Content-Type: application/json',
	       "Authorization: Bearer {$this->token}")
	    );
	    $result = json_decode(curl_exec($curl), true);

	    if($result == null){
	    	$result = [];
	    }

	    return $result;

	}

		/**
	 *
	 */
	public function getLoadingTypes()
	{
	    $curl = curl_init('https://api.ati.su/v1.0/dictionaries/loadingTypes');
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	       'Content-Type: application/json',
	       "Authorization: Bearer {$this->token}")
	    );
	    $result = json_decode(curl_exec($curl), true);

	    if($result == null){
	    	$result = [];
	    }

	    return $result;

	}


	/**
	 * @param string $name
	 */
	public function getCityId($name)
	{
		$name = str_replace('г.', '', $name);
		$name = trim($name);
		$data = [
			'name' => $name,
			'cityNameOnly' => 'true',
		];

	    $curl = curl_init('https://api.ati.su/v1.0/dictionaries/cities?'.http_build_query($data));
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	       'Content-Type: application/json',
	       "Authorization: Bearer {$this->token}")
	    );
	    $result = json_decode(curl_exec($curl), true);

	    foreach($result as $row){
	    	if($row['CityName'] == $name){
	    		return $row['CityId'];
	    	}
	    }

	    return null;
	}


}





?>