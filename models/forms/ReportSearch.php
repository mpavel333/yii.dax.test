<?php 
namespace app\models\forms;


use app\models\Requisite; 
use app\models\Client; 
use app\models\Driver; 
use app\models\Flight; 
  

use app\models\ReportColumn;
use app\components\ComponentReport;
use Codeception\PHPUnit\ResultPrinter\Report;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * Class ReportSearch
 * @package app\models\forms
 */
class ReportSearch extends Model
{
    /**
     * @var array
     */
    public $setting;
    public $oldSetting;
    public $requisite_name;
    public $requisite_doljnost_rukovoditelya;
    public $requisite_fio_polnostyu;
    public $requisite_official_address;
    public $requisite_bank_name;
    public $requisite_inn;
    public $requisite_kpp;
    public $requisite_ogrn;
    public $requisite_bic;
    public $requisite_kr;
    public $requisite_nomer_rascheta;
    public $requisite_tel;
    public $requisite_fio_buhgaltera;
    public $requisite_nds;
    public $requisite_pechat;
    public $client_name;
    public $client_doljnost_rukovoditelya;
    public $client_fio_polnostyu;
    public $client_official_address;
    public $client_bank_name;
    public $client_inn;
    public $client_kpp;
    public $client_ogrn;
    public $client_bic;
    public $client_kr;
    public $client_nomer_rascheta;
    public $client_tel;
    public $client_email;
    public $client_nds;
    public $client_doc;
    public $client_mailing_address;
    public $client_code;
    public $driver_data;
    public $driver_driver;
    public $driver_phone;
    public $driver_data_avto;
    public $flight_organization_id;
    public $flight_zakazchik_id;
    public $flight_carrier_id;
    public $flight_driver_id;
    public $flight_rout;
    public $flight_order;
    public $flight_date;
    public $flight_view_auto;
    public $flight_address1;
    public $flight_shipping_date;
    public $flight_telephone1;
    public $flight_type;
    public $flight_date_out2;
    public $flight_address_out2;
    public $flight_contact_out2;
    public $flight_name2;
    public $flight_address_out3;
    public $flight_date_out3;
    public $flight_contact;
    public $flight_name3;
    public $flight_address_out4;
    public $flight_date_out4;
    public $flight_telephone;
    public $flight_cargo_weight;
    public $flight_name;
    public $flight_address_out5;
    public $flight_contact_out;
    public $flight_date_out5;
    public $flight_volume;
    public $flight_address;
    public $flight_date_out6;
    public $flight_contact_out3;
    public $flight_dop_informaciya_o_gruze;
    public $flight_we;
    public $flight_pay_us;
    public $flight_payment1;
    public $flight_col2;
    public $flight_payment_out;
    public $flight_otherwise2;
    public $flight_otherwise3;
    public $flight_col1;
    public $flight_fio;
    public $flight_date_cr;
    public $flight_number;
    public $flight_upd;
    public $flight_date2;
    public $flight_date3;
    public $flight_recoil;
    public $flight_your_text;
    public $flight_otherwise4;
    public $flight_otherwise;
    public $flight_file;
  
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['requisite_name','requisite_doljnost_rukovoditelya','requisite_fio_polnostyu','requisite_official_address','requisite_bank_name','requisite_inn','requisite_kpp','requisite_ogrn','requisite_bic','requisite_kr','requisite_nomer_rascheta','requisite_tel','requisite_fio_buhgaltera','requisite_nds','requisite_pechat','client_name','client_doljnost_rukovoditelya','client_fio_polnostyu','client_official_address','client_bank_name','client_inn','client_kpp','client_ogrn','client_bic','client_kr','client_nomer_rascheta','client_tel','client_email','client_nds','client_doc','client_mailing_address','client_code','driver_data','driver_driver','driver_phone','driver_data_avto','flight_organization_id','flight_zakazchik_id','flight_carrier_id','flight_driver_id','flight_rout','flight_order','flight_date','flight_view_auto','flight_address1','flight_shipping_date','flight_telephone1','flight_type','flight_date_out2','flight_address_out2','flight_contact_out2','flight_name2','flight_address_out3','flight_date_out3','flight_contact','flight_name3','flight_address_out4','flight_date_out4','flight_telephone','flight_cargo_weight','flight_name','flight_address_out5','flight_contact_out','flight_date_out5','flight_volume','flight_address','flight_date_out6','flight_contact_out3','flight_dop_informaciya_o_gruze','flight_we','flight_pay_us','flight_payment1','flight_col2','flight_payment_out','flight_otherwise2','flight_otherwise3','flight_col1','flight_fio','flight_date_cr','flight_number','flight_upd','flight_date2','flight_date3','flight_recoil','flight_your_text','flight_otherwise4','flight_otherwise','flight_file', 
    ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'setting' => 'Колонки',
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = (new Query())->from('drivers');


        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->all(),
        ]);





        return $dataProvider;
    }

    public static function fieldLabels()
    {
        return [

        ];
    }

}