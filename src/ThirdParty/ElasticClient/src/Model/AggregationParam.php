<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ElasticClient\Model;

/**
 * Description of AggregationParam
 *
 * @author prabin
 */
class AggregationParam {
    private $field;
    private $size=10;
    private $minDocCount=1;
    private $sort;
    private $type="term";
    private $nestedAggregation=array();
    
    public function __construct($field, $size=10, $minDocCount=1, $sort="", $type="term") {
        $this->field = $field;
        $this->size = $size;
        $this->minDocCount = $minDocCount;
        $this->sort = $sort;
        $this->type = $type;
    }
    
    function getType() {
        return $this->type;
    }

    function setType($type) {
        $this->type = $type;
    }
        
    function getSort() {
        return $this->sort;
    }

    function setSort($sort) {
        $this->sort = $sort;
    }
        
    function getMinDocCount() {
        return $this->minDocCount;
    }

    function setMinDocCount($minDocCount) {
        $this->minDocCount = $minDocCount;
    }

        
    function getNestedAggregation() {
        return $this->nestedAggregation;
    }

    function setNestedAggregation($nestedAggregation) {
        $this->nestedAggregation = $nestedAggregation;
    }
    
    function addNestedAggregation($field, $size=10) {
        $this->nestedAggregation[] = new AggregationParam($field, $size);
    }
        
    function getField() {
        return $this->field;
    }

    function getSize() {
        return $this->size;
    }

    function setField($field) {
        $this->field = $field;
    }

    function setSize($size) {
        $this->size = $size;
    }


}
