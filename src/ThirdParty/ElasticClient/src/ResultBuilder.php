<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ElasticClient;

use Elastica\ResultSet;
use ElasticClient\Model\AggregationParam;
use ElasticClient\Model\AggregationResult;
use ElasticClient\Model\SearchParam;
use ElasticClient\Model\SearchResultPage;
use Exception;

/**
 * Description of ResultBuilder
 *
 * @author prabin
 */
class ResultBuilder {
    
    private $res;
    private $sp;
    private $className;
    
    function __construct($res, $sp, $className="") {
        $this->res = $res;
        $this->sp = $sp;
        $this->className = $className;
    }
    
    protected function prepareDataObject($sourceArr, $className){
        if(empty($className)){
            return $sourceArr;
        }
        $obj = new $className();
        if(!($obj instanceof IndexDocumentInterface)){
            throw new Exception("$className passed in for result construction should implement IndexDocumentInterface");
        }
        $obj->fillFromSearchResult($sourceArr);
        return $obj;
    }
    
    public function getSRP(&$srp){
        $res = $this->res;
        $sp = $this->sp;
        $className = $this->className;
        $srp->setRawResult($res);
        $srp->setTotalCount($res->getTotalHits());
        $hits = $res->getResults();
        $aggregationData = $res->getAggregations();
        $idArr = array();
        $dataArr = array();
        foreach ($hits as $hit) {
            $ukey = $hit->getId();
            $idArr[] = $ukey;
            $dataArr[] = $this->prepareDataObject($hit->getSource(), $className);
        }
        $srp->setIdArray($idArr);
        $srp->setObjArray($dataArr);
        if($sp->getAggregationList()){
            $srp->setAggregationDataMap($this->extractAllAggregationValues($aggregationData, $sp->getAggregationList()));
        }
        return $srp;
    }
    
    private function extractAllAggregationValues($rawAggData, $requestedAggs){
        $aggCountArr = array();
        foreach ($requestedAggs as $aggParam){
            $field = $aggParam->getField();
            if(array_key_exists($field, $rawAggData)){
                $aggCountArr[$field] = $this->extractAggregationValue($rawAggData[$field], $aggParam);
            }
        }
        return $aggCountArr;
    }
    
    private function extractAggregationValue($rawAggData, AggregationParam $aggParam){
        $field = $aggParam->getField();
        $aggCounts = array();
        if($aggParam->getNestedAggregation()){
            $parentField = $field;
            if(array_key_exists($parentField, $rawAggData)){
                $parentAggData = $rawAggData[$parentField];
                $parentKey = array_key_exists("key_as_string", $parentAggData)?$parentAggData["key_as_string"]:$parentAggData["key"];
                if(empty($parentKey)){
                    $parentKey = $field;
                }
                if(array_key_exists("buckets", $parentAggData)){
                    $parentAggBuckets = $parentAggData["buckets"];
                    foreach ($parentAggBuckets as $bucket) {
                        $key = array_key_exists("key_as_string", $bucket)?$bucket["key_as_string"]:$bucket["key"];
                        foreach ($aggParam->getNestedAggregation() as $nestedAggParam) {
                            $aggCounts[$key] = $this->extractAggregationValue($bucket, $nestedAggParam);
                        }
                    }
                }
                
            }
        }elseif(array_key_exists($field, $rawAggData)){
            //case of children aggregations
            return $this->extractAggregationValue($rawAggData[$field], $aggParam);
        }elseif(array_key_exists("buckets", $rawAggData)) {
            $fieldPart = explode("||", $field);
            $total = CommonUtil::getArrayValueSafe($rawAggData, "sum_other_doc_count", 0);
            if(count($fieldPart) > 1){
                foreach ($rawAggData["buckets"] as $resKey => $data) {
                    $total += round((float)($data['doc_count']), 2);
                }
            }
            foreach ($rawAggData["buckets"] as $resKey => $data) {
                $key = array_key_exists("key_as_string", $data)?$data["key_as_string"]:$data["key"];
                if(empty($key)){
                    $key = $resKey;
                }
                $count = round((float)($data['doc_count']), 2);
                if(count($fieldPart) > 1){
                    switch ($fieldPart[0]) {
                        case SearchParam::FACET_OPERAND_PERCENT:
                            if($total > 0 ){
                                $count = round((round((float)($data['doc_count']), 2)*100)/$total,2);
                            }else{
                                $count = 0;
                            }
                            break;
                        default:
                            break;
                    }
                }
                $aggCounts[$key] = $count;
            }
            if(count($fieldPart) > 2){
                $aggCounts = CommonUtil::getArrayValueSafe($aggCounts, $fieldPart[2], 0);
            }
        }else{
            $key = $field;
            if(array_key_exists("doc_count", $rawAggData)){
                $aggCounts = (int)($rawAggData['doc_count']);
            }else if(array_key_exists("values", $rawAggData)){
                // case of percentiles
                $valArr = $rawAggData["values"];
                $finalValArr = array();
                foreach ($valArr as $pkey => $value) {
                    if(!strpos($pkey, "_as_string")){
                        $finalValArr[$pkey] = $value;
                    }
                }
                $aggCounts[$key] = $finalValArr;
            }else{
                $aggCounts = (int)($rawAggData['value']);
            }
        }
        return $aggCounts;
    }
}
