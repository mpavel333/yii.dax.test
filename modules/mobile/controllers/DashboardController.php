<?php 
namespace app\modules\mobile\controllers;

use app\models\Company;
use app\models\MobileUser;
use app\models\Machine;
use Yii;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\models\User;
use app\behaviors\RoleBehavior;

class DashboardController extends Controller
{
    public $rawData;

    private $user;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {   
        $result = [];

        if (isset($_GET['chart_button'])) {
            if ($_GET['chart_button'] == "one"){
                $chartButton = [
                    [
                        "button" => "one",
                        "icon" => "fa fa-pencil",
                        "color" => "#EB9D40"
                    ],
                    [
                        "button" => "two",
                        "icon" => "fa fa-eye",
                        "color" => "#CBD9FF"
                    ]
                ];
                if (isset($_GET["chart_data"])){
                    if ($_GET["chart_data"] == "aprel"){
                        $chartSelect = [
                            "data" => [
                                ["label" => "Апрель", "value" => "aprel",],
                                ["label" => "Март", "value" => "mart",],
              
                            ]
                        ];
                        $chartSeries = [
                            [
                                "name" => 'Наличные', 
                                "color" => "#06BBEE",
                                "data" => [31, 40, 28, 51, 42, 109, 100],
                            ],
                            [
                                "name" => 'Безналичные',
                                "color" => "#F04438",
                                "data" => [11, 32, 45, 32, 34, 52, 41],
                            ] 
                        ];
                        $chartCategories = ['2019-09-15T00:00:00', '2019-09-16T01:30:00', '2019-09-17T02:30:00', '2019-09-18T03:30:00', '2019-09-19T04:30:00', '2019-09-20T05:30:00', '2019-09-21T06:30:00'];
                    }
                    if ($_GET["chart_data"] == "mart"){
                         $chartSelect = [
                            "data" => [
                                ["label" => "Март", "value" => "mart",],
                                ["label" => "Апрель", "value" => "aprel",],
              
                            ]
                        ];
                        $chartSeries = [
                            [
                                "name" => 'Наличные др', 
                                "color" => "#06BBEE",
                                "data" => [131, 140, 128, 151, 142, 1109, 1100],
                            ],
                            [
                                "name" => 'Безналичные др',
                                "color" => "#F04438",
                                "data" => [111, 132, 145, 132, 134, 152, 141],
                            ] 
                        ];
                        $chartCategories = ['2019-09-01T00:00:00', '2019-09-02T01:30:00', '2019-09-03T02:30:00', '2019-09-04T03:30:00', '2019-09-05T04:30:00', '2019-09-06T05:30:00', '2019-09-07T06:30:00'];
                    }
                } else {
                    $chartSelect = [
                        "data" => [
                            ["label" => "Апрель", "value" => "aprel",],
                            ["label" => "Март", "value" => "mart",],
          
                        ]
                    ];
                    $chartSeries = [
                        [
                            "name" => 'Наличные', 
                            "color" => "#06BBEE",
                            "data" => [31, 40, 28, 51, 42, 109, 100],
                        ],
                        [
                            "name" => 'Безналичные',
                            "color" => "#F04438",
                            "data" => [11, 32, 45, 32, 34, 52, 41],
                        ] 
                    ];
                    $chartCategories = ['2019-09-15T00:00:00', '2019-09-16T01:30:00', '2019-09-17T02:30:00', '2019-09-18T03:30:00', '2019-09-19T04:30:00', '2019-09-20T05:30:00', '2019-09-21T06:30:00'];
                }
            }
            if ($_GET['chart_button'] == "two") {
                $chartButton = [
                    [
                        "button" => "one",
                        "icon" => "fa fa-pencil",
                        "color" => "#CBD9FF"
                    ],
                    [
                        "button" => "two",
                        "icon" => "fa fa-eye",
                        "color" => "#EB9D40"
                    ]
                ];
                if (isset($_GET["chart_data"])){
                    if ($_GET["chart_data"] == "aprel"){
                        $chartSelect = [
                            "data" => [
                                ["label" => "Апрель", "value" => "aprel",],
                                ["label" => "Март", "value" => "mart",],
                            ]
                        ];
                        $chartSeries = [
                            [
                                "name" => 'Наличные', 
                                "color" => "#06BBEE",
                                "data" => [2231, 2240, 2228, 2251, 2242, 22109, 12200],
                            ],
                            [
                                "name" => 'Безналичные',
                                "color" => "#F04438",
                                "data" => [3311, 3332, 3345, 3332, 3334, 3352, 3341],
                            ] 
                        ];
                        $chartCategories = ['2019-09-15T00:00:00', '2019-09-16T01:30:00', '2019-09-17T02:30:00', '2019-09-18T03:30:00', '2019-09-19T04:30:00', '2019-09-20T05:30:00', '2019-09-21T06:30:00'];
                    }
                    if ($_GET["chart_data"] == "mart"){
                         $chartSelect = [
                            "data" => [
                                ["label" => "Март", "value" => "mart",],
                                ["label" => "Апрель", "value" => "aprel",],
              
                            ]
                        ];
                        $chartSeries = [
                            [
                                "name" => 'Наличные др', 
                                "color" => "#06BBEE",
                                "data" => [131, 140, 128, 151, 142, 1109, 1100],
                            ],
                            [
                                "name" => 'Безналичные др',
                                "color" => "#F04438",
                                "data" => [111, 132, 145, 132, 134, 152, 141],
                            ] 
                        ];
                        $chartCategories = ['2019-09-01T00:00:00', '2019-09-02T01:30:00', '2019-09-03T02:30:00', '2019-09-04T03:30:00', '2019-09-05T04:30:00', '2019-09-06T05:30:00', '2019-09-07T06:30:00'];
                    }
                } else {
                    $chartSelect = [
                        "data" => [
                            ["label" => "Апрель", "value" => "aprel",],
                            ["label" => "Март", "value" => "mart",],
          
                        ]
                    ];
                    $chartSeries = [
                        [
                            "name" => 'Наличные', 
                            "color" => "#06BBEE",
                            "data" => [31, 40, 28, 51, 42, 109, 100],
                        ],
                        [
                            "name" => 'Безналичные',
                            "color" => "#F04438",
                            "data" => [11, 32, 45, 32, 34, 52, 41],
                        ] 
                    ];
                    $chartCategories = ['2019-09-15T00:00:00', '2019-09-16T01:30:00', '2019-09-17T02:30:00', '2019-09-18T03:30:00', '2019-09-19T04:30:00', '2019-09-20T05:30:00', '2019-09-21T06:30:00'];
                }
            }
        } else {
             $chartButton = [
                [
                    "button" => "one",
                    "icon" => "fa fa-pencil",
                    "color" => "#EB9D40"
                ],
                [
                    "button" => "two",
                    "icon" => "fa fa-eye",
                    "color" => "#CBD9FF"
                ]
            ];
            $chartSelect = [
                "data" => [
                    ["label" => "Апрель", "value" => "aprel",],
                    ["label" => "Март", "value" => "mart",],
  
                ]
            ];
            $chartSeries = [
                [
                    "name" => 'Наличные', 
                    "color" => "#06BBEE",
                    "data" => [31, 40, 28, 51, 42, 109, 100],
                ],
                [
                    "name" => 'Безналичные',
                    "color" => "#F04438",
                    "data" => [11, 32, 45, 32, 34, 52, 41],
                ] 
            ];
            $chartCategories = ['2019-09-15T00:00:00', '2019-09-16T01:30:00', '2019-09-17T02:30:00', '2019-09-18T03:30:00', '2019-09-19T04:30:00', '2019-09-20T05:30:00', '2019-09-21T06:30:00'];
        }

        $result[] = [
            'type' => "chart",
            'label' => "Кол-во покупок",
            'button' => $chartButton,
            'select' => $chartSeries,
            "series" => $chartSelect,
            "categories" => $chartCategories,
        ];

        $result[] = [
            'type' => 'block_box',
            'label' => "Общий статаус автоматов",
            'list' => [
                [
                    'color' => '#8AC44B',
                    'data' => [
                        'label' => 'Работают',
                        'value' => '151',
                        'value2' => '34%',
                        'label2' => 'втоматов'
                    ]
                ],
                [
                    'color' => '#478ECC',
                    'data' => [
                        'label' => 'Мало продуктов',
                        'value' => '251',
                        'value2' => '20%',
                        'label2' => 'втоматов'
                    ]
                ],
                [
                    'color' => '#F04438',
                    'data' => [
                        'label' => 'Тех. проблемы',
                        'value' => '30',
                        'value2' => '17%',
                        'label2' => 'втоматов'
                    ]
                ]
            ]
        ];
        $result[] = [
            'type' => 'block_mini_list',
            'label' => "Общая статистика за сегодня",
            'list' => [
                [
                    'label' => 'Выручка',
                    'value' => '150 000',
                    "icon" => "fa fa-pencil",
                ],
                [
                    'label' => 'Товаров и услуг продано',
                    'value' => '10 000',
                    "icon" => "fa fa-pencil",
                ],
                [
                    'label' => 'Инкассировано',
                    'value' => '1 000',
                    "icon" => "fa fa-pencil",
                ]
            ]
        ];
        if (isset($_GET["block_top_list_data"])){
            if ($_GET["block_top_list_data"] == "top3") {
                $block_top_list_data = [
                    ["label" => "Top 3", "value" => "top3",],
                    ["label" => "Top 10", "value" => "top10",],
  
                ];
                $list = [
                    [
                        [
                            'label' => 'Тип',
                            'value' => '<i class="bi bi-align-top"></i>',
                        ],
                        [
                            'label' => 'Количество покупок',
                            'value' => '670',
                        ],
                        [
                            'label' => 'Прибыль',
                            'value' => '700 000р',
                        ],
                        [
                            'label' => 'Деньги в автомате',
                            'value' => '10 000р',
                        ],
                        [
                            'label' => 'Расположение',
                            'value' => 'Теамат на Киевской',
                        ],
                    ],
                    [
                        [
                            'label' => 'Тип',
                            'value' => '<i class="bi bi-align-top"></i>',
                        ],
                        [
                            'label' => 'Количество покупок',
                            'value' => '670',
                        ],
                        [
                            'label' => 'Прибыль',
                            'value' => '700 000р',
                        ],
                        [
                            'label' => 'Деньги в автомате',
                            'value' => '10 000р',
                        ],
                        [
                            'label' => 'Расположение',
                            'value' => 'Теамат на Киевской',
                        ],
                    ],
                    [
                        [
                            'label' => 'Тип',
                            'value' => '<i class="bi bi-align-top"></i>',
                        ],
                        [
                            'label' => 'Количество покупок',
                            'value' => '670',
                        ],
                        [
                            'label' => 'Прибыль',
                            'value' => '700 000р',
                        ],
                        [
                            'label' => 'Деньги в автомате',
                            'value' => '10 000р',
                        ],
                        [
                            'label' => 'Расположение',
                            'value' => 'Теамат на Киевской',
                        ],
                    ]
                    
                ];
            }
            if ($_GET["block_top_list_data"] == "top10") {

                $block_top_list_data = [
                    ["label" => "Top 10", "value" => "top10",],
                    ["label" => "Top 3", "value" => "top3",],
                ];
                $list = [
                    [
                        [
                            'label' => 'Тип',
                            'value' => '<i class="bi bi-align-top"></i>',
                        ],
                        [
                            'label' => 'Количество покупок',
                            'value' => '1 670',
                        ],
                        [
                            'label' => 'Прибыль',
                            'value' => '70 000р',
                        ],
                        [
                            'label' => 'Деньги в автомате',
                            'value' => '1 000р',
                        ],
                        [
                            'label' => 'Расположение',
                            'value' => 'Теамат на Киевской',
                        ],
                    ],
                    [
                        [
                            'label' => 'Тип',
                            'value' => '<i class="bi bi-align-top"></i>',
                        ],
                        [
                            'label' => 'Количество покупок',
                            'value' => '2 670',
                        ],
                        [
                            'label' => 'Прибыль',
                            'value' => '710 000р',
                        ],
                        [
                            'label' => 'Деньги в автомате',
                            'value' => '20 000р',
                        ],
                        [
                            'label' => 'Расположение',
                            'value' => 'Теамат на Киевской',
                        ],
                    ],
                    [
                        [
                            'label' => 'Тип',
                            'value' => '<i class="bi bi-align-top"></i>',
                        ],
                        [
                            'label' => 'Количество покупок',
                            'value' => '1 670',
                        ],
                        [
                            'label' => 'Прибыль',
                            'value' => '7 000р',
                        ],
                        [
                            'label' => 'Деньги в автомате',
                            'value' => '110 000р',
                        ],
                        [
                            'label' => 'Расположение',
                            'value' => 'Теамат на Киевской',
                        ],
                    ]
                    
                ];
            }
        } else {

                $block_top_list_data = [
                    ["label" => "Top 3", "value" => "top3",],
                    ["label" => "Top 10", "value" => "top10",],
                ];
             $list = [
                    [
                        [
                            'label' => 'Тип',
                            'value' => '<i class="bi bi-align-top"></i>',
                        ],
                        [
                            'label' => 'Количество покупок',
                            'value' => '670',
                        ],
                        [
                            'label' => 'Прибыль',
                            'value' => '700 000р',
                        ],
                        [
                            'label' => 'Деньги в автомате',
                            'value' => '10 000р',
                        ],
                        [
                            'label' => 'Расположение',
                            'value' => 'Теамат на Киевской',
                        ],
                    ],
                    [
                        [
                            'label' => 'Тип',
                            'value' => '<i class="bi bi-align-top"></i>',
                        ],
                        [
                            'label' => 'Количество покупок',
                            'value' => '670',
                        ],
                        [
                            'label' => 'Прибыль',
                            'value' => '700 000р',
                        ],
                        [
                            'label' => 'Деньги в автомате',
                            'value' => '10 000р',
                        ],
                        [
                            'label' => 'Расположение',
                            'value' => 'Теамат на Киевской',
                        ],
                    ],
                    [
                        [
                            'label' => 'Тип',
                            'value' => '<i class="bi bi-align-top"></i>',
                        ],
                        [
                            'label' => 'Количество покупок',
                            'value' => '670',
                        ],
                        [
                            'label' => 'Прибыль',
                            'value' => '700 000р',
                        ],
                        [
                            'label' => 'Деньги в автомате',
                            'value' => '10 000р',
                        ],
                        [
                            'label' => 'Расположение',
                            'value' => 'Теамат на Киевской',
                        ],
                    ]
                    
                ];
        }
        $result[] = [
            'type' => 'block_top_list',
            'label' => "Лучшие торговые автоматы",
            'select' => [
                "data" => $block_top_list_data
            ],
            'list' => $list
        ];

        $result[] = [
            'type' => 'block_free',
            'label' => 'Требуется внимание',
            "data" => [
                [
                    "up_left" => [
                        '<i class="bi bi-align-top"></i>',
                        '<i class="bi bi-align-top"></i>',
                    ],
                    "up_right" =>[
                        "10 часов",
                        'Что то еще'
                    ],
                    "down_left" => [
                        '<i class="bi bi-align-top"></i>',
                        'Теамат на Киевской',
                    ],
                    "down_right" =>[
                        "10 часов",
                        'Что то еще'
                    ],
                ],
                [
                    "up_left" => [
                        '<i class="bi bi-align-top"></i>',
                        '<i class="bi bi-align-top"></i>',
                    ],
                    "up_right" =>[
                        "10 часов",
                        'Что то еще'
                    ],
                    "down_left" => [
                        '<i class="bi bi-align-top"></i>',
                        'Теамат на Киевской',
                    ],
                    "down_right" =>[
                        "10 часов",
                        'Что то еще'
                    ],
                ],
                [
                    "up_left" => [
                        '<i class="bi bi-align-top"></i>',
                        '<i class="bi bi-align-top"></i>',
                    ],
                    "up_right" =>[
                        "10 часов",
                        'Что то еще'
                    ],
                    "down_left" => [
                        '<i class="bi bi-align-top"></i>',
                        'Теамат на Киевской',
                    ],
                    "down_right" =>[
                        "10 часов",
                        'Что то еще'
                    ],
                ],
                [
                    "up_left" => [
                        '<i class="bi bi-align-top"></i>',
                        '<i class="bi bi-align-top"></i>',
                    ],
                    "up_right" =>[
                        "10 часов",
                        'Что то еще'
                    ],
                    "down_left" => [
                        '<i class="bi bi-align-top"></i>',
                        'Теамат на Киевской',
                    ],
                    "down_right" =>[
                        "10 часов",
                        'Что то еще'
                    ],
                ],
            ]
        ];
        if (isset($_GET["blok_top_tab_data"])){
            if ($_GET["blok_top_tab_data"] == "top3") {
                $blok_top_tab_data = [
                    ["label" => "Top 3", "value" => "top3",],
                    ["label" => "Top 10", "value" => "top10",],
  
                ];
                $list = [
                [
                    "label" => 'Отключение питания',
                    "icon" => "fa fa-eye",
                    "color" => "#06BBEE", 
                    'up_left' => 'Количество',
                    'down_left' => '1 320',
                    'up_right' =>'Последнее',
                    'down_right' => '12 часа',
                ],
                [
                    "label" => 'Отключение питания',
                    "icon" => "fa fa-eye",
                    "color" => "#06BBEE", 
                    'up_left' => 'Количество',
                    'down_left' => '4 320',
                    'up_right' =>'Последнее',
                    'down_right' => '4 часа',
                ],
                [
                    "label" => 'Отключение питания',
                    "icon" => "fa fa-eye",
                    "color" => "#06BBEE", 
                    'up_left' => 'Количество',
                    'down_left' => '3 320',
                    'up_right' =>'Последнее',
                    'down_right' => '22 часа',
                ],
            ];
            }
            if ($_GET["blok_top_tab_data"] == "top10") {

                $blok_top_tab_data = [
                    ["label" => "Top 10", "value" => "top10",],
                    ["label" => "Top 3", "value" => "top3",],
  
                ];
                $list = [
                [
                    "label" => 'Отключение питания',
                    "icon" => "fa fa-eye",
                    "color" => "#06BBEE", 
                    'up_left' => 'Количество',
                    'down_left' => '320',
                    'up_right' =>'Последнее',
                    'down_right' => '2 часа',
                ],
                [
                    "label" => 'Отключение питания',
                    "icon" => "fa fa-eye",
                    "color" => "#06BBEE", 
                    'up_left' => 'Количество',
                    'down_left' => '320',
                    'up_right' =>'Последнее',
                    'down_right' => '2 часа',
                ],
                [
                    "label" => 'Отключение питания',
                    "icon" => "fa fa-eye",
                    "color" => "#06BBEE", 
                    'up_left' => 'Количество',
                    'down_left' => '320',
                    'up_right' =>'Последнее',
                    'down_right' => '2 часа',
                ],
            ];
            }
        } else {

                $blok_top_tab_data = [
                    ["label" => "Top 3", "value" => "top3",],
                    ["label" => "Top 10", "value" => "top10",],
                ];
            $list = [
                [
                    "label" => 'Отключение питания',
                    "icon" => "fa fa-eye",
                    "color" => "#06BBEE", 
                    'up_left' => 'Количество',
                    'down_left' => '1 320',
                    'up_right' =>'Последнее',
                    'down_right' => '12 часа',
                ],
                [
                    "label" => 'Отключение питания',
                    "icon" => "fa fa-eye",
                    "color" => "#06BBEE", 
                    'up_left' => 'Количество',
                    'down_left' => '4 320',
                    'up_right' =>'Последнее',
                    'down_right' => '4 часа',
                ],
                [
                    "label" => 'Отключение питания',
                    "icon" => "fa fa-eye",
                    "color" => "#06BBEE", 
                    'up_left' => 'Количество',
                    'down_left' => '3 320',
                    'up_right' =>'Последнее',
                    'down_right' => '22 часа',
                ],
            ];
        }
        $result[]= [
            'type' => 'blok_top_tab',
            'label' => "Самые частые события",
            'select' => [
                "data" => $blok_top_tab_data
            ],
            "list" => $list
        ];

        if (isset($_GET["chart_gorizont_button"])) {
            if ($_GET["chart_gorizont_button"] == "one") {
                $chartGorizontButton = [
                    [
                        "button" => "one",
                        "icon" => "fa fa-pencil",
                        "color" => "#EB9D40"
                    ],
                    [
                        "button" => "two",
                        "icon" => "fa fa-eye",
                        "color" => "#CBD9FF"
                    ]
                ];
                $series = [
                    [
                        "name" => 'Экспрессо', 
                        "color" => "#06BBEE",
                        "data" => 100,
                    ],
                    [
                        "name" => 'Зарядка',
                        "color" => "#06BBEE",
                        "data" => 200,
                    ] ,
                    [
                        "name" => 'Зарядка',
                        "color" => "#EB9D40",
                        "data" => 50,
                    ] 
                ];
            }
            if ($_GET["chart_gorizont_button"] == "two") {
                $chartGorizontButton = [
                    [
                        "button" => "one",
                        "icon" => "fa fa-pencil",
                        "color" => "#CBD9FF"
                    ],
                    [
                        "button" => "two",
                        "icon" => "fa fa-eye",
                        "color" => "#EB9D40"
                    ]
                ];
                $series = [
                    [
                        "name" => 'Экспрессо', 
                        "color" => "#06BBEE",
                        "data" => 1100,
                    ],
                    [
                        "name" => 'Зарядка',
                        "color" => "#06BBEE",
                        "data" => 1200,
                    ] ,
                    [
                        "name" => 'Зарядка',
                        "color" => "#EB9D40",
                        "data" => 150,
                    ] 
                ];
            } 
        } else {
            $chartGorizontButton = [
                    [
                        "button" => "one",
                        "icon" => "fa fa-pencil",
                        "color" => "#EB9D40"
                    ],
                    [
                        "button" => "two",
                        "icon" => "fa fa-eye",
                        "color" => "#CBD9FF"
                    ]
                ];
                $series = [
                    [
                        "name" => 'Экспрессо', 
                        "color" => "#06BBEE",
                        "data" => 100,
                    ],
                    [
                        "name" => 'Зарядка',
                        "color" => "#06BBEE",
                        "data" => 200,
                    ] ,
                    [
                        "name" => 'Зарядка',
                        "color" => "#EB9D40",
                        "data" => 50,
                    ] 
                ];
        }
        $result[] = [
            'type' => 'chart_gorizont',
            'label' => "Статистика продаж по всем автоматам",
            'button' => $chartGorizontButton,
            "series" => $series,
        ];
        if (isset($_GET["html"])) {
            if ($_GET["html"] == "one") {
                $htmlButton = [
                    [
                        "button" => "one",
                        "icon" => "fa fa-pencil",
                        "color" => "#EB9D40"
                    ],
                    [
                        "button" => "two",
                        "icon" => "fa fa-eye",
                        "color" => "#CBD9FF"
                    ]
                ];
                $url = "https://web-team.su/demo/click_effect/";
            }
            if ($_GET["html"] == "two"){
                $htmlButton = [
                    [
                        "button" => "one",
                        "icon" => "fa fa-pencil",
                        "color" => "#CBD9FF"
                    ],
                    [
                        "button" => "two",
                        "icon" => "fa fa-eye",
                        "color" => "#EB9D40"
                    ]
                ];
                $url = "https://web-team.su/demo/jQuery-Input-Mask/demo/demo.html";
            }
        } else {
            $htmlButton = [
                [
                    "button" => "one",
                    "icon" => "fa fa-pencil",
                    "color" => "#EB9D40"
                ],
                [
                    "button" => "two",
                    "icon" => "fa fa-eye",
                    "color" => "#CBD9FF"
                ]
            ];
            $url = "https://web-team.su/demo/click_effect/";
        }
        $result[] = [
            'type' => 'html',
            'label' => "Статистика продаж по всем автоматам",
            'button' => $htmlButton,
            "url" => $url,
        ];


        return $result;
    }


    public function beforeAction($action)
    {
        Yii::$app->controller->enableCsrfValidation = false;

        $content = file_get_contents('php://input');
        $this->rawData = json_decode($content, true);

        if($action->id != 'login')
        {
            $token = null;
            if(isset($_GET['token'])){
                $token = $_GET['token'];
            } elseif(isset($this->rawData['token'])){
                $token = $this->rawData['token'];
            }

            if($token == null){
                Yii::$app->response->format = Response::FORMAT_JSON;
                throw new \yii\web\BadRequestHttpException('Токен не указан');
            }

            $token = explode('||', $token);

            $user = \app\models\User::find()->where(['login' => $token[0], 'password_hash' => $token[1]])->one();

            if($user == null){
                Yii::$app->response->format = Response::FORMAT_JSON;
                throw new \yii\web\BadRequestHttpException('Неверный логин или пароль');
            }

            Yii::$app->user->login($user, 0);
        }

        return parent::beforeAction($action);
    }
}