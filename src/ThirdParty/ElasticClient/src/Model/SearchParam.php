<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ElasticClient\Model;

/**
 * Description of SearchParam
 *
 * @author prabin
 */
class SearchParam {
    const FACET_OPERAND_PERCENT = "percent";
    const FACET_OPERAND_PERCENTILE = "percentile";
    const FACET_OPERAND_SUM = "sum";
    const FACET_OPERAND_AVG = "avg";
    const FACET_OPERAND_UNIQUE = "unique";
    const FACET_OPERAND_WEIGHTED_AVG = "weighted-avg";
    
    private $index;
    private $type;
    private $query;
    private $andFilters;
    private $orFilters;
    private $notFilters;
    private $rangeFilters;
    private $aggregationList=array();
    private $aggregationOnly=false;
    private $offset=0;
    private $pageSize=10;
    private $searchFields=array(); 
    private $resultFields=array();
    
    function getIndex() {
        return $this->index;
    }

    function getType() {
        return $this->type;
    }

    function setIndex($index) {
        $this->index = $index;
    }

    function setType($type) {
        $this->type = $type;
    }
        
    function getResultFields() {
        return $this->resultFields;
    }

    function setResultFields($resultFields) {
        $this->resultFields = $resultFields;
    }
        
    function getSearchFields() {
        return $this->searchFields;
    }

    function setSearchFields($searchFields) {
        $this->searchFields = $searchFields;
    }
            
    function getAggregationOnly() {
        return $this->aggregationOnly;
    }

    function setAggregationOnly($aggregationOnly) {
        $this->aggregationOnly = $aggregationOnly;
    }
        
    function getQuery() {
        return $this->query;
    }

    function setQuery($query) {
        $this->query = $query;
    }
        
    function getAndFilters() {
        return $this->andFilters;
    }

    function getOrFilters() {
        return $this->orFilters;
    }

    function getNotFilters() {
        return $this->notFilters;
    }

    function getRangeFilters() {
        return $this->rangeFilters;
    }

    function setAndFilters($andFilters) {
        $this->andFilters = $andFilters;
    }

    function setOrFilters($orFilters) {
        $this->orFilters = $orFilters;
    }

    function setNotFilters($notFilters) {
        $this->notFilters = $notFilters;
    }

    function setRangeFilters($rangeFilters) {
        $this->rangeFilters = $rangeFilters;
    }
        
    function getAggregationList() {
        return $this->aggregationList;
    }

    function getOffset() {
        return $this->offset;
    }

    function getPageSize() {
        return $this->pageSize;
    }

    function setAggregationList($aggregationList) {
        $this->aggregationList = $aggregationList;
    }
    
    function addAggregation($field, $size=10) {
        $this->aggregationList[] = new AggregationParam($field, $size);
    }
    
    function addAggregationParamObj($aggregationParam) {
        $this->aggregationList[] = $aggregationParam;
    }

    function setOffset($offset) {
        $this->offset = $offset;
    }

    function setPageSize($pageSize) {
        $this->pageSize = $pageSize;
    }
    
    public function addToRangeFilters($field, $min, $max) {
        if(CommonUtil::isEmpty($this->rangeFilters)){
            $this->rangeFilters = array();
        }
        $this->rangeFilters[$field] = array("min"=>$min, "max"=>$max);
    }
    
    public function addToAndFilter($field, $value) {
        $this->setAndFilters($this->addToFilter($this->getAndFilters(), $field, $value));
    }
    
    public function addToORFilter($field, $value) {
        $this->setOrFilters($this->addToFilter($this->getOrFilters(), $field, $value));
    }
    
    public function addToNOTFilter($field, $value) {
        $this->setNotFilters($this->addToFilter($this->getNotFilters(), $field, $value));
    }
    
    private function addToFilter($filterArr, $field, $value){
        if (!$filterArr) {
            $filterArr = array();
        }
        if (!is_array($value)) {
            $value = array($value);
        }
        if (array_key_exists($field, $filterArr)) {
            $currVal = $filterArr[$field];
            if (is_array($currVal)) {
                $value = array_merge($value, $currVal);
            } else {
                $value[] = $currVal;
            }
        }
        $filterArr[$field] = $value;
        return $filterArr;
    }
}
