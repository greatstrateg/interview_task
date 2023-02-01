<?php
/**
Фрагмент рабочей библиотеки с классами для работы с Telegram и Яндекс Диск
**/
require_once $_SERVER['DOCUMENT_ROOT']."/lib/lib.php";
require_once $_SERVER['DOCUMENT_ROOT']."/lib/config.php";

//Класс для отправки сообщений в Telegram
class LibTelegram extends MainLib {
    //Функция отправки сообщения в основной чат телеграмм
    function telegramSend($message, $user_id='', $mode=1) {
        $user_id = $this->cms_str_param($user_id);
        if($user_id=='') {
            $user_id = TELEGRAM_ADMIN_ID;
        }

        $arr_param['chat_id'] = $user_id;
        $arr_param['parse_mode'] = 'html';
        $arr_param['disable_web_page_preview'] = $mode;
        $arr_param['text'] = $message;

        $url = "https://api.telegram.org/bot".TELEGRAM_TOKEN."/sendMessage";
        $obj = $this->cms_curl_post_telegram($url, $arr_param);
        $obj = json_decode($obj);
        return $obj;
    }
    //Функция отправки массива
    function telegramSendArr($arr_param, $user_id='', $mode=1) {
        $user_id = $this->cms_str_param($user_id);
        if($user_id=='') {
            $user_id = TELEGRAM_ADMIN_ID;
        }
        $method = "sendMessage";
        if(isset($arr_param['method'])) {
            $method = $arr_param['method'];
        }
        $arr_param['chat_id'] = $user_id;
        $arr_param['parse_mode'] = 'html';
        $arr_param['disable_web_page_preview'] = $mode;

        $url = "https://api.telegram.org/bot".TELEGRAM_TOKEN."/".$method;
        $obj = $this->cms_curl_post_telegram($url, $arr_param);
        $obj = json_decode($obj);
        return $obj;
    }
    //Функция отправки в произвольный чат
    function telegramSendCustom($telegram_token, $arr_param, $user_id='', $mode=1) {
        $user_id = $this->cms_str_param($user_id);
        if($user_id=='') {
            $user_id = TELEGRAM_ADMIN_ID;
        }
        $arr_param['chat_id'] = $user_id;
        $arr_param['parse_mode'] = 'html';
        $arr_param['disable_web_page_preview'] = $mode;

        $url = "https://api.telegram.org/bot".$telegram_token."/sendMessage";
        $obj = $this->cms_curl_post_telegram($url, $arr_param);
        $obj = json_decode($obj);
        return $obj;
    }
    //Функция отправки сообщения в админский чат
    function TelegramAdmin($message) {
        $user_id = TELEGRAM_ADMIN_ID;
        $telegram_token = TELEGRAM_ADMIN_TOKEN;

        $arr_param['text'] = $message;
        $obj = $this->telegramSendCustom($telegram_token, $arr_param, $user_id);

        return $obj;
    }
    function telegramTest($message) {
        $user_id = TELEGRAM_ADMIN_ID;
        $telegram_token = TELEGRAM_TEST_BOT;

        $arr_param['text'] = $message;
        $obj = $this->telegramSendCustom($telegram_token, $arr_param, $user_id);
        return $obj;
    }
}

//Класс для работы с Яндекс Диск
class LibYD extends MainLib {
    //Получение списка папок
    function ydFolderInfo($path='/',$param='') {
        $url = "resources?path=".urlencode($path);
        if($param!='') {
            $url .= $param;
        }
        $ch = curl_init("https://cloud-api.yandex.net/v1/disk/".$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: OAuth ".YD_TOKEN));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($res);
        return $res;
    }
    //Создание новой папки
    function ydFolderAdd($path='', $type=1) {
        if($type==1) {
            $path = "disk:/MAINPHOTO/".$this->cms_str_param($path);
        }
        else if($type==2) {
            $path = "disk:/MAINEVENT/".$this->cms_str_param($path);
        }
        $url = "resources?path=".urlencode($path);
        $ch = curl_init("https://cloud-api.yandex.net/v1/disk/".$url);
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: OAuth ".YD_TOKEN));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($res);
        return $res;
    }
    //Создание новой папки
    function ydFolderCopy($path_from='',$path_to='', $type=1) {
        if($type==1) {
            $path_from = "disk:/MAINPHOTO/".$this->cms_str_param($path_from)."/";
            $path_to = "disk:/MAINPHOTO/".$this->cms_str_param($path_to)."/";
        }
        else if($type==2) {
            $path_from = "disk:/MAINEVENT/".$this->cms_str_param($path_from)."/";
            $path_to = "disk:/MAINEVENT/".$this->cms_str_param($path_to)."/";
        }

        $url = "resources/copy?from=".urlencode($path_from)."&path=".urlencode($path_to);//."&overwrite=true"
        $ch = curl_init("https://cloud-api.yandex.net/v1/disk/".$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: OAuth ".YD_TOKEN));
        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($res);
        return $res;
    }
    //Удаление папки или файла
    function ydDelete($path='', $type=1) {
        if($type==1) {
            $path = "disk:/MAINPHOTO/".$this->cms_str_param($path);
        }
        else if($type==2) {
            $path = "disk:/MAINEVENT/".$this->cms_str_param($path);
        }

        $url = "resources?path=".urlencode($path).""; //&permanently=true
        $ch = curl_init("https://cloud-api.yandex.net/v1/disk/".$url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: OAuth ".YD_TOKEN));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if(in_array($http_code, array(202, 204))) {
            //Успех
        }
        return $http_code;
    }
    //Получение пути для загрузки файлов
    function ydGetPath($path='', $file_name='', $type=1) {
        if($type==1) {
            $path = "disk:/MAINPHOTO/".$this->cms_str_param($path);
        }
        else if($type==2) {
            $path = "disk:/MAINEVENT/".$this->cms_str_param($path);
        }
        $url = "upload?path=".urlencode($path.$file_name)."&overwrite=true";
        $ch = curl_init("https://cloud-api.yandex.net/v1/disk/resources/".$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: OAuth ".YD_TOKEN));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($res);
        if(isset($res->error)) {
            return "error";
        }
        else {
            return $res->href;
        }
    }
    //Загрузка файлов на Яндекс Диск
    function ydLoadFile($ya_path='', $file_path='') {
        $fp = fopen($file_path, 'r');

        $ch = curl_init($ya_path);
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_UPLOAD, true);
        curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file_path));
        curl_setopt($ch, CURLOPT_INFILE, $fp);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $http_code;
    }
    //Получение публичной ссылки для скачивания папки
    function ydPublish($path='/', $type=1) {
        if($type==1) {
            $path = "disk:/MAINPHOTO/".$this->cms_str_param($path);
        }
        else if($type==2) {
            $path = "disk:/MAINEVENT/".$this->cms_str_param($path);
        }
        $url = "publish?path=".urlencode($path);
        $ch = curl_init("https://cloud-api.yandex.net/v1/disk/resources/".$url);
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: OAuth ".YD_TOKEN));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($res);
        return $res;
    }
    //Удаление публичной ссылки
    function ydUnpublish($path='/', $type=1) {
        if($type==1) {
            $path = "disk:/MAINPHOTO/".$this->cms_str_param($path);
        }
        else if($type==2) {
            $path = "disk:/MAINEVENT/".$this->cms_str_param($path);
        }
        $url = "unpublish?path=".urlencode($path);
        $ch = curl_init("https://cloud-api.yandex.net/v1/disk/resources/".$url);
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: OAuth ".YD_TOKEN));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($res);
        return $res;
    }
}