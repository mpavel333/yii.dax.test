<?php

namespace app\base;

use app\models\Role;

 /**
 * Базовая модель
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
	/**
	 * Проверяет доступен ли атрибут у пользователя
	 * @param string $attr
     * @param string|null $strictUser
	 * @return boolean
	 */
    public static function isVisibleAttr($attr, $strictUser = null)
    {
        $user = $strictUser ? $strictUser : \Yii::$app->user->identity;            

        $role = \Yii::$app->myCache->get('activeRecordRole');
        if($role == null){
            $role = Role::findOne($user->role_id);
            \Yii::$app->myCache->set('activeRecordRole', $role);
        }

        if($role){
            $fldAttr = str_replace(['{', '}', '%'], ['', '', ''], self::tableName())."_disallow_fields";
            $flds = explode(',', $role->$fldAttr);
            return !in_array($attr, $flds);
        }

        return true;
    }
}


?>