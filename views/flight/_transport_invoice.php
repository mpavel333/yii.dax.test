<?php

use yii\helpers\ArrayHelper as _;

?>

<style>
    * {
        font-family: 'Times New Roman' !important;
    }
    body {
        padding: 1px;
    }
    table {
        width: 100%;
    }
    .text-left {
        text-align: left;
    }
    .text-center {
        text-align: center;
    }
    .text-right {
        text-align: right;
    }
    .fz-10 {
        font-size: 10px !important;
    }
    .fz-13 {
        font-size: 13px !important;
    }
    .bold {
        font-weight: 800;
    }
    .w-60 {
        width: 60%;
    }
    .w-70 {
        width: 70%;
    }
    .w-80 {
        width: 80%;
    }

    .vt-top {
        vertical-align: top;
    }

    h3 {
        font-weight: 200 !important;
        font-size: 16px !important;
    }

    .bordered tr td {
        border: 1px solid #000;
    }
</style>

<table class="text-right">
    <tbody>
        <tr>
            <td class="w-60"></td>
            <td>Приложение № 4<br>к Правилам перевозок грузов<br>автомобильным транспортом<br><br><span class="fz-13">(в ред. Постановления Правительства РФ
            от 30.11.2021 № 2116)</span></td>
        </tr>
    </tbody>
</table>

<h3 class="text-center" style="margin-bottom: 20px;">Транспортная накладная (форма)</h3>

