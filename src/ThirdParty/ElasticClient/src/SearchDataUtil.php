<?php
namespace ElasticClient;

use Elastica\Util;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SearchDataUtil
 *
 * @author prabin
 */
class SearchDataUtil extends Util{
    /**
     * Escapes the following terms (because part of the query language)
     * + - && || ! ( ) { } [ ] ^ " ~ * ? : \
     * modified from elastica default to ignore ( and )
     *
     * @param  string $term Query term to escape
     * @return string Escaped query term
     * @link http://lucene.apache.org/java/2_4_0/queryparsersyntax.html#Escaping%20Special%20Characters
     */
    public static function escapeTerm($term, $includeQuotes=true)
    {
        $result = $term;
        // \ escaping has to be first, otherwise escaped later once again
        $chars = array('\\', '+', '-', '&&', '||', '!', '{', '}', '[', ']', '^', '~', '*', '?', ':', '/');
        if($includeQuotes){
            $chars[] = '"';
        }

        foreach ($chars as $char) {
            $result = str_replace($char, '\\' . $char, $result);
        }

        return $result;
    }

    /**
     * Replace the following reserved words (because part of the query language)
     * AND OR NOT 
     * modified from elastica to include lowercased values
     *
     * @param  string $term Query term to replace
     * @return string Replaced query term
     * @link http://lucene.apache.org/java/2_4_0/queryparsersyntax.html#Boolean%20operators
     */
    public static function replaceBooleanWords($term)
    {
        //remove trailing or preciding boolean terms
        $result = preg_replace("/^(and|or) | (and|or|not)$/", "", trim(strtolower($term)));
        $replacementMap = array('and'=>'AND', 'or'=>'OR', 'not'=>'NOT');
        $result = strtr($result, $replacementMap);
        return $result;
    }
    
    public static function preProcessQueryData($str){
        //$str = self::adjustBrackets($str);
        $singleQuoteCount = substr_count($str, "'");
        if($singleQuoteCount%2 == 0){
            $str = str_replace("'", '"', $str);
        }
        $doubleQuoteCount = substr_count($str, '"');
        $str = self::escapeTerm($str, ($doubleQuoteCount%2));
        $str = self::formatStringExpression($str);
        return self::replaceBooleanWords($str);
    }
    
    private static function adjustBrackets($str){
        $openCount = substr_count($str, "(");
        $closeCount = substr_count($str, ")");
        if($openCount == $closeCount){
            return $str;
        }else if($openCount > $closeCount){
            $diff = $openCount - $closeCount;
            return $str.str_repeat(")", $diff);
        }else{
            $diff = $closeCount - $openCount;
            return str_repeat("(", $diff).$str;
        }
    }
    
    public static function formatStringExpression($str, $open="(", $close=")", $escape = true, $replace=" "){
        if(empty($str)){
            return $str;
        }
        $stack = array();
        $replaceStr1 = str_split($str);
        for($i=0;$i<strlen($str);$i++){
            if($str[$i] == $open){
                $arrayObject= array();
                $arrayObject[$i]=$open;
                array_push($stack,$arrayObject);
            }
            else if($str[$i]==$close){
                if(current(current($stack))== $open){                 
                    array_pop($stack);
                }
                else{
                    $currentValue = current($stack);
                    if(!empty($stack) &&  current($currentValue) ==$close){
                        $popValue = array_pop($stack);
                        $key= key($popValue);
                        $replaceStr1[$key]=$escape?("\\".$replaceStr1[$key]):$replace;
                        $replaceStr1[$i]=$escape?("\\".$replaceStr1[$i]):$replace;
                    }else if(empty($stack)){
                        $replaceStr1[$i]=$escape?("\\".$replaceStr1[$i]):$replace;
                    }
                }
            }
        }
        foreach($stack as $obj){
           $replaceStr1[key($obj)] = $escape?("\\".$replaceStr1[key($obj)]):$replace;
        }
        return implode("", $replaceStr1);
    }
}

?>