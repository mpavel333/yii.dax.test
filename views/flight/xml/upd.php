<?php

use yii\helpers\ArrayHelper as _;

if(function_exists('guid') == false){

  function guid()
  {
      if (function_exists('com_create_guid') === true)
      {
          return trim(com_create_guid(), '{}');
      }
  
      return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
  }

}

function getVal($model, $val)
{
    $value = _::getValue($model, $val);
    $value = str_replace('"', '&quot;', $value);
    $value = trim($value);
    return $value;
}

?>
<?php echo '<?xml version="1.0" encoding="windows-1251"?>'; ?>
<Файл  xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ИдФайл="<?= $fileId ?>" ВерсФорм="5.01" ВерсПрог="DAKSCRM">
  <СвУчДокОбор ИдОтпр="<?= $idSender ?>" ИдПол="<?= $idReceiver ?>"/>
  <Документ КНД="1115131" Функция="СЧФДОП" ПоФактХЖ="Документ об отгрузке товаров (выполнении работ), передаче имущественных прав (документ об оказании услуг)" НаимДокОпр="Счет-фактура и документ об отгрузке товаров (выполнении работ), передаче имущественных прав (документ об оказании услуг)" ДатаИнфПр="<?= date('d.m.Y') ?>" ВремИнфПр="<?= date('H.i.s') ?>" НаимЭконСубСост="<?= getVal($model, 'organization.name') ?>, ИНН/КПП <?= getVal($model, 'organization.inn') ?>/<?= getVal($model, 'organization.kpp') ?>">
    <СвСчФакт НомерСчФ="<?= getVal($model, 'number') ?>" ДатаСчФ="<?= date('d.m.Y', strtotime($model->date_cr)) ?>" КодОКВ="643">
      <ИспрСчФ ДефНомИспрСчФ="-" ДефДатаИспрСчФ="-"/>
      <СвПрод>
        <ИдСв>
          <СвЮЛУч НаимОрг="<?= getVal($model, 'organization.name') ?>" ИННЮЛ="<?= getVal($model, 'organization.inn') ?>" КПП="<?= getVal($model, 'organization.kpp') ?>"/>
        </ИдСв>
        <Адрес>
          <АдрИнф КодСтр="643" АдрТекст="<?= getVal($model, 'organization.official_address') ?>"/>
        </Адрес>
<?php if(getVal($model, 'organization.tel')): ?>
        <Контакт Тлф="<?= getVal($model, 'organization.tel') ?>"/>
<?php else: ?>
        <Контакт/>
<?php endif; ?>
        <БанкРекв НомерСчета="<?= getVal($model, 'organization.nomer_rascheta') ?>">
          <СвБанк НаимБанк="<?= getVal($model, 'organization.bank_name') ?>" БИК="<?= getVal($model, 'organization.bic') ?>" КорСчет="<?= getVal($model, 'organization.kr') ?>"/>
        </БанкРекв>
      </СвПрод>
      <СвПокуп>
        <ИдСв>
          <СвЮЛУч НаимОрг="<?= getVal($model, 'zakazchik.name') ?>" ИННЮЛ="<?= getVal($model, 'zakazchik.inn') ?>" КПП="<?= getVal($model, 'zakazchik.kpp') ?>"/>
        </ИдСв>
        <Адрес>
          <АдрИнф КодСтр="643" АдрТекст="<?= getVal($model, 'zakazchik.official_address') ?>"/>
        </Адрес>
<?php if(getVal($model, 'zakazchik.tel')): ?>
            <Контакт Тлф="<?= getVal($model, 'zakazchik.tel') ?>"/>
<?php else: ?>
                <Контакт/>
<?php endif; ?>
        <БанкРекв НомерСчета="<?= getVal($model, 'zakazchik.nomer_rascheta') ?>">
          <СвБанк НаимБанк="<?= getVal($model, 'zakazchik.bank_name') ?>" БИК="<?= getVal($model, 'zakazchik.bic') ?>" КорСчет="<?= getVal($model, 'zakazchik.kr') ?>"/>
        </БанкРекв>
      </СвПокуп>
      <ДопСвФХЖ1 НаимОКВ="Российский рубль" КурсВал="1"/>
      <ДокПодтвОтгр НаимДокОтгр="Реализация (акт, накладная, УПД)" НомДокОтгр="<?= getVal($model, 'number') ?>" ДатаДокОтгр="<?= date('d.m.Y', strtotime($model->date_cr)) ?>"/>
      <ИнфПолФХЖ1>
        <ТекстИнф Идентиф="ИдентификаторДокументаОснования" Значен="<?= $statementGuid ?>"/>
        <ТекстИнф Идентиф="ВидСчетаФактуры" Значен="Реализация"/>
        <ТекстИнф Идентиф="ТолькоУслуги" Значен="true"/>
        <ТекстИнф Идентиф="ДокументОбОтгрузке" Значен="№ п/п 1 № <?= getVal($model, 'number') ?> от <?= date('d.m.Y', strtotime($model->date_cr)) ?> г."/>
      </ИнфПолФХЖ1>
    </СвСчФакт>
    <ТаблСчФакт>
      <СведТов НомСтр="1" НаимТов="<?= "Транспортные услуги по Договору-Заявке №{$model->order} от ".\Yii::$app->formatter->asDate($model->date, 'php:d.m.Y').", маршрут {$model->rout}, Авто "._::getValue($model, 'autoString').", Водитель "._::getValue($model, 'driver.data') ?>" ОКЕИ_Тов="642" КолТов="1" <?= $goodAttributes ?>>
        <Акциз>
          <БезАкциз>без акциза</БезАкциз>
        </Акциз>
