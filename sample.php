<?php
require(__DIR__ . '/vendor/autoload.php');

use \basebuy\basebuyAutoApi\BasebuyAutoApi;
use \basebuy\basebuyAutoApi\connectors\CurlGetConnector;
use \basebuy\basebuyAutoApi\exceptions\EmptyResponseException;

define ("API_KEY", "");
$last_date_update = strtotime('01.01.2016 00:00:00'); // Дата последнего обращения к API, чтобы сперва сделать проверку, а уже потом выкачивать файлы
$id_type = 1; // Легковые автомобили (полный список можно получить через $basebuyAutoApi->typeGetAll())

$basebuyAutoApi = new BasebuyAutoApi(
    new CurlGetConnector( API_KEY )
);

try {


    if ( $basebuyAutoApi->typeGetDateUpdate() > $last_date_update){
        $downloadedFilePath = $basebuyAutoApi->typeGetAll();
    }
    /*
        if ( $basebuyAutoApi->markGetDateUpdate( $id_type ) > $last_date_update){
            print_r($basebuyAutoApi->markGetDateUpdate( $id_type, BasebuyAutoApi::FORMAT_STRING ));
            $downloadedFilePath = $basebuyAutoApi->markGetAll( $id_type );
        }

        if ( $basebuyAutoApi->modelGetDateUpdate( $id_type ) > $last_date_update){
            $downloadedFilePath = $basebuyAutoApi->modelGetAll( $id_type );
        }

        if ( $basebuyAutoApi->generationGetDateUpdate( $id_type ) > $last_date_update){
            $downloadedFilePath = $basebuyAutoApi->generationGetAll( $id_type );
        }

        if ( $basebuyAutoApi->serieGetDateUpdate( $id_type ) > $last_date_update){
            $downloadedFilePath = $basebuyAutoApi->serieGetAll( $id_type );
        }

        if ( $basebuyAutoApi->modificationGetDateUpdate( $id_type ) > $last_date_update){
            $downloadedFilePath = $basebuyAutoApi->modificationGetAll( $id_type );
        }

        if ( $basebuyAutoApi->characteristicGetDateUpdate( $id_type ) > $last_date_update){
            $downloadedFilePath = $basebuyAutoApi->characteristicGetAll( $id_type );
        }

        if ( $basebuyAutoApi->characteristicValueGetDateUpdate( $id_type ) > $last_date_update){
            $downloadedFilePath = $basebuyAutoApi->characteristicValueGetAll( $id_type );
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

    if ( $e->getCode() == 401 ){
        echo '<pre>'.$e->getMessage()."\nУказан неверный API-ключ или срок действия вашего ключа закончился. Обратитесь в службу поддержки по адресу support@basebuy.ru</pre>";
    }

    if ( $e->getCode() == 404 ){
        echo '<pre>'.$e->getMessage()."\nПо заданным параметрам запроса невозможно построить результат. Проверьте наличие параметра id_type, который обязателен для всех сущностей, кроме собственно type.</pre>";
    }

    if ( $e->getCode() == 500 ){
        echo '<pre>'.$e->getMessage()."\nВременные перебои в работе сервиса.</pre>";
    }

    if ( $e->getCode() == 501 ){
        echo '<pre>'.$e->getMessage()."\nЗапрошено несуществующее действие для указанной сущности.</pre>";
    }

    if ( $e->getCode() == 503 ){
        echo '<pre>'.$e->getMessage()."\nВременное прекращение работы сервиса в связи с обновлением базы данных.</pre>";
    }
}