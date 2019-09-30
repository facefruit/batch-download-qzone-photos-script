<?php

function get_album_list($header, $uin, $host_uin, $g_tk, $page_num = 30) {
  $albums = array();

  $url = QZONE_ALBUM_LIST;
  $url = str_replace('${G_TK}', $g_tk, $url);
  $url = str_replace('${HOST_UIN}', $host_uin, $url);
  $url = str_replace('${UIN}', $uin, $url);
  $url = str_replace('${TIME}', time() * 1000, $url);
  $url = str_replace('${PAGE_NUM}', $page_num, $url);
  $i = 0;
  while (true) {
    $tmp_url = str_replace('${PAGE_START}', $i, $url);
    echo 'link= '.$tmp_url."\n";
    $response = request($tmp_url, $header);
    if ($response === false) {
      echo 'Request failure!
';
      break;
    } else {
      $json = json_decode($response, false);
      $data = $json->data;
      $album_list = $data->albumList;
      foreach($album_list as $album) {
        $foo = new stdClass();
        $foo->id = $album->id;
        $foo->name = $album->name;
        $foo->desc = $album->desc;
        array_push($albums, $foo);
      }
      if (sizeof($album_list) == $page_num) {
        $i += $page_num;
        continue;
      } else {
        break;
      }
    }
  }
  return $albums;
}
