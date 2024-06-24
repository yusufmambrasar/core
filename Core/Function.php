<?php
defined('BASE') or header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');

if (! function_exists('rootPath')) 
{
    function rootPath() {
        return BASE;
    }
}

if (! function_exists('currentUrl')) 
{
    function currentUrl($return=FALSE)
    {
        $output = sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            $_SERVER['REQUEST_URI'],
        );
        if($return)
        {
            return $output;
        }
        else
        {
            echo $output;
        }
    }
}

if (! function_exists('siteUrl')) 
{
    function siteUrl($Uri='', $return=FALSE)
    {
        $Path = str_replace($_SERVER['QUERY_STRING'],'',$_SERVER['REQUEST_URI']);
        $output = sprintf(
            "%s://%s%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            $Path,
            $Uri,
        );
        if($return)
        {
            return $output;
        }
        else
        {
            echo $output;
        }
    }
}

if (! function_exists('baseUrl')) 
{
    function baseUrl($Uri = '', $return=FALSE)
    {
        $output = BASE . '/' . $Uri;
        if($return)
        {
            return $output;
        }
        else
        {
            echo $output;
        }
    }
}

if (! function_exists('isActiveUrl')) 
{
    function isActiveUrl($uri,$return=FALSE)
    {
        $CurrentUrl = CurrentURL(TRUE);
        $ActiveUrl = SiteUrl($uri,TRUE);
        $output = ''; 
        if(strpos($ActiveUrl,$CurrentUrl)!==FALSE)
        {
            $output = 'active';
        }
        if($return)
        {
            return $output;
        }
        else
        {
            echo $output;
        }
    }
}


if (! function_exists('redirect')) 
{
    function redirect($uri='')
    {
        $url = SiteUrl($uri,TRUE);
        header('location: ' . $url);
        exit();
    }
}

if (! function_exists('randomString')) 
{
    function randomString($len=6,$type='Alphanumeric') {
        if($type=='alpha')
        {
            $characters = 'abcdefghijklmnopqrstuvwxyz';
        }
        elseif($type=='Alpha')
        {
            $characters = 'abcdefghijklmnopqrstuvwxyz';
        }
        elseif($type=='ALPHA')
        {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        elseif($type=='AlphA')
        {
            $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        elseif($type=='alphanumeric')
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        }
        elseif($type=='Alphanumeric')
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        }
        elseif($type=='ALPHAnumeric')
        {
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        else
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $RandomString = '';
    
        for ($i = 0; $i < $len; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $RandomString .= $characters[$index];
        }

        if($type=='Alpha' OR $type=='Alphanumeric')
        {
            $RandomString = ucfirst($RandomString);
        }
    
        return $RandomString;
    }
}


if (! function_exists('ParseQuestionFillAnswer')) 
{
    function ParseQuestionFillAnswer($format, $arr) 
    { 
        $arrs = explode("\n",$arr);
        $format = str_replace('[...]','<span class="text-primary">%s</span>',$format);
        call_user_func_array('printf', array_merge((array)$format, $arrs)); 
    } 
}

if (! function_exists('ParseQuestionFillBlank')) 
{
    function ParseQuestionFillBlank($format, $arr, $answers) 
    { 
        $answers = explode("\n",$answers);
        $arrs = explode("\n",$arr);
        foreach($arrs as $k => $v)
        {
            if(isset($answers[$k]))
            {
                $answer = $answers[$k];
            }
            else
            {
                $answer = '';
            }
            $arrs[$k] = '<input type="text" class="form-control form-control-inline" placeholder="..." name="answer_fill'.$k.'" value="'.$answer.'" />';
        }
        $format = str_replace('[...]','%s',$format);
        call_user_func_array('printf', array_merge((array)$format, $arrs)); 
    } 
}

if (! function_exists('TimeAgoIndonesia')) 
{
    function TimeAgoIndonesia($timestamp){  
        $time_ago = strtotime($timestamp);  
        $current_time = time();  
        $time_difference = $current_time - $time_ago;  
        $seconds = $time_difference;  
        $minutes      = round($seconds / 60 );        // value 60 is seconds  
        $hours        = round($seconds / 3600);       //value 3600 is 60 minutes * 60 sec  
        $days         = round($seconds / 86400);      //86400 = 24 * 60 * 60;  
        $weeks        = round($seconds / 604800);     // 7*24*60*60;  
        $months       = round($seconds / 2629440);    //((365+365+365+365+366)/5/12)*24*60*60  
        $years        = round($seconds / 31553280);   //(365+365+365+365+366)/5 * 24 * 60 * 60  
        if($seconds <= 60) 
        {  
            return "Baru saja";  
        } 
        else if($minutes <=60) 
        {  
            if($minutes==1)
            {  
                return "Satu menit lalu";  
            }
            else
            {  
                return "$minutes menit lalu";  
            }  
        } 
        else if($hours <=24) 
        {  
            if($hours==1) 
            {  
                return "Satu jam lalu";  
            } 
                else 
            {  
                return "$hours jam lalu";  
            }  
        }
        else if($days <= 7) 
        {  
            if($days==1) {  
                return "Kemarin";  
            }
            else 
            {  
                return "$days hari lalu";  
            }  
        }
        else if($weeks <= 4.3) //4.3 == 52/12
        {  
            if($weeks==1)
            {  
                return "Seminggu lalu";  
            }
            else 
            {  
                return "$weeks minggu lalu";  
            }  
        } 
        else if($months <=12)
        {  
            if($months==1)
            {  
                return "Sebulan lalu";  
            }
            else
            {  
                return "$months bulan lalu";  
            }  
        }
        else
        {  
            if($years==1)
            {  
                return "Setahun lalu";  
            }
            else 
            {  
                return "$years tahun lalu";  
            }  
        }  
    } 
}

if (!function_exists('http_response_code')) {
    function http_response_code($code = NULL) {

        if ($code !== NULL) {

            switch ($code) {
                case 100: $text = 'Continue'; break;
                case 101: $text = 'Switching Protocols'; break;
                case 200: $text = 'OK'; break;
                case 201: $text = 'Created'; break;
                case 202: $text = 'Accepted'; break;
                case 203: $text = 'Non-Authoritative Information'; break;
                case 204: $text = 'No Content'; break;
                case 205: $text = 'Reset Content'; break;
                case 206: $text = 'Partial Content'; break;
                case 300: $text = 'Multiple Choices'; break;
                case 301: $text = 'Moved Permanently'; break;
                case 302: $text = 'Moved Temporarily'; break;
                case 303: $text = 'See Other'; break;
                case 304: $text = 'Not Modified'; break;
                case 305: $text = 'Use Proxy'; break;
                case 400: $text = 'Bad Request'; break;
                case 401: $text = 'Unauthorized'; break;
                case 402: $text = 'Payment Required'; break;
                case 403: $text = 'Forbidden'; break;
                case 404: $text = 'Not Found'; break;
                case 405: $text = 'Method Not Allowed'; break;
                case 406: $text = 'Not Acceptable'; break;
                case 407: $text = 'Proxy Authentication Required'; break;
                case 408: $text = 'Request Time-out'; break;
                case 409: $text = 'Conflict'; break;
                case 410: $text = 'Gone'; break;
                case 411: $text = 'Length Required'; break;
                case 412: $text = 'Precondition Failed'; break;
                case 413: $text = 'Request Entity Too Large'; break;
                case 414: $text = 'Request-URI Too Large'; break;
                case 415: $text = 'Unsupported Media Type'; break;
                case 500: $text = 'Internal Server Error'; break;
                case 501: $text = 'Not Implemented'; break;
                case 502: $text = 'Bad Gateway'; break;
                case 503: $text = 'Service Unavailable'; break;
                case 504: $text = 'Gateway Time-out'; break;
                case 505: $text = 'HTTP Version not supported'; break;
                default:
                    exit('Unknown http status code "' . htmlentities($code) . '"');
                break;
            }

            $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

            header($protocol . ' ' . $code . ' ' . $text);

            $GLOBALS['http_response_code'] = $code;

        } else {

            $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);

        }

        return $code;

    }
}

if (!function_exists('numberToRoman')) {

    function numberToRoman($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

}

if (!function_exists('error_400')) {

    function error_400()
    {
        header("HTTP/1.1 400 Bad Request");
        echo "<h1>400 Bad Request</h1>";
        echo "<p>Your request is invalid.</p>";
        exit();
    }

}

if (!function_exists('dateConverter')) {

    function dateConverter($date, $format='Y-m-d') {
        return date($format,strtotime($date));
    }
    

}