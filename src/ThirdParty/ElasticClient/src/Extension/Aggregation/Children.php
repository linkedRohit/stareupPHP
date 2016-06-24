<?php
namespace ElasticClient\Extension\Aggregation;

use Elastica\Aggregation\AbstractAggregation;


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Children
 *
 * @author prabin
 */
class Children extends AbstractAggregation{
    protected $_childTypeName;
    
    public function __construct($name, $typeName) {
        parent::__construct($name);
        $this->_childTypeName = $typeName;
    }
    
    public function getChildType(){
        return $this->_childTypeName;
    }
    
    public function toArray()
    {
        $array = array(
            "children" => array("type" => $this->_childTypeName)
        );
        
        if($this->_aggs)
        {
            $array['aggs'] = $this->_aggs;
        }
        return $array;
    }
    
    public function addAggregationAsKeyValue($aggKey, $aggValueArr)
    {
        $this->_aggs[$aggKey] = $aggValueArr;
        return $this;
    }
    
}

?>
