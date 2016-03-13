<?php
namespace basebuy\basebuyAutoApi\models;


use ReflectionClass;
use ReflectionProperty;


abstract class BaseModel
{
    /** @var string[] */
    private $_attributes = false;

    /**
     * @return string[]
     */
    protected function attributes()
    {
        if($this->_attributes === false) {
            $class = new ReflectionClass($this);
            $this->_attributes = [];
            foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
                if (!$property->isStatic()) {
                    $this->_attributes[] = $property->getName();
                }
            }
        }

        return $this->_attributes;
    }

    public function __construct($params)
    {
        foreach($this->attributes() as $attribute) {
            if(isset($params[$attribute])) {
                $this->{$attribute} = $params[$attribute];
            }
        }
    }

}