<table class="bordered">
    <tbody>
        <tr class="text-center bold">
            <td colspan="4">Транспортная накладная</td>
            <td colspan="4">Заказ (заявка)</td>
        </tr>
        <tr class="text-center">
            <td class="bold">Дата</td>
            <td><?= date('d.m.Y') ?></td>
            <td class="bold">№</td>
            <td><?= $model->order ?></td>
            <td class="bold">Дата</td>
            <td><?= date('d.m.Y', strtotime($model->date)) ?></td>
            <td class="bold">№</td>
            <td><?= $model->order ?></td>
        </tr>
        <tr>
            <td class="bold" colspan="2">Экземпляр №1</td>
            <td colspan="2"></td>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td class="bold" colspan="4">Грузополучатель</td>
            <td class="vt-top" colspan="4" rowspan="2">1а Заказчик услуг по организации<br>перевозки груза (при наличии)</td>
        </tr>
        <tr>
            <td colspan="4"><br></td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                <?= _::getValue($model, 'organization.name') ?>, ИНН <?= _::getValue($model, 'organization.inn') ?>, <?= _::getValue($model, 'organization.official_address') ?>, тел: <?= _::getValue($model, 'organization.tel') ?>
            </td>
            <td colspan="4">

            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (реквизиты, позволяющие идентифицировать Грузоотправителя)
            </td>
            <td class="fz-10" colspan="4">
                (реквизиты, позволяющие идентифицировать Заказчика услуг по организации перевозки груза)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                <br>
            </td>
            <td colspan="4">
                <br>
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                реквизиты документа, определяющего основания осуществления расчетов по договору перевозки иным лицом, отличным от грузоотправителя (при наличии)
            </td>
            <td class="fz-10" colspan="4">
                (реквизиты договора на выполнение услуг по организации перевозки груза)
            </td>
        </tr>
        <tr class="text-center bold">
            <td colspan="8">2. Грузополучатель</td>
        </tr>
        <tr class="text-center">
            <td colspan="8">
                <?= _::getValue($model, 'zakazchik.name') ?>, ИНН <?= _::getValue($model, 'zakazchik.inn') ?>, <?= _::getValue($model, 'zakazchik.official_address') ?>, тел: <?= _::getValue($model, 'zakazchik.tel') ?>
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="8">(реквизиты договора на выполнение услуг по организации перевозки груза)</td>
        </tr>
        <tr class="text-center">
            <td colspan="8">
                <?= $model->address_out4 ?>
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="8">(адрес места доставки груза)</td>
        </tr>
        <tr class="text-center bold">
            <td colspan="8">3. Груз</td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                <?= $model->name ?>
            </td>
            <td colspan="4">
                Способ упаковки: <?= $model->name2 ?>
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (отгрузочное наименование груза (для опасных грузов - в соответствии с ДОПОГ), его состояние и другая необходимая информация о грузе)
            </td>
            <td class="fz-10" colspan="4">
                (количество грузовых мест, маркировка, вид тары и способ упаковки)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="8">
                Всего: <?= $model->cargo_weight ?> кг. (брутто);
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="8">
                (масса груза брутто в килограммах, масса груза нетто в килограммах (при возможности ее определения), размеры (высота, ширина, длина) в метрах (при перевозке крупногабаритного груза), объем груза в кубических метрах и плотность груза в соответствии с документацией на груз (при необходимости), дополнительные характеристики груза, учитывающие отраслевые особенности (при необходимости)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                —
            </td>
            <td colspan="4">
                <?= $model->name_price ?> руб.
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (в случае перевозки опасного груза - информация по каждому опасному веществу, материалу или изделию в соответствии с пунктом 5.4.1 ДОПОГ)
            </td>
            <td class="fz-10" colspan="4">
                (объявленная стоимость (ценность) груза (при необходимости)
            </td>
        </tr>
        <tr class="text-center bold">
            <td colspan="8">4. Сопроводительные документы на груз (при наличии)</td>
        </tr>
        <tr class="text-center">
            <td colspan="8">—</td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="8">
                (перечень прилагаемых к транспортной накладной документов, предусмотренных ДОПОГ, санитарными, таможенными (при наличии), карантинными, иными правилами в соответствии с законодательством Российской Федерации, либо регистрационные номера указанных документов, если такие документы (сведения о таких документах) содержатся в государственных информационных системах)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="8">Сертификат соответствия N РОСС RU.АЮ 31.Н14390 от 17.01.2024, ИНН составителя - 7707123456</td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="8">
                (перечень прилагаемых к грузу сертификатов, паспортов качества, удостоверений и других документов, наличие которых установлено законодательством Российской Федерации, либо регистрационные номера указанных документов, если такие документы (сведения о таких документах) содержатся в государственных информационных системах)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="8">Товарная накладная от <?= date('d.m.Y') ?> N <?= $model->order ?>, ИНН составителя - 5042230372</td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="8">
                (реквизиты, позволяющие идентифицировать документ(-ы), подтверждающий(-ие) отгрузку товаров) (при наличии), реквизиты сопроводительной ведомости (при перевозке груженых контейнеров или порожних контейнеров)
            </td>
        </tr>
        <tr class="text-center bold">
            <td colspan="8">5. Указания грузоотправителя по особым условиям перевозки</td>
        </tr>
        <tr class="text-center">
            <td colspan="4">Дата доставки: <?= date('d.m.Y', strtotime($model->date_out4)) ?>, с 9:00 до 18:00</td>
            <td colspan="4"></td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (маршрут перевозки, дата и время/сроки доставки груза (при необходимости)
            </td>
            <td class="fz-10" colspan="4">
                (контактная информация о лицах, по указанию которых может осуществляться переадресовка)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="4"></td>
            <td colspan="4"></td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (указания, необходимые для выполнения фитосанитарных, санитарных, карантинных, таможенных и прочих требований, установленных законодательством Российской Федерации)
            </td>
            <td class="fz-10" colspan="4">
                (температурный режим перевозки груза (при необходимости), сведения о запорно-пломбировочных устройствах (в случае их предоставления грузоотправителем), запрещение перегрузки груза)
            </td>
        </tr>
        <tr class="text-center bold">
            <td colspan="8">6. Перевозчик</td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                <?= _::getValue($model, 'carrier.name') ?>, ИНН <?= _::getValue($model, 'carrier.inn') ?>, <?= _::getValue($model, 'carrier.official_address') ?>, тел: <?= _::getValue($model, 'carrier.tel') ?>
            </td>
            <td colspan="4">
                <?= _::getValue($model, 'driver.data') ?>, <?= _::getValue($model, 'driver.info') ?>
                тел: <?= _::getValue($model, 'driver.phone') ?>
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (реквизиты, позволяющие идентифицировать Перевозчика)
            </td>
            <td class="fz-10" colspan="4">
                (реквизиты, позволяющие идентифицировать водителя(-ей))
            </td>
        </tr>
        <tr class="text-center bold">
            <td colspan="8">7. Транспортное средство</td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                <?= $model->getAutoString() ?>
            </td>
            <td colspan="4">
                <?php
                    $driver = \app\models\Driver::findOne($model->auto);

                    if($driver){
                        echo "{$driver->car_number}";
                    }
                
                ?>
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (тип, марка, грузоподъемность (в тоннах), вместимость (в кубических метрах)
            </td>
            <td class="fz-10" colspan="4">
                (регистрационный номер транспортного средства)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="8"></td>
        </tr>
        <tr class="text-center bold">
            <td colspan="8">Тип владения: 1 - собственность; 2 - совместная собственность супругов; 3 - аренда; 4 - лизинг;<br>1</td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                
            </td>
            <td colspan="4">
                
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (реквизиты документа(-ов), подтверждающего(-их) основание владения грузовым автомобилем (тягачом, а также прицепом (полуприцепом) (для типов владения 3, 4, 5)
            </td>
            <td class="fz-10" colspan="4">
                (номер, дата и срок действия специального разрешения, установленный маршрут движения тяжеловесного и (или) крупногабаритного транспортного средства или транспортного средства, перевозящего опасный груз) (при наличии)
            </td>
        </tr>
        <tr class="text-center bold">
            <td colspan="8">8. Прием груза</td>
        </tr>
        <tr class="text-center">
            <td colspan="8">
                <?= _::getValue($model, 'organization.name') ?>
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="8">
                (реквизиты лица, осуществляющего погрузку груза в транспортное средство)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="8">
                <?= _::getValue($model, 'organization.name') ?>
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="8">
                (наименование (ИНН) владельца объекта инфраструктуры пункта погрузки)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                <?= $model->address1 ?>
            </td>
            <td colspan="4">
                <?= date('d.m.Y', strtotime($model->shipping_date)) ?>, 9:00
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (адрес места погрузки)
            </td>
            <td class="fz-10" colspan="4">
                (заявленные дата и время подачи транспортного средства под погрузку)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                <?= date('d.m.Y', strtotime($model->shipping_date)) ?>, 9:00
            </td>
            <td colspan="4">
                <?= date('d.m.Y', strtotime($model->shipping_date)) ?>, 9:00
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (фактические дата и время прибытия под погрузку)
            </td>
            <td class="fz-10" colspan="4">
                (фактические дата и время убытия)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="8">
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="8">
                (оговорки и замечания перевозчика (при наличии) о дате и времени прибытия/убытия, о состоянии, креплении груза, тары, упаковки, маркировки, опломбирования, о массе груза и количестве грузовых мест, о проведении погрузочных работ)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                Кладовщик:  Драгунов В.А. Драгунов (доверенность от 01.03.2024 N б/н)
            </td>
            <td colspan="4">
                Савушкин М.Ю. Савушкин
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (подпись, расшифровка подписи лица, осуществившего погрузку груза, с указанием реквизитов документа, подтверждающего полномочия лица на погрузку груза)
            </td>
            <td class="fz-10" colspan="4">
                (подпись, расшифровка подписи водителя, принявшего груз для перевозки)
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
            </td>
            <td class="fz-10" colspan="4">
            </td>
        </tr>
        <tr class="text-center bold">
            <td colspan="8">9. Переадресовка (при наличии)</td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
            </td>
            <td colspan="4">
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (дата, вид переадресовки на бумажном носителе или в электронном виде
                (с указанием вида доставки документа))
            </td>
            <td class="fz-10" colspan="4">
                (адрес нового пункта выгрузки, новые дата и время подачи транспортного средства под выгрузку)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
            </td>
            <td colspan="4">
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (реквизиты лица, от которого получено указание на переадресовку)
            </td>
            <td class="fz-10" colspan="4">
                (при изменении получателя груза - реквизиты нового получателя)
            </td>
        </tr>
        <tr class="text-center bold">
            <td colspan="8">10. Выдача груза</td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                <?= $model->address_out4 ?>
            </td>
            <td colspan="4">
                <?= date('d.m.Y', strtotime($model->date_out4)) ?>, 9:00
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (адрес места выгрузки)
            </td>
            <td class="fz-10" colspan="4">
                (заявленные дата и время подачи транспортного средства под выгрузку)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                <?= date('d.m.Y', strtotime($model->date_out4)) ?>, 9:00
            </td>
            <td colspan="4">
                <?= date('d.m.Y', strtotime($model->date_out4)) ?>, 9:00
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (фактические дата и время прибытия)
            </td>
            <td class="fz-10" colspan="4">
                (фактические дата и время убытия)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                Груз упакован в картонные коробки, упаковка не нарушена, маркировка нанесена
            </td>
            <td colspan="4">
                <?= $model->place_count ?>
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (фактическое состояние груза, тары, упаковки, маркировки, опломбирования)
            </td>
            <td class="fz-10" colspan="4">
                (количество грузовых мест)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                <?= $model->cargo_weight ?> кг (брутто) / <?= $model->cargo_weight ?> кг (нетто)
            </td>
            <td colspan="4">
                
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (масса груза брутто в килограммах, масса груза нетто в килограммах (при возможности ее определения), плотность груза в соответствии с документацией на груз (при необходимости)
            </td>
            <td class="fz-10" colspan="4">
                (оговорки и замечания перевозчика (при наличии) о дате и времени прибытия/убытия, о состоянии груза, тары, упаковки, маркировки, опломбирования, о массе груза и количестве грузовых мест)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                Менеджер Хохлов И.Т. Хохлов
            </td>
            <td colspan="4">
                Савушкин М.Ю. Савушкин
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (должность, подпись, расшифровка подписи грузополучателя или уполномоченного грузоотправителем лица)
            </td>
            <td class="fz-10" colspan="4">
                (подпись, расшифровка подписи водителя, сдавшего груз грузополучателю или уполномоченному грузополучателем лицу)
            </td>
        </tr>
        <tr class="text-center bold">
            <td colspan="8">11. Отметки грузоотправителей, грузополучателей, перевозчиков (при необходимости)</td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                Менеджер Хохлов И.Т. Хохлов
            </td>
            <td colspan="4">
                Савушкин М.Ю. Савушкин
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (должность, подпись, расшифровка подписи грузополучателя или уполномоченного грузоотправителем лица)
            </td>
            <td class="fz-10" colspan="4">
                (подпись, расшифровка подписи водителя, сдавшего груз грузополучателю или уполномоченному грузополучателем лицу)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="5">
                
            </td>
            <td colspan="2">
                
            </td>
            <td colspan="1">
                
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="5">
                (краткое описание обстоятельств, послуживших основанием для отметки, сведения о коммерческих и иных актах, в том числе
                о погрузке/выгрузке груза)
            </td>
            <td class="fz-10" colspan="2">
                (расчет и размер штрафа)
            </td>
            <td class="fz-10" colspan="1">
                (подпись, дата)
            </td>
        </tr>
        <tr class="text-center bold">
            <td colspan="8">12. Стоимость перевозки груза (установленная плата) в рублях (при необходимости)</td>
        </tr>
        <tr class="text-center">
            <td colspan="2">
                
            </td>
            <td colspan="2">
                
            </td>
            <td colspan="2">
                
            </td>
            <td colspan="2">
                
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="2">
                (стоимость перевозки без налога - всего)
            </td>
            <td class="fz-10" colspan="2">
                (налоговая ставка)
            </td>
            <td class="fz-10" colspan="2">
                (сумма налога, предъявляемая покупателю)
            </td>
            <td class="fz-10" colspan="2">
                (стоимость перевозки с налогом - всего)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="8">
                
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="8">
                (порядок (механизм) расчета (исчислений) платы) (при наличии порядка (механизма))
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                
            </td>
            <td colspan="4">
                
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (реквизиты, позволяющие идентифицировать Экономического субъекта, составляющего первичный учетный документ о факте хозяйственной жизни со стороны Перевозчика)
            </td>
            <td class="fz-10" colspan="4">
                (реквизиты, позволяющие идентифицировать Экономического субъекта, составляющего первичный учетный документ о факте хозяйственной жизни со стороны Грузоотправителя)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                
            </td>
            <td colspan="4">
                
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (основание, по которому Экономический субъект является составителем документа о факте хозяйственной жизни)
            </td>
            <td class="fz-10" colspan="4">
                (основание, по которому Экономический субъект является составителем документа о факте хозяйственной жизни)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                
            </td>
            <td colspan="4">
                
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">

            </td>
            <td class="fz-10" colspan="4">
                (реквизиты, позволяющие идентифицировать лицо, от которого будут поступать денежные средства)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                
            </td>
            <td colspan="4">
                
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (подпись, расшифровка подписи лица, ответственного за оформление факта хозяйственной жизни со стороны Перевозчика (уполномоченного лица)
            </td>
            <td class="fz-10" colspan="4">
                (подпись, расшифровка подписи лица, ответственного за оформление факта хозяйственной жизни со стороны Грузоотправителя (уполномоченного лица)
            </td>
        </tr>
        <tr class="text-center">
            <td colspan="4">
                
            </td>
            <td colspan="4">
                
            </td>
        </tr>
        <tr class="text-center">
            <td class="fz-10" colspan="4">
                (должность, основание полномочий физического лица, уполномоченного Перевозчиком (уполномоченным лицом), дата подписания)
            </td>
            <td class="fz-10" colspan="4">
                (должность, основание полномочий физического лица, уполномоченного Грузоотправителем (уполномоченным лицом), дата подписания)
            </td>
        </tr>
    </tbody>
</table>
