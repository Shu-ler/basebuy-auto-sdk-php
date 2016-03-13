<?php
namespace basebuy\basebuyAutoApi\connectors;


use basebuy\basebuyAutoApi\IConnector;


class FileGetConnector implements IConnector
{
    /** @var string */
    private $_url;

    /** @var string */
    private $_api_kei;

    /**
     * SimpleConnector constructor.
     *
     * @param string $api_kei
     * @param string $url
     */
    public function __construct($api_kei, $url = 'http://api.basebuy.ru/api/auto/v1/')
    {
        $this->_api_kei = $api_kei;
        $this->_url     = $url;
    }


    public function get($method, $format, $params = [])
    {
        $requestUrl = implode('?', [
            $this->_url.$method.'.'.$format,
            http_build_query($this->addApiKey($params)),
        ]);
        return file_get_contents($requestUrl);
    }

    protected function addApiKey($params)
    {
        $params['api_key'] = $this->_api_kei;

        return $params;
    }
}