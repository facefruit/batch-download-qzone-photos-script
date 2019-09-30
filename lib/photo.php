<?php

function get_photo_list($header, $uin, $host_uin, $g_tk, $album, $page_num = 100) {
  $photos = array();

  $id = $album->id;
  $url = QZONE_PHOTO_LIST;
  $url = str_replace('${G_TK}', $g_tk, $url);
  $url = str_replace('${UIN}', $uin, $url);
  $url = str_replace('${HOST_UIN}', $host_uin, $url);
  $url = str_replace('${TOPIC_ID}', $id, $url);
  $url = str_replace('${PAGE_NUM}', $page_num, $url);
  $url = str_replace('${TIME}', time() * 1000, $url);

  $num = 0;
  while(true) {
    $tmp_url = str_replace('${PAGE_START}', $num, $url);
    echo 'album link= '.$tmp_url.'
';
    $response = request($tmp_url, $header);
    if ($response === false) {
      echo 'Request photo failure!
';
      break;
    } else {
      $json = json_decode($response, false);
      $data = $json->data;
      $photo_list = $data->photoList;
      if (!isset($photo_list)) {
        break;
      }
      foreach ($photo_list as $photo) {
        $foo = new stdClass();
        $foo->cameratype = $photo->cameratype;
        $foo->name = $photo->name;
        $foo->desc = $photo->desc;
        $foo->uuid = $photo->origin_uuid;
        if ($foo->uuid == false) {
          $foo->uuid = $photo->lloc;
          if ($foo->uuid == false) {
            $foo->uuid = $photo->sloc;
          }
        }
        $foo->id = hash('sha256', $foo->uuid);
        $foo->url = $photo->origin_url;
        if ($foo->url == false) {
          $foo->url = $photo->raw;
          if ($foo->url == false) {
            $foo->url = $photo->url;
          }
        }
        $foo->tag = $photo->tag;

        array_push($photos, $foo);
      }
      if (sizeof($photo_list) == $page_num) {
        $num += $page_num;
        continue;
      } else {
        break;
      }
    }
  }
  return $photos;
}
