<?php
namespace ElasticClient\Model;

use Elastica\ResultSet;
use ElasticClient\CommonUtil;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SearchResultPage
 *
 * @author prabin
 */
class SearchResultPage {
    const FACET_KEY_SEPARATOR = "_";
    private $totalCount = 0;
    private $idArray = array();
    private $aggregationDataMap = array();
    private $objArray=array();
    private $errors;
    private $rawResult;
    
    function getRawResult() {
        return $this->rawResult;
    }

    function setRawResult($rawResult) {
        $this->rawResult = $rawResult;
    }
        
    function getAggregationDataMap() {
        return $this->aggregationDataMap;
    }

    function setAggregationDataMap($aggregationDataMap) {
        $this->aggregationDataMap = $aggregationDataMap;
    }
                    
    public function isSuccess() {
        return CommonUtil::isEmpty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }

    public function setErrors($errors) {
        $this->errors = $errors;
    }
    
    public function addErrors($errors) {
        $errorArr = is_array($errors)?$errors:array($errors);
        $this->errors = CommonUtil::combine1DArray($this->errors, $errorArr);
    }
    
    function getObjArray() {
        return $this->objArray;
    }

    function setObjArray($objArray) {
        $this->objArray = $objArray;
    }
            
    public function getTotalCount() {
        return $this->totalCount;
    }

    public function setTotalCount($totalCount) {
        $this->totalCount = $totalCount;
    }

    public function getIdArray() {
        return $this->idArray;
    }

    public function setIdArray($idArray) {
        $this->idArray = $idArray;
    }
        
    public function __toString() {
        return "total: ".$this->totalCount."  ids: ".var_export($this->idArray, true);
    }
    
    public function fillFromResultSet(ResultSet $res, SearchParam $sp){
        $requestedFacets = $sp->getAggregationList();
        $this->setTotalCount($res->getTotalHits());
        $hits = $res->getResults();
        $facets = $res->getAggregations();
        $idArr = array();
        $dataArr = array();
        foreach ($hits as $hit) {
            $ukey = $hit->getId();
            $idArr[] = $ukey;
            $dataArr[] = CommonUtil::isEmpty($hit->getSource())?$hit->getFields():$hit->getSource();
        }
        $this->setIdArray($idArr);
        $this->setDataArray($dataArr);
        if($requestedFacets){
            $this->setAggregationArray($this->extractAllFacetValues($facets, $requestedFacets));
        }
    }
    
    private function extractAllFacetValues($rawFacetData, $requestedFacets){
        $facetCountArr = array();
        foreach ($requestedFacets as $facet){
            if(is_array($facet)){
                 $facetCountArr[$facet[0]] = $this->extractFacetValue($rawFacetData, $facet);
            }else{
                $facetCountArr[$facet] = $this->extractFacetValue($rawFacetData, $facet);
            }
        }
        return $facetCountArr;
    }
    
    private function extractFacetValue($facetData, $fieldVal){
        $facetCounts = array();
        $aggData = new AggregationData();
        if(is_array($fieldVal) && count($fieldVal) == 1){
            $field = $fieldVal[0];
        }else{
            $field = $fieldVal;
        }
        if(is_array($field)){
            $parentField = array_shift($field);
            if(array_key_exists($parentField, $facetData)){
                $parentFacetData = $facetData[$parentField];
                if(array_key_exists("buckets", $parentFacetData)){
                    $parentFacetBuckets = $parentFacetData["buckets"];
                    foreach ($parentFacetBuckets as $bucket) {
                        $key = array_key_exists("key_as_string", $bucket)?$bucket["key_as_string"]:$bucket["key"];
                            $facetCounts[$key] = $this->extractFacetValue($bucket, $field);
                    }
                }else{
                    
                }
            }
        }elseif(array_key_exists($field, $facetData)){
            //case of children aggregations
            return $this->extractFacetValue($facetData[$field], $field);
        }elseif(array_key_exists("buckets", $facetData)) {
            $fieldPart = explode("||", $fieldVal);
            $total = CommonUtil::getArrayValueSafe($facetData, "doc_count", 0);
            $aggDataList = array();
            foreach ($facetData["buckets"] as $resKey => $data) {
                $key = array_key_exists("key_as_string", $data)?$data["key_as_string"]:$data["key"];
                if(empty($key)){
                    $key = $resKey;
                }
                $keyData = new AggregationData();
                $keyData->setDisplayText($key);
                if(count($fieldPart) > 1){
                    $keyData->setValue($this->recalculateFacetValue($fieldPart[0], $total, $data['doc_count']));
                    if(count($fieldPart) > 2 && $key != $fieldPart[2]){
                        continue;
                    }
                }else{
                    $keyData->setValue(round((float)($data['doc_count']), 2));
                }
                $aggDataList[] = $keyData;
            }
            return $aggDataList;
        }else{
            $aggData->setDisplayText($field);
            $key = $field;
            if(array_key_exists("doc_count", $facetData)){
                $aggData->setValue(($facetData['doc_count']));
            }else if(array_key_exists("values", $facetData)){
                // case of percentiles
                $valArr = $facetData["values"];
                $finalValArr = array();
                foreach ($valArr as $pkey => $value) {
                    if(!strpos($pkey, "_as_string")){
                        $finalValArr[$pkey] = $value;
                    }
                }
                $aggData->setValue($finalValArr);
            }else{
                $aggData->setValue(($facetData['value']));
            }
        }
        return $aggData;
    }
    
    private function recalculateFacetValue($operation, $total, $value){
        switch ($operation) {
            case SearchParam::FACET_OPERAND_PERCENT:
                if($total > 0 ){
                    return round((round((float)($value), 2)*100)/$total,2);
                }else{
                    0;
                }
                break;

            default:
                return $value;
        }
    }

}

?>
