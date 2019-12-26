<?php

namespace App\Traits;

trait PDParser
{
    public static function parseProfileData($profileLink)
    {
        $response = self::_getResponse($profileLink);
        if(!$response)
            return false;

        $response = preg_replace("/\r|\n|\t/", '', $response);
        $result = [];
        // preg_match( '/<span class=\'desc lighter\'>.*?<\/span>/s' , $response , $lastActive);
        // $result = array('lastActive' => preg_replace('/(<span class=\'desc lighter\'>)(.*?)(<\/span>)/', '$2', $lastActive[0]));
        
        //preg_match( '/<ul class=\'ipsList_inline ipsPos_left\'>.*?<\/span>/s' , $response , $lastActive);
        
        return $result;
    }

    public static function parseProfileAvatar($profileLink)
    {
        $response = self::_getResponse($profileLink);
        if(!$response)
            return false;

        preg_match( '/<img class="ipsUserPhoto" id=\'profile_photo\' .*?>/' , $response , $links );
        if(isset($links[0]) && !preg_match('/style_images\/Prodota_Images/', $links[0]))
            return preg_replace('/(.*?src=\')(.*?)(\'.*)/', '$2', $links[0]);
        else
            return false;
    }

    private static function _getResponse($profileLink)
    {
        if(empty($profileLink))
            return false;

        $profileLink = str_replace('http://', 'https://', $profileLink);
        $ch = curl_init($profileLink);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Opera/10.00 (Windows NT 5.1; U; ru) Presto/2.2.0');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, '1');
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result = curl_exec($ch);
        curl_close($ch);
        if($httpCode == 404) {
            return false;
        }
        return $result;
    }
}