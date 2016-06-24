<?php
namespace ElasticClient\Extension\Aggregation;

use Elastica\Aggregation\AbstractAggregation;
use Elastica\Filter\AbstractFilter;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Filters
 *
 * @author prabin
 */
class Filters extends AbstractAggregation{
    /**
     * Filters
     * @var array
     */
    protected $_filters = array();
    
    /**
     * 
     * @param \Elastica\Filter\AbstractFilter $filter
     * @return \ElasticClient\Extension\Aggregation\Filters
     */
    public function addFilter($name, AbstractFilter $filter)
    {
        $this->_filters[$name] = $filter->toArray();
        return $this;
    }
    
    public function toArray()
    {
        $array = array(
            "filters" => array("filters" => $this->_filters)
        );
        
        if($this->_aggs)
        {
            $array['aggs'] = $this->_aggs;
        }
        return $array;
    }
}

?>