<?php if($taxNds): ?>
        <СумНал>
          <СумНал><?= $taxNds ?></СумНал>
        </СумНал>
<?php else: ?>
        <СумНал>
          <БезНДС>без НДС</БезНДС>
        </СумНал>
<?php endif; ?>
        <ДопСведТов ПрТовРаб="3" КодТов="00000000038" НаимЕдИзм="ед"/>
        <ИнфПолФХЖ2 Идентиф="Для1С_Идентификатор" Значен="<?= $oneCGuid ?>"/>
        <ИнфПолФХЖ2 Идентиф="Для1С_Наименование" Значен="<?= "Транспортные услуги по Договору-Заявке №{$model->order} от ".\Yii::$app->formatter->asDate($model->date, 'php:d.m.Y').", маршрут {$model->rout}, Авто "._::getValue($model, 'autoString').", Водитель "._::getValue($model, 'driver.data') ?>"/>
        <ИнфПолФХЖ2 Идентиф="Для1С_ЕдиницаИзмерения" Значен="ед"/>
        <ИнфПолФХЖ2 Идентиф="Для1С_ЕдиницаИзмеренияКод" Значен="642"/>
        <ИнфПолФХЖ2 Идентиф="Для1С_СтавкаНДС" Значен="<?= $taxNds ? $taxNds : 0 ?>"/>
        <ИнфПолФХЖ2 Идентиф="ИД" Значен="<?= $oneCGuid ?>"/>
      </СведТов>
<?php if($taxNds): ?>
        <ВсегоОпл СтТовБезНДСВсего="<?= $weNoNds ?>" СтТовУчНалВсего="<?= $model->we ?>">
            <СумНалВсего>
                <СумНал><?= $taxNds ?></СумНал>
            </СумНалВсего>
<?php if(!getVal($model, 'cargo_weight')): ?>
            <КолНеттоВс><?= getVal($model, 'cargo_weight') ?></КолНеттоВс>
<?php endif; ?>
        </ВсегоОпл>
<?php else: ?>
        <ВсегоОпл СтТовБезНДСВсего="<?= $we ?>" СтТовУчНалВсего="<?= $we ?>">
            <СумНалВсего>
                <БезНДС>без НДС</БезНДС>
            </СумНалВсего>
<?php if(!getVal($model, 'cargo_weight')): ?>
            <КолНеттоВс><?= getVal($model, 'cargo_weight') ?></КолНеттоВс>
<?php endif; ?>
        </ВсегоОпл>
<?php endif; ?>
    </ТаблСчФакт>
    <СвПродПер>
    <СвПер СодОпер="Услуги оказаны в полном объеме." ВидОпер="ПродажаКомиссия" ДатаПер="<?= date('d.m.Y', strtotime($model->date_cr)) ?>">
        <ОснПер НаимОсн="Основной договор" НомОсн="<?= getVal($model, 'zakazchik.doc') ?>" ДатаОсн="<?= date('d.m.Y', strtotime(getVal($model, 'zakazchik.doc_date'))) ?>"/>
        <СвЛицПер>
          <РабОргПрод Должность="Генеральный директор">
            <ФИО Фамилия="<?= getVal($model, 'organization.lastNamePart') ?>" Имя="<?= getVal($model, 'organization.namePart') ?>" Отчество="<?= getVal($model, 'organization.patronymicPart') ?>"/>
          </РабОргПрод>
        </СвЛицПер>
        <ТранГруз/>
      </СвПер>
      <ИнфПолФХЖ3>
        <ТекстИнф Идентиф="ИдентификаторДокументаОснования" Значен="<?= $statementGuid ?>"/>
      </ИнфПолФХЖ3>
    </СвПродПер>
    <Подписант ОблПолн="5" Статус="1" ОснПолн="Должностные обязанности">
      <ФЛ>
        <ФИО Фамилия="-" Имя="-"/>
      </ФЛ>
    </Подписант>
  </Документ>
</Файл>