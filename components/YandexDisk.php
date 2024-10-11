<?php

namespace app\components;

class YandexDisk {

	public static function upload($file, $path = '/TEO disk/')
	{
        // $token = 'AQAAAAAWNZbaAAdPkPFxJVQOA01pgSXeX3K_Dp0';
		// $token = 'AQAAAABgExhwAAdPkKxvAdoneENxtc3YlC3403U';
		$token = 'AQAAAAA-wEyAAAdPkCXUwCeQGU7DnFIpvFaIu4w';


        // $path = '/TEO disk/'; 
         
        $ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources/?path=' . urlencode($path));
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        // $setting = \app\models\Setting::findByKey('backup_key');

        
         
        // $file = __DIR__.'/../web/uploads/07wU-ogYh5vptkyPt6wc8RVzp6wSxcDf.jpg';
         
        // Папка на Яндекс Диске (уже должна быть создана).
        // $path = '/TEO disk/'; 
         

        // Запрашиваем URL для загрузки.
        $ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources/upload?path=' . urlencode($path . basename($file)));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);
         
        $res = json_decode($res, true);
        if (empty($res['error'])) {
            // Если ошибки нет, то отправляем файл на полученный URL.
            $fp = fopen($file, 'r');
         
            $ch = curl_init($res['href']);
            curl_setopt($ch, CURLOPT_PUT, true);
            curl_setopt($ch, CURLOPT_UPLOAD, true);
            curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file));
            curl_setopt($ch, CURLOPT_INFILE, $fp);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
         
            if ($http_code == 201) {
                // echo 'Файл успешно загружен.';
            }
        } 

        $getRes = self::publish($path . basename($file))['getRes'];


        return ['res' => $res, 'getRes' => json_decode($getRes, true)];
	}

	public static function download($path)
	{
        $token = 'AQAAAAA-wEyAAAdPkCXUwCeQGU7DnFIpvFaIu4w';

         
        $ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources/download?path=' . urlencode($path));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        $link = isset($res['href']) ? $res['href'] : null;

        \Yii::warning($path, 'PATH');
        \Yii::warning($res, 'RES');

        // if($link){
                    
        // }


        return $res;
	}

    public static function publish($path)
    {
        $token = 'AQAAAAA-wEyAAAdPkCXUwCeQGU7DnFIpvFaIu4w';

         
        $ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources/publish?path=' . urlencode($path));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        $res = curl_exec($ch);
        curl_close($ch);

        $link = isset($res['href']) ? $res['href'] : null;

        \Yii::warning($path, 'PATH');
        \Yii::warning($res, 'RES');

        // if($link){
                    
        // }

        $getRes = self::get($path);



        return ['res' => $res, 'getRes' => $getRes];
    }

    public static function get($path)
    {
        $token = 'AQAAAAA-wEyAAAdPkCXUwCeQGU7DnFIpvFaIu4w';

         
        $ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources?path='.$path);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        $link = isset($res['href']) ? $res['href'] : null;

        \Yii::warning($path, 'PATH');
        \Yii::warning($res, 'RES');

        // if($link){
                    
        // }


        return $res;
    }

}


?>