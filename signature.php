<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE");
session_start();

//获取accesstoken
    function getAccessToken()
    {
        $token = $_SESSION['token'];
        if(!isset($token)){
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".'wx5ead899dc1f6ea25'."&secret=".'b7587083785486e4d2484b93bc0a0aef';
            // 微信返回的信息
            $returnData = json_decode(curlHttp($url));
            $resData['accessToken'] = $returnData->access_token;
            $resData['expiresIn'] = $returnData->expires_in;
            $resData['time'] = date("Y-m-d H:i",time());
            
            $res = $resData;
            $_SESSION['token'] = $res;
            return $res;
        }else{
            return $token;
        }
    }
 
    function curlHttp($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($curl, CURLOPT_TIMEOUT, 500 );
        curl_setopt($curl, CURLOPT_URL, $url );
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,false);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }
    function getJsApiTicket($accessToken) {

        $ticket = $_SESSION['ticket'];

        if(!isset($ticket)){
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$accessToken&&type=jsapi";
            // 微信返回的信息
            $returnData = json_decode(curlHttp($url));
     
            $resData['ticket'] = $returnData->ticket;
            $resData['expiresIn'] = $returnData ->expires_in;
            $resData['time'] = date("Y-m-d H:i",time());
            $resData['errcode'] = $returnData->errcode;
            
            $_SESSION['ticket'] = $resData;

            return $resData;
        }else{
            return $ticket;
        }
 
        
    }

    // 创建随机字符串
    function createNoncestr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for($i = 0; $i < $length; $i ++) {
            $str .= substr ( $chars, mt_rand ( 0, strlen ( $chars ) - 1 ), 1 );
        }
        return $str;
    }
    // 获取token
    $token = getAccessToken();
   
    // 获取ticket
    $ticketList = getJsApiTicket($token['accessToken']);
    $ticket = $ticketList['ticket'];

    // 该url为调用jssdk接口的url
    $url = $_POST['url'];
    $url = urldecode($url);

    $timestamp = time();

    // 生成随机字符串
    $nonceStr=createNoncestr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序 j -> n -> t -> u
    $string = "jsapi_ticket={$ticket}&noncestr={$nonceStr}&timestamp={$timestamp}&url={$url}";
    $signature = sha1($string);
    $signPackage = array (
        "appId" => 'wx5ead899dc1f6ea25',
        "nonceStr" => $nonceStr,
        "timestamp" => $timestamp,
        "url" => $url,
        "signature" => $signature,
        "rawString" => $string,
        "ticket" => $ticket,
        "token" => $token['accessToken']
    );

    // 返回数据给前端
    echo json_encode($signPackage);


?>