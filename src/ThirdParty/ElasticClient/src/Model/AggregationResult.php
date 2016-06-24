<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ElasticClient\Model;

/**
 * Hold Values of aggregation result
 *
 * @author prabin
 */
class AggregationResult {
    const OTHERS_FIELD_KEY = "Others";
    
    private $displayText;
    private $key;
    private $count;
    private $otherCount;
    private $nestedAggregationResultMap=array();
    
    function __construct($key, $count, $otherCount=0) {
        $this->key = $key;
        $this->count = $count;
        $this->otherCount = $otherCount;
    }
    
    function getOtherCount() {
        return $this->otherCount;
    }

    function setOtherCount($otherCount) {
        $this->otherCount = $otherCount;
    }
        
    function getKey() {
        return $this->key;
    }

    function setKey($key) {
        $this->key = $key;
    }
        
    function getDisplayText() {
        return $this->displayText;
    }

    function getCount() {
        return $this->count;
    }

    function setDisplayText($displayText) {
        $this->displayText = $displayText;
    }

    function setCount($count) {
        $this->count = $count;
    }
    
    function getNestedAggregationResultMap() {
        return $this->nestedAggregationResultMap;
    }

    function setNestedAggregationResultMap($nestedAggregationResultMap) {
        $this->nestedAggregationResultMap = $nestedAggregationResultMap;
    }
        
    function addToNestedAggregationResultMap($field, AggregationResult $aggResult) {
        $this->nestedAggregationDataList[$field] = $aggResult;
    }


}
