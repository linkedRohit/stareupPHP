<?php
namespace ElasticClient;

use Elastica\Client;
use Elastica\Query;
use Elastica\Query\Term;
use Elastica\Request;


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author prabin
 */
class NElasticClient extends Client{
    const DATA_PARAM_KEY = "data";
    const OPTION_PARAM_KEY = "option";
    
    public function __construct($host, $port, $username="", $password="", $callback = null) {
        $server = array();
        $authHeaderValue = 'Basic '.base64_encode("$username:$password").'==';
        $authHeader = array('Authorization'=>$authHeaderValue);
        $server['headers'] = $authHeader;
        $server["host"] = $host;
        $server["port"] = $port;
        $config = array('servers' => array($server));
        parent::__construct($config, $callback);
    }
    
    public function getIndex($name)
    {
        return new NIndex($this, $name);
    }
    
    public function setRefreshInterval($indexName, $interval="1s"){
        $index = $this->getIndex($indexName);
        $settings = $index->getSettings();
        return $settings->setRefreshInterval($interval);
    }
    
    public function addAlias($indexName, $aliasName, $indexRouting, $searchRouting, $fieldValueFilterArray=array()){
        $filter = "";
        if($fieldValueFilterArray){
            $termFilter = new Term($fieldValueFilterArray);
            $filter = $termFilter->toArray();
        }
        return $this->getIndex($indexName)->addAliasWithFilterAndRouting($aliasName, $indexRouting, $searchRouting, $filter);
    }
    
    public function prepareDocument($indexName, $typeName, $docId, $dataArray, $params = array(), $upsert=false){
        $type = $this->getIndex($indexName)->getType($typeName);
        $doc = $type->createDocument($docId, $dataArray);
        $doc->setParams(array_merge($doc->getParams(), $params));
        $doc->setRetryOnConflict(3);
        if($upsert){
            $doc->setDocAsUpsert(true);
        }
        return $doc;
    }
    
    public function addToIndex($indexName, $typeName, $docId, $dataArray, $params = array()){
        $type = $this->getIndex($indexName)->getType($typeName);
        return $type->addDocument($this->prepareDocument($indexName, $typeName, $docId, $dataArray, $params));
        
    }
    
    public function addUpdateToIndex($indexName, $typeName, $docId, $dataArray, $params = array()){
        return $this->updateDocument($docId, $this->prepareDocument($indexName, $typeName, $docId, $dataArray, $params, true), $indexName, $typeName);
    }
    
    public function addUpdateBulkToIndex($indexName, $typeName, $docIdDataArray){
        if(empty($docIdDataArray)){
            return;
        }
        $docArr = array();
        $type = $this->getIndex($indexName)->getType($typeName);
        foreach ($docIdDataArray as $docId => $dataArray) {
            $params = array();
            if(array_key_exists(self::OPTION_PARAM_KEY, $dataArray)){
                $params = $dataArray[self::OPTION_PARAM_KEY];
            }
            $doc = $this->prepareDocument($indexName, $typeName, $docId, $dataArray[self::DATA_PARAM_KEY], $params, true);
            $docArr[] = $doc;
        }
        return $this->updateDocuments($docArr);
    }
    
    public function search($indexName, $typeName, Query $query, $options = null){
        $type = $this->getIndex($indexName)->getType($typeName);
        $resultSet = $type->search($query, $options);
        return $resultSet;
    }
    
    public function deleteByQuery($indexName, $typeName, Query $query){
        $type = $this->getIndex($indexName)->getType($typeName);
        $res = $type->deleteByQuery($query);
        return $res;
    }
    
    /**
     * method to clear specific filter cache key
     * @param type $indexName
     * @param type $filterCacheKey
     */
    public function clearFilterCache($indexName, $filterCacheKey){
        if(!empty($filterCacheKey)){
            $path = '_cache/clear';
            $this->getIndex($indexName)->request($path, Request::POST, array(), array("filter_keys"=>$filterCacheKey));
        }
    }
}

?>
