<?php

function get_anti_csrf_token($cookie) {
  $e = 5381;
  $n = explode('; ', $cookie);
//  var_dump($n);
  $t = '';
  foreach ($n as $k) {
    if (/*strpos($k, 'skey') === 0 || */strpos($k, 'p_skey') === 0 || strpos($k, 'wxvip_access_token') === 0) {
      $t = substr($k, strpos($k, '=') + 1);
//      echo 'key = '.$t.'
//';
      break;
    }
  }
  for ($i = 0, $o = strlen($t); $i < $o; ++$i) {
//    echo $i.': '.$t{$i}.': '.ord($t{$i}).'
//';
    $e = (($e << 5) + ord($t{$i}) + $e) & 2147483647;
//    echo $e.'
//';
  }
  return $e & 2147483647;
}

function get_http_header($header = array()) {
  array_push($header, 'accept: text/javascript, application/javascript, application/ecmascript, application/x-ecmascript, */*; q=0.01');
  //array_push($header, 'accept-encoding: gzip, deflate, br');
  array_push($header, 'accept-language: zh-CN,zh;q=0.9');
  array_push($header, 'user-agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36');
  array_push($header, 'cache-control: max-age=0');
  return $header;
}

/**
 * 检测文件所在路径文件夹是否存在，
 * 不存在则创建文件夹并保存数据。
 */
function check_path_and_save($path, $content) {
    //获取目录
    $dir = substr($path, 0, strrpos($path, '/'));
    if (!file_exists($dir)) {
        mkdir($dir, 755, true);
    }
    return file_put_contents($path, $content);
} 


/**
 * 检测图片是否已存在，
 * 如果不存在则保存图片，
 * 如果存在且为同一个文件则保存图片，
 * 如果存在且不为同一个文件则保存为备份原文件。
 */
function check_image_and_save($path, $content) {
    if (file_exists($path)) {
        //图片已存在
        if (filesize($path) != strlen($content)) {
            //视为不同图片
            $bak_path = $path.'.'.strtoupper(uniqid()).'.bak';
            return file_put_contents($bak_path, $content);
        }
        return true;
    } else {
        //图片不存在
        return check_path_and_save($path, $content);
    }
}

/**
 * 网络请求
 */
function download($url, $time = 5) {
    if (empty($url)) {
        //日志记录
        return false;
    }
    $i = 0;
    do {
        $content = file_get_contents($url);
        if ($content !== false) {
            return $content;
        }
        echo 'Request failure! Time = '.$i.'; Url = '.$url.'
';
        $i++;
    } while ($i < $time);
    return false;
}



function image_header() {
  $header = array();
  array_push($header, 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8');
//Accept-Encoding: gzip, deflate
  array_push($header, 'Accept-Language: zh-CN,zh;q=0.9');
  array_push($header, 'Connection: keep-alive');
  array_push($header, 'Host: b145.photo.store.qq.com');
  array_push($header, 'Upgrade-Insecure-Requests: 1');
  array_push($header, 'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36');
  return $header;
}
