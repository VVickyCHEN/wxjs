##注意事项

##github项目地址
*  https://github.com/VVickyCHEN/wxjs
  
##  总体思路
  提议：最好整体看一下微信公众平台的技术文档。
  
*  1、先注册微信公众号账号
  配置（1）IP白名单
      （2）js接口安全域名
*  2、写好前端页面后把签名验证先通过，估计很多人会卡在这一步，所以我总结了自己的几个例子，希望对大家有用。
  	（1）url的获取
  		官方文档说这个地址需要在前端实时获取，使用encodeURIComponent加密传给后台。
  		
  		前台传输的url地址需要js来url加密
  		var url = encodeURIComponent(location.href.split('#')[0]);
  		
  		后台获取地址
  		$url = urldecode($_POST['url']);
  	（2）官方文档说了access_token和ticket都需要设置缓存保存起来，并且要设置过期时间
  	（3）前端引用的地址https://res.wx.qq.com/open/js/jweixin-1.0.0.js如果加了https后台获取token和ticket的地址也要跟着加上https。
  
* 3、配置好签名的话，后面的下载语音就简单了。大家可以直接看我代码，这里列出需要注意的地方。
  
  //微信上传下载媒体文件的地址，这里不能加上s，不然保存不了amr文件
  $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$access_token}&media_id={$media_id}";
  
  
##  ffmpeg下载地址
*  https://ffmpeg.zeranoe.com/builds/
  
  楼主配置好了ffmpeg环境变量，在cmd命令行中是可以使用ffmpeg将amr转换成mp3，但是在php使用shell_exec函数的时候却不行，想来想去，就想到了如下方法：
  
  如果使用shell_exec函数来用ffmpeg命令不生效，可以使用ffmpeg文件下的bin目录代替ffmpeg
  $command = "C:\phpStudy\PHPTutorial\php/ffmpeg-20190101-1dcb5b7-win64-static\bin/ffmpeg -i {$filename} {$mp3} 2>&1";
  shell_exec($command);