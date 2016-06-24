<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ElasticClient;

use Elastica\Aggregation\Avg;
use Elastica\Aggregation\Cardinality;
use Elastica\Aggregation\Percentiles;
use Elastica\Aggregation\ScriptedMetric;
use Elastica\Aggregation\Sum;
use Elastica\Aggregation\Terms;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchAll;
use Elastica\Query\QueryString;
use ElasticClient\Model\AggregationParam;
use ElasticClient\Model\SearchParam;

/**
 * Description of QueryBuilder
 *
 * @author prabin
 */
class QueryBuilder {
    protected $sp;
    
    public function __construct($sp) {
        $this->sp = $sp;
    }
    
    public function buildQuery(){
        $mainQuery = new Query();
        $query = $this->getQueryObject($this->sp);
        if($query->hasParam('filter') || $query->hasParam('query')){
            $mainQuery->setQuery($query);
        }else{
            $mainQuery->setQuery(new MatchAll());
        }
        if($this->sp->getAggregationList()){
            $this->prepareAggregations($mainQuery, $this->sp->getAggregationList());
        }
        $mainQuery->setSize($this->sp->getPageSize());
        $mainQuery->setFrom($this->sp->getOffset());
        if($this->sp->getResultFields()){
            $mainQuery->setSource($this->sp->getResultFields());
        }
        $this->setSorting($mainQuery, $this->sp);
        return $mainQuery;
    }
    
    protected function setSorting(Query &$mainQuery, $sp){
        if($sp->getAggregationOnly()){
            return;
        }
        $sortOrder = "desc";
        $sortBy = "_score";
        //add sorting code
        
    }
    
    protected function getQueryObject($sp){
        $query = new BoolQuery();
        $qStr = $sp->getQuery();
        if(!empty($qStr)){
            $simple = new QueryString();
            $simple->setQuery($qStr);
            if($sp->getSerachFields()){
                $simple->setFields($sp->getSearchFields());
            }
            $query->addMust($simple);
        }
        $boolFilters = $this->getFilterQuery($sp);
        if ($boolFilters->toArray()) {
            $query->addFilter($boolFilters);
        }
        return $query;
    }
    
    protected function getFilterQuery($sp){
        $boolFilter = new BoolQuery();
        if($sp->getRangeFilters()){
            foreach ($sp->getRangeFilters() as $field => $value) {
                QueryHelper::addRangeQueryToBool($boolFilter, $value["min"], $value["max"], $field);
            }
        }
        if($sp->getAndFilters()){
            foreach ($sp->getAndFilters() as $field => $value) {
                QueryHelper::addTermQueryToBool($boolFilter, $field, $value);
            }
        }
        if($sp->getOrFilters()){
            foreach ($sp->getOrFilters() as $field => $value) {
                QueryHelper::addTermQueryToBool($boolFilter, $field, $value, QueryHelper::BOOL_OR);
            }
        }
        if($sp->getNotFilters()){
            foreach ($sp->getNotFilters() as $field => $value) {
                QueryHelper::addTermQueryToBool($boolFilter, $field, $value, QueryHelper::BOOL_NOT);
            }
        }
        $customFilter = $this->prepareCustomFilters($sp);
        if($customFilter){
           $cf = $customFilter->toArray();
           if(!empty($cf)){
            $boolFilter->addMust($customFilter);
           }
        }
        return $boolFilter;
    }
    
    protected function prepareCustomFilters($sp){
        //override for custom filters which are not term or range filters
        return false;
    }
    
    private function prepareAggregations(Query &$mainQuery, $aggregationsList) {
        $aggObjList = array();
        foreach ($aggregationsList as $aggregation) {
            $mainQuery->addAggregation($this->getAgrregationObj($aggregation));
        }
    }

    private function getAgrregationObj(AggregationParam $aggregation) {
        $aggObj = $this->getMainIndexAgrregationObj($aggregation);
        if ($aggObj && $aggregation->getNestedAggregation()) {
            foreach ($aggregation->getNestedAggregation() as $nestedAgg) {
                $nestedAgg = $this->getAgrregationObj($nestedAgg);
                if($nestedAgg) {
                    $aggObj->addAggregation($nestedAgg);
                }
            }
        }
        return $aggObj;
    }

    private function getMainIndexAgrregationObj(AggregationParam $aggregation) {
        $aggObj = false;
        $aggField = $aggregation->getField();
        $parts = explode("||", $aggField);
        if(count($parts) > 1){
            $operand = strtolower($parts[0]);
            $field = $parts[1];
            $countField = "";
            if(count($parts) > 2){
                $countField = $parts[2];
            }
            switch ($operand) {
                case SearchParam::FACET_OPERAND_SUM:
                    $aggObj = new Sum($aggField);
                    $aggObj->setField($field);
                    break;
                case SearchParam::FACET_OPERAND_AVG:
                    $aggObj = new Avg($aggField);
                    $aggObj->setField($field);
                    break;
                case SearchParam::FACET_OPERAND_WEIGHTED_AVG:
                    if(!empty($countField)){
                        $aggObj = new ScriptedMetric($aggField);
                        $aggObj->setInitScript("_agg['weightedSum'] = 0d; _agg['countSum'] = 0L;");
                        $aggObj->setMapScript("_agg['weightedSum'] = _agg['weightedSum'] + doc['$field'].value * doc['$countField'].value; _agg['countSum'] = _agg['countSum'] + doc['$countField'].value;");
                        $aggObj->setReduceScript("weightedSum = 0d; countSum = 0L; for(a in _aggs) {weightedSum += a.weightedSum; countSum += a.countSum;};if(countSum == 0L) {return 0d;} else {return (weightedSum / countSum).round(2)}");
                    }
                    break;
                case SearchParam::FACET_OPERAND_PERCENTILE:
                    $aggObj = new Percentiles($aggField, $field);
                    $aggObj->setPercents(array(50,75, 90, 95, 99));
                    break;
                case SearchParam::FACET_OPERAND_UNIQUE:
                    $aggObj = new Cardinality($aggField);
                    $aggObj->setField($field);
                    break;
                case SearchParam::FACET_OPERAND_PERCENT:
                    $aggObj = new Terms($aggField);
                    $aggObj->setField($field);
                    break;
                default:
                    break;
            }

        }else{
            switch ($aggregation->getType()) {
                case "term":
                    $aggObj = new Terms($aggField);
                    $aggObj->setField($aggField);
                    break;
                default:
                    $aggObj = $this->prepareCustomAggregation($aggregation);
            }
        }
        
        if($aggObj && $aggObj instanceof Terms){
            $aggObj->setSize($aggregation->getSize());
            $aggObj->setMinimumDocumentCount($aggregation->getMinDocCount());
            //set sorting if required
        }
        return $aggObj;
    }
    
    protected function prepareCustomAggregation(AggregationParam $aggregation){
        //override to return custom aggregations
        return false;
    }
}
