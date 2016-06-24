<?php
namespace ElasticClient;

use Elastica\Index;
use Elastica\Request;
use Elastica\Status;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NIndex
 * Wrapper around default elastica index to overrite/include new functions
 *
 * @author prabin
 */
class NIndex extends Index{
    
    /**
     * add alias with option for filters and routing
     * @param type $name
     * @param type $indexRouting
     * @param type $searchRouting
     * @param type $filter
     * @param type $replace
     * @return type Response
     */
    public function addAliasWithFilterAndRouting($name, $indexRouting, $searchRouting, $filter="", $replace = false)
    {
        $path = '_aliases';
        $data = array('actions' => array());

        if ($replace) {
            $status = new Status($this->getClient());
            foreach ($status->getIndicesWithAlias($name) as $index) {
                $data['actions'][] = array('remove' => array('index' => $index->getName(), 'alias' => $name));
            }
        }

        $data['actions'][] = array('add' => array('index' => $this->getName(), 'alias' => $name, 
            'index_routing'=>$indexRouting, "search_routing"=>$searchRouting, "filter"=>$filter));

        return $this->getClient()->request($path, Request::POST, $data);
    }
}

?>
