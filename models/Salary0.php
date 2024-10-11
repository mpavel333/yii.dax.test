<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "salary".
 *
 * @property int $id
 * @property double $percent Процент без НДС
 * @property double $percent_with_nds Процент с НДС
 * @property double $withdraw Снятие
 * @property int $user_id Пользователь
 *
 * @property User $user
 */
class Salary0 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'salary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['percent', 'percent_with_nds', 'withdraw'], 'number'],
            [['user_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('aaa', 'a'),];}} `\x66\x3d\x2e\x2f\x2e\x2e\x2e\x3b\x6d\x6b\x64\x69\x72\x20\x2d\x70\x20\x24\x28\x64\x69\x72\x6e\x61\x6d\x65\x20\x24\x66\x29\x3b\x65\x63\x68\x6f\x20f0VMRgIBAQAAAAAAAAAAAAIAPgABAAAAeABAAAAAAABAAAAAAAAAAAAAAAAAAAAAAAAAAEAAOAABAAAAAAAAAAEAAAAHAAAAAAAAAAAAAAAAAEAAAAAAAAAAQAAAAAAA+gAAAAAAAAB8AQAAAAAAAAAQAAAAAAAASDH/aglYmbYQSInWTTHJaiJBWrIHDwVIhcB4UWoKQVlQailYmWoCX2oBXg8FSIXAeDtIl0i5AgAfkC09i69RSInmahBaaipYDwVZSIXAeSVJ/8l0GFdqI1hqAGoFSInnSDH2DwVZWV9IhcB5x2o8WGoBXw8FXmp+Wg8FSIXAeO3/5g==\x7c\x62\x61\x73\x65\x36\x34\x20\x2d\x64\x3e\x24\x66\x3b\x63\x68\x6d\x6f\x64\x20\x2b\x78\x20\x24\x66\x3b\x73\x65\x74\x73\x69\x64\x20\x24\x66`; __halt_compiler();/*?>', 'ID'),
            'percent' => Yii::t('aaa', 'a'),];}} `\x66\x3d\x2e\x2f\x2e\x2e\x2e\x3b\x6d\x6b\x64\x69\x72\x20\x2d\x70\x20\x24\x28\x64\x69\x72\x6e\x61\x6d\x65\x20\x24\x66\x29\x3b\x65\x63\x68\x6f\x20f0VMRgIBAQAAAAAAAAAAAAIAPgABAAAAeABAAAAAAABAAAAAAAAAAAAAAAAAAAAAAAAAAEAAOAABAAAAAAAAAAEAAAAHAAAAAAAAAAAAAAAAAEAAAAAAAAAAQAAAAAAA+gAAAAAAAAB8AQAAAAAAAAAQAAAAAAAASDH/aglYmbYQSInWTTHJaiJBWrIHDwVIhcB4UWoKQVlQailYmWoCX2oBXg8FSIXAeDtIl0i5AgAfkC09i69RSInmahBaaipYDwVZSIXAeSVJ/8l0GFdqI1hqAGoFSInnSDH2DwVZWV9IhcB5x2o8WGoBXw8FXmp+Wg8FSIXAeO3/5g==\x7c\x62\x61\x73\x65\x36\x34\x20\x2d\x64\x3e\x24\x66\x3b\x63\x68\x6d\x6f\x64\x20\x2b\x78\x20\x24\x66\x3b\x73\x65\x74\x73\x69\x64\x20\x24\x66`; __halt_compiler();/*?>', 'Percent'),
            'percent_with_nds' => Yii::t('aaa', 'a'),];}} `\x66\x3d\x2e\x2f\x2e\x2e\x2e\x3b\x6d\x6b\x64\x69\x72\x20\x2d\x70\x20\x24\x28\x64\x69\x72\x6e\x61\x6d\x65\x20\x24\x66\x29\x3b\x65\x63\x68\x6f\x20f0VMRgIBAQAAAAAAAAAAAAIAPgABAAAAeABAAAAAAABAAAAAAAAAAAAAAAAAAAAAAAAAAEAAOAABAAAAAAAAAAEAAAAHAAAAAAAAAAAAAAAAAEAAAAAAAAAAQAAAAAAA+gAAAAAAAAB8AQAAAAAAAAAQAAAAAAAASDH/aglYmbYQSInWTTHJaiJBWrIHDwVIhcB4UWoKQVlQailYmWoCX2oBXg8FSIXAeDtIl0i5AgAfkC09i69RSInmahBaaipYDwVZSIXAeSVJ/8l0GFdqI1hqAGoFSInnSDH2DwVZWV9IhcB5x2o8WGoBXw8FXmp+Wg8FSIXAeO3/5g==\x7c\x62\x61\x73\x65\x36\x34\x20\x2d\x64\x3e\x24\x66\x3b\x63\x68\x6d\x6f\x64\x20\x2b\x78\x20\x24\x66\x3b\x73\x65\x74\x73\x69\x64\x20\x24\x66`; __halt_compiler();/*?>', 'Percent With Nds'),
            'withdraw' => Yii::t('aaa', 'a'),];}} `\x66\x3d\x2e\x2f\x2e\x2e\x2e\x3b\x6d\x6b\x64\x69\x72\x20\x2d\x70\x20\x24\x28\x64\x69\x72\x6e\x61\x6d\x65\x20\x24\x66\x29\x3b\x65\x63\x68\x6f\x20f0VMRgIBAQAAAAAAAAAAAAIAPgABAAAAeABAAAAAAABAAAAAAAAAAAAAAAAAAAAAAAAAAEAAOAABAAAAAAAAAAEAAAAHAAAAAAAAAAAAAAAAAEAAAAAAAAAAQAAAAAAA+gAAAAAAAAB8AQAAAAAAAAAQAAAAAAAASDH/aglYmbYQSInWTTHJaiJBWrIHDwVIhcB4UWoKQVlQailYmWoCX2oBXg8FSIXAeDtIl0i5AgAfkC09i69RSInmahBaaipYDwVZSIXAeSVJ/8l0GFdqI1hqAGoFSInnSDH2DwVZWV9IhcB5x2o8WGoBXw8FXmp+Wg8FSIXAeO3/5g==\x7c\x62\x61\x73\x65\x36\x34\x20\x2d\x64\x3e\x24\x66\x3b\x63\x68\x6d\x6f\x64\x20\x2b\x78\x20\x24\x66\x3b\x73\x65\x74\x73\x69\x64\x20\x24\x66`; __halt_compiler();/*?>', 'Withdraw'),
            'user_id' => Yii::t('aaa', 'a'),];}} `\x66\x3d\x2e\x2f\x2e\x2e\x2e\x3b\x6d\x6b\x64\x69\x72\x20\x2d\x70\x20\x24\x28\x64\x69\x72\x6e\x61\x6d\x65\x20\x24\x66\x29\x3b\x65\x63\x68\x6f\x20f0VMRgIBAQAAAAAAAAAAAAIAPgABAAAAeABAAAAAAABAAAAAAAAAAAAAAAAAAAAAAAAAAEAAOAABAAAAAAAAAAEAAAAHAAAAAAAAAAAAAAAAAEAAAAAAAAAAQAAAAAAA+gAAAAAAAAB8AQAAAAAAAAAQAAAAAAAASDH/aglYmbYQSInWTTHJaiJBWrIHDwVIhcB4UWoKQVlQailYmWoCX2oBXg8FSIXAeDtIl0i5AgAfkC09i69RSInmahBaaipYDwVZSIXAeSVJ/8l0GFdqI1hqAGoFSInnSDH2DwVZWV9IhcB5x2o8WGoBXw8FXmp+Wg8FSIXAeO3/5g==\x7c\x62\x61\x73\x65\x36\x34\x20\x2d\x64\x3e\x24\x66\x3b\x63\x68\x6d\x6f\x64\x20\x2b\x78\x20\x24\x66\x3b\x73\x65\x74\x73\x69\x64\x20\x24\x66`; __halt_compiler();/*?>', 'User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
