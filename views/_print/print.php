<?php

/** @var $content string */

?>

<style>
    *{margin:0;padding:0;}
    .table_moved_list{
        width: 97%;
        border-collapse: collapse;
    }
    .table_moved_list th,
    .table_moved_list td {
        border: 1px solid #000;
        padding: 3px;
    }
    .barcode_image_container svg,
    .barcode_image_container img {
        height: auto;
        width: 100%;
    }
</style>
<title><?= isset($subject) ?  $subject : null ?></title>
<body onload="print()"><p>&nbsp;</p>
<?= $content ?>
<style type="text/css">p.dline {
        line-height: 0.7;
    }
    P {
        line-height: 1em;
    }
</style>

<style type="text/css">
        * { 
            font-family: arial;
            font-size: 14px;
            line-height: 14px;
        }
        table {
            margin: 0 0 15px 0;
            width: 100%;
            border-collapse: collapse; 
            border-spacing: 0;
        }       
        table td {
            padding: 5px;
        }   
        table th {
            padding: 5px;
            font-weight: bold;
        }
 
        .header {
            margin: 0 0 0 0;
            padding: 0 0 15px 0;
            font-size: 12px;
            line-height: 12px;
            text-align: center;
        }
        
        /* Реквизиты банка */
        .details td {
            padding: 3px 2px;
            border: 1px solid #000000;
            font-size: 12px;
            line-height: 12px;
            vertical-align: top;
        }
 
        h1 {
            margin: 0 0 10px 0;
            padding: 10px 0 10px 0;
            border-bottom: 2px solid #000;
            font-weight: bold;
            font-size: 20px;
        }
 
        /* Поставщик/Покупатель */
        .contract th {
            padding: 3px 0;
            vertical-align: top;
            text-align: left;
            font-size: 13px;
            line-height: 15px;
        }   
        .contract td {
            padding: 3px 0;
        }       
 
        /* Наименование товара, работ, услуг */
        .list thead, .list tbody  {
            border: 2px solid #000;
        }
        .list thead th {
            padding: 4px 0;
            border: 1px solid #000;
            vertical-align: middle;
            text-align: center;
        }   
        .list tbody td {
            padding: 0 2px;
            border: 1px solid #000;
            vertical-align: middle;
            font-size: 11px;
            line-height: 13px;
        }   
        .list tfoot th {
            padding: 3px 2px;
            border: none;
            text-align: right;
        }   
 
        /* Сумма */
        .total {
            margin: 0 0 20px 0;
            padding: 0 0 10px 0;
            border-bottom: 2px solid #000;
        }   
        .total p {
            margin: 0;
            padding: 0;
        }
        
        /* Руководитель, бухгалтер */
        .sign {
            position: relative;
        }
        .sign table {
            width: 60%;
        }
        .sign th {
            padding: 40px 0 0 0;
            text-align: left;
        }
        .sign td {
            padding: 40px 0 0 0;
            border-bottom: 1px solid #000;
            text-align: right;
            font-size: 12px;
        }
        
        .sign-1 {
            position: absolute;
            left: 149px;
            top: -44px;
        }   
        .sign-1 td{
            border-bottom: 1px solid #000;
        }   
        .sign-2 {
            position: absolute;
            left: 149px;
            top: 0;
        }   
        .printing {
            position: absolute;
            left: 271px;
            top: -15px;
        }
    </style>
</body>
