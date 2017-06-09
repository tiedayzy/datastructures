<?php
/**
 * 截取utf8格式中文字符
 *
 *中文UTF8编码，每个字占3个字节，但是也有一些特殊的字符，会占用一字节或者双字节
 *1个字节：00——7F
 *2个字节：C080——DFBF
 *3个字节：E08080——EFBFBF
 *4个字节：F0808080——F7BFBFBF
 *
 *可以根据第一个字节的范围确定该字符所占的字节数：
 *$ord = ord($str{$i});
 *$ord < 192 单字节和控制字符
 *192 <= $ord < 224 双字节
 *224<= $ord < 240  三字节
 *中文并没有四个字节的字符
 *
 *
 */
function substrCn_UTF8($str,$start,$length) {

    if( $start < 0 ){
        $start += cnStrlen_utf8( $str );

        if( $start < 0 ){
            $start = 0;
        }
    }

    $slen = strlen( $str );

    if( $len < 0 ){
        $len += $slen - $start;

        if($len < 0){
            $len = 0;
        }
    }

    $i = 0;
    $count = 0;

    /* 获取开始位置 */
    while( $i < $slen && $count < $start){
        $ord = ord( $str{$i} );

        if( $ord < 127){
            $i ++;
        }else if( $ord < 224 ){
            $i += 2;
        }else{
            $i += 3;
        }
        $count ++;
    }

    $count  = 0;
    $substr = '';

    /* 截取$len个字符 */
    while( $i < $slen && $count < $len){
        $ord = ord( $str{$i} );

        if( $ord < 127){
            $substr .= $str{$i};
            $i ++;
        }else if( $ord < 224 ){
            $substr .= $str{$i} . $str{$i+1};
            $i += 2;
        }else{
            $substr .= $str{$i} . $str{$i+1} . $str{$i+2};
            $i += 3;
        }
        $count ++;
    }

    return $substr;
 }

function cnSubstr( $str, $start, $len, $encode = 'gbk' ){
    if( extension_loaded("mbstring") ){
        //echo "use mbstring";
        //return mb_substr( $str, $start, $len, $encode );
    }

    $enc = strtolower( $encode );
    switch($enc){
        case 'gbk':
        case 'gb2312':
            return cnSubstr_gbk($str, $start, $len);
            break;
        case 'utf-8':
        case 'utf8':
            return cnSubstr_utf8($str, $start, $len);
            break;
        default:
            //do some warning or trigger error;
}

function cnStrlen_utf8( $str ){
    $len  = 0;
    $i    = 0;
    $slen = strlen( $str );

    while( $i < $slen ){
        $ord = ord( $str{$i} );
        if( $ord < 127){
            $i ++;
        }else if( $ord < 224 ){
            $i += 2;
        }else{
            $i += 3;
        }
        $len ++;
    }

    return $len;
}

function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
        if(false === $slice) {
            $slice = '';
        }
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice.'...' : $slice;
}


