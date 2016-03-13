<?php
namespace basebuy\basebuyAutoApi\connectors;


use \basebuy\basebuyAutoApi\IConnector;
use \basebuy\basebuyAutoApi\exceptions\EmptyResponseException;
use \Curl\Curl;


class CurlGetConnector implements IConnector
{
    /** @var string */
    private $_url;

    /** @var string */
    private $_api_kei;

    public $_tmp_dir;

    /**
     * SimpleConnector constructor.
     *
     * @param string $api_kei
     * @param string $url
     * @param string $tmp_dir
     */
    public function __construct($api_kei, $url = 'http://api.basebuy.ru/api/auto/v1/', $tmp_dir = null)
    {
        $this->_api_kei = $api_kei;
        $this->_url     = $url;
        $this->_tmp_dir = $tmp_dir ? $tmp_dir : $_SERVER['DOCUMENT_ROOT'].'/tmp/';
    }


    public function get($method, $format, $params = [])
    {

        $result = null;
        $curl = new Curl();
        $curl->get($this->_url.$method.'.'.$format, $this->addApiKey($params));
        if ($curl->error) {
            throw new EmptyResponseException( $curl->errorMessage,  $curl->errorCode );
        }
        else {
            $result = $curl->response;
        }

        $curl->close();

        return $result;
    }

    public function download($method, $format, $params = [])
    {
        $requestUrl = implode('?', [
            $this->_url.$method.'.'.$format,
            http_build_query($this->addApiKey($params)),
        ]);

        $resultFileName = $this->_tmp_dir.$method.'.'.$format;
        $curl = new Curl();
        if ( $curl->download($requestUrl, $resultFileName) ) {
            $result = $resultFileName;
        } else {
            throw new EmptyResponseException( $curl->errorMessage,  $curl->errorCode );
        }
        $curl->close();

        return $result;
    }

    protected function addApiKey($params)
    {
        $params['api_key'] = $this->_api_kei;

        return $params;
    }
}