<?php
//media_id为微信jssdk接口上传后返回的媒体id
ini_set("error_reporting","E_ALL & ~E_NOTICE");
session_start();

    $media_id = $_POST["serverId"];

    $access_token = getAccessToken();
    $access_token = $access_token['accessToken'];

    $path = "./weixinrecord/";   //保存路径，相对当前文件的路径
   
    if(!is_dir($path)){
        mkdir($path);
    }

    //微信上传下载媒体文件
    // 这里不能加上s，不然保存不了amr文件
    $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$access_token}&media_id={$media_id}";

    $filename = "wxupload_".time().rand(1111,9999).".amr";
    $filename = $path."/".$filename;
    downAndSaveFile($url,$filename);
    $mp3 = str_replace('.amr', '.mp3', $filename);

    if(!empty($mp3)){
        $command = "C:\phpStudy\PHPTutorial\php/ffmpeg-20190101-1dcb5b7-win64-static\bin/ffmpeg -i {$filename} {$mp3} 2>&1";
 
        shell_exec($command);

        $data["path"] = $filename;
        $data["msg"] = "download record audio success!";
        // $data["url"] = $url;

        echo json_encode($data);
    }else{
        echo json_encode('csln');
    } 


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

//HTTP get 请求
function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
}

//根据URL地址，下载文件
function downAndSaveFile($url,$savePath){
    ob_start();
    readfile($url);
    $img  = ob_get_contents();
    ob_end_clean();
    $size = strlen($img);
    $fp = fopen($savePath, 'a');
    fwrite($fp, $img);
    fclose($fp);
}
?>


