<?php
namespace ElasticClient2\Extension\Query;

use Elastica\Query\SimpleQueryString;
use Elastica\Util;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CustomQueryString
 *
 * @author prabin
 */
class CustomQueryString extends SimpleQueryString{
    
    /**
     * @param string $query
     * @param array $fields
     */
    public function __construct($query, array $fields = array(), $flags="")
    {
        $replacementMap = array('AND'=>'+', 'OR'=>'|', 'NOT'=>'-');
        $query = strtr($query, $replacementMap);
        $this->setQuery($query);
        if (sizeof($fields)) {
            $this->setFields($fields);
        }
        if(empty($flags)){
            $flags = "OR|AND|NOT|PHRASE";
        }
        $this->setFlags($flags);
    }
    
    public function setFlags($flags)
    {
        return $this->setParam("flags", $flags);
    }
    
    protected function _getBaseName()
    {
        return Util::getParamName("SimpleQueryString");
    }
}

?>
