<?php
namespace ElasticClient;

use Elastica\Query\BoolQuery;
use Elastica\Query\HasChild;
use Elastica\Query\HasParent;
use Elastica\Query\Nested;
use Elastica\Query\Range;
use Elastica\Query\Terms;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QueryHelper
 *
 * @author prabin
 */
class QueryHelper {
    const BOOL_OR = "or";
    const BOOL_AND = "and";
    const BOOL_NOT = "not";
     
    public static function addTermQueryToBool(BoolQuery &$boolFilter, $field, $value, $operand=self::BOOL_AND){
        if((is_array($value) && count($value)>0 )|| (!is_array($value) && trim($value)!="" )){

            $filter = self::getTermQuery($field, $value);
            if($operand == self::BOOL_NOT){
                $boolFilter->addMustNot($filter);
            }else if($operand == self::BOOL_OR){
                $boolFilter->addShould($filter);
            }else{
                $boolFilter->addMust($filter);
            }
        }
    }
    
    public static function addRangeQueryToBool(BoolQuery &$boolFilter, $start, $end, $field, $ignoreValue = null, $operand=self::BOOL_AND){
        if($start||$end){
            $filter = self::getRangeQuery($start, $end, $field, $ignoreValue);
            if($operand == self::BOOL_NOT){
                $boolFilter->addMustNot($filter);
            }else if($operand == self::BOOL_OR){
                $boolFilter->addShould($filter);
            }else{
                $boolFilter->addMust($filter);
            }
        }
    }
     
    /**
     * exmaple complex filter
     * for parent child
     * <actual Field>|<(parent/child>:<parent/child type name>
     * for nested
     * <parent Field>|<actual Field>
     * @param type $field
     * @param type $value
     * @return Terms|Nested|HasParent|HasChild
     */ 
    private static function getTermQuery($field, $value){
        $fieldPart = explode("|", $field, 2);
        if(count($fieldPart) > 1){
            $ownerParts = explode(":", $fieldPart[1]);
            if(count($ownerParts) > 1){
                $type = $ownerParts[1];
                $filter = self::getTermQuery($type.".".$fieldPart[0], $value);
                switch ($ownerParts[0]) {
                    case "parent":
                        return new HasParent($filter, $type);
                    case "child":
                        return new HasChild($filter, $type);
                        break;

                    default:
                        break;
                }
            }
            $filter = new Nested();
            $filter->setPath($fieldPart[0]);
            $filter->setFilter(self::getTermQuery($fieldPart[0].".".$fieldPart[1], $value));
            return $filter;
        }
        return new Terms($field, is_array($value)?$value:explode(",",$value));
    }
    
    /**
     * for parent child field
     * <actual Field>|<(parent/child>:<parent/child type name>
     * @param type $start
     * @param type $end
     * @param type $field
     * @param type $ignoreValue
     * @return HasParent|HasChild|Range
     */
    public static function getRangeQuery($start, $end, $field, $ignoreValue = null){
        $rangeParam = array();
        if($start !== $ignoreValue){
            $rangeParam["gte"] = $start;
        }
        if($end !== $ignoreValue){
            $rangeParam["lte"] = $end;
        }
        $fieldParts = explode("|", $field);
        if(count($fieldParts) > 1){
            $field = $fieldParts[0];
            $ownerParts = explode(":", $fieldParts[1]);
            if(count($ownerParts) > 1){
                $type = $ownerParts[1];
                $rangeFilter = new Range($type.".".$field, $rangeParam);
                switch ($ownerParts[0]) {
                    case "parent":
                        return new HasParent($rangeFilter, $type);
                    case "child":
                        return new HasChild($rangeFilter, $type);
                        break;

                    default:
                        break;
                }
            }
        }
        return new Range($field, $rangeParam);
    }
}