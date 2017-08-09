<?php
require(__DIR__ . '/vendor/autoload.php');

use \basebuy\basebuyAutoApi\BasebuyAutoApi;
use \basebuy\basebuyAutoApi\connectors\CurlGetConnector;
use \basebuy\basebuyAutoApi\exceptions\EmptyResponseException;

define ("API_KEY", "");
define ("API_URL", "https://api.basebuy.ru/api/auto/v1/");
$downloadFolder = $_SERVER['DOCUMENT_ROOT'];

$lastDateUpdate = strtotime('01.01.2016 00:00:00'); // Дата последнего обращения к API, чтобы сперва сделать проверку, а уже потом выкачивать файлы
$idType = 1; // Легковые автомобили (полный список можно получить через $basebuyAutoApi->typeGetAll())

$basebuyAutoApi = new BasebuyAutoApi(
    new CurlGetConnector( API_KEY, API_URL, $downloadFolder)
);

try {


    if ( $basebuyAutoApi->typeGetDateUpdate() > $lastDateUpdate){
        $downloadedFilePath = $basebuyAutoApi->typeGetAll();
    }
    /*
        if ( $basebuyAutoApi->markGetDateUpdate( $idType ) > $lastDateUpdate){
            print_r($basebuyAutoApi->markGetDateUpdate( $idType, BasebuyAutoApi::FORMAT_STRING ));
            $downloadedFilePath = $basebuyAutoApi->markGetAll( $idType );
        }

        if ( $basebuyAutoApi->modelGetDateUpdate( $idType ) > $lastDateUpdate){
            $downloadedFilePath = $basebuyAutoApi->modelGetAll( $idType );
        }

        if ( $basebuyAutoApi->generationGetDateUpdate( $idType ) > $lastDateUpdate){
            $downloadedFilePath = $basebuyAutoApi->generationGetAll( $idType );
        }

        if ( $basebuyAutoApi->serieGetDateUpdate( $idType ) > $lastDateUpdate){
            $downloadedFilePath = $basebuyAutoApi->serieGetAll( $idType );
        }

        if ( $basebuyAutoApi->modificationGetDateUpdate( $idType ) > $lastDateUpdate){
            $downloadedFilePath = $basebuyAutoApi->modificationGetAll( $idType );
        }

        if ( $basebuyAutoApi->characteristicGetDateUpdate( $idType ) > $lastDateUpdate){
            $downloadedFilePath = $basebuyAutoApi->characteristicGetAll( $idType );
        }

        if ( $basebuyAutoApi->characteristicValueGetDateUpdate( $idType ) > $lastDateUpdate){
            $downloadedFilePath = $basebuyAutoApi->characteristicValueGetAll( $idType );
        }
        */

    $fp = fopen( $downloadedFilePath, 'r');
    if ($fp){
        while (!feof($fp)){
            $fileRow = fgets($fp, 999);
            echo $fileRow."<br />";
        }
    } else {
        echo "Ошибка при открытии файла";
    }
    fclose($fp);



} catch( Exception $e ){

    switch ( $e->getCode() ){
        case 401:
            echo '<pre>'.$e->getMessage()."\nУказан неверный API-ключ или срок действия вашего ключа закончился. Обратитесь в службу поддержки по адресу support@basebuy.ru</pre>";
            break;

        case 404:
            echo '<pre>'.$e->getMessage()."\nПо заданным параметрам запроса невозможно построить результат. Проверьте наличие параметра id_type, который обязателен для всех сущностей, кроме собственно type.</pre>";
            break;

        case 500:
            echo '<pre>'.$e->getMessage()."\nВременные перебои в работе сервиса.</pre>";
            break;

        case 501:
            echo '<pre>'.$e->getMessage()."\nЗапрошено несуществующее действие для указанной сущности.</pre>";
            break;

        case 503:
            echo '<pre>'.$e->getMessage()."\nВременное прекращение работы сервиса в связи с обновлением базы данных.</pre>";
            break;

        default:
            echo '<pre>'.$e->getMessage()."</pre>";
    }
}