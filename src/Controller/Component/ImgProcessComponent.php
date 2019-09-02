<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * ImgProcess component
 */
class ImgProcessComponent extends Component
{
    function initialize(array $config) {
        $this->controller = $this->_registry->getController();
    }

    //validation適用のため、rquestに img_name, img_ext, img_sizeを詰める
    function save($request) {
        $img = $request->data['img'];
        $ext =  pathinfo($img['name'], PATHINFO_EXTENSION);
        $name = md5(uniqid(rand(), 1)).'.'.$ext;
        $request->data['img_ext'] = $ext;
        $request->data['img_size'] = $img['size'];
        $request->data['img_name'] = $name;
    }

    //オリジナルとサムネイル作成
    function generate_どこで使用しているfunctionか確認($img,$original_file) {

        // //何故かエラーが出るので@を付けて出力をしない
        $exif = @exif_read_data($original_file, 0, true);
        if(isset($exif['IFD0']['Orientation'])) {
            switch ($exif['IFD0']['Orientation']){
                case 5: //時計回りに90°回転した後、左右反転
                case 6: //時計回りに90°回転
                case 7: //反時計回りに90°回転した後、左右反転
                case 8: //反時計回りに90°回転
                    list($original_height ,$original_width) = getimagesize($original_file);
               break;
            default:
            list($original_width, $original_height) = getimagesize($original_file);
            }
        }else{
            list($original_width, $original_height) = getimagesize($original_file);
        }

        if($img['type'] === 'image/jpeg') $original_image = imagecreatefromjpeg($original_file);
        if($img['type'] === 'image/png') $original_image = imagecreatefrompng($original_file);
        if($img['type'] === 'image/gif') $original_image = imagecreatefromgif($original_file);

        if($original_width > 800){
            $thumb_width = 800;
            $thumb_height = round( $original_height * $thumb_width / $original_width );
        }else{
            $thumb_width = $original_width;
            $thumb_height = $original_height;
        }

        $thumb_image = imagecreatetruecolor($thumb_width, $thumb_height);

        imagecopyresampled($thumb_image, $original_image, 0, 0, 0, 0,
            $thumb_width, $thumb_height,
            $original_width, $original_height);
        // imagecopyresized($thumb_image, $original_image, 0, 0, 0, 0,
        //     $thumb_width, $thumb_height,
        //     $original_width, $original_height);
        if($img['type'] === 'image/jpeg') imagejpeg($thumb_image, PHOTO_UPLOADDIR.'/'."thumbnail.jpg", 60);
        if($img['type'] === 'image/png') imagepng($thumb_image, PHOTO_UPLOADDIR.'/'."thumbnail.png");
        if($img['type'] === 'image/gif') imagegif($thumb_image, PHOTO_UPLOADDIR.'/'."thumbnail.gif");





        // move_uploaded_file($tmp_name, 'img/'.$post->img_name);
        // $original_file = 'img/'.$post->img_name;;
        // list($original_width, $original_height) = getimagesize($original_file);
        // $thumb_width = 300;
        // $thumb_height = round( $original_height * $thumb_width / $original_width );
        // if($post->img_ext === 'jpg') $original_image = imagecreatefromjpeg($original_file);
        // if($post->img_ext === 'png') $original_image = imagecreatefrompng($original_file);
        // if($post->img_ext === 'gif') $original_image = imagecreatefromgif($original_file);
        // $thumb_image = imagecreatetruecolor($thumb_width, $thumb_height);
        // imagecopyresized($thumb_image, $original_image, 0, 0, 0, 0,
        //     $thumb_width, $thumb_height,
        //     $original_width, $original_height);
        // if($post->img_ext === 'jpg') imagejpeg($thumb_image, 'img/mini/'.$post->img_name);
        // if($post->img_ext === 'png') imagepng($thumb_image, 'img/mini/'.$post->img_name);
        // if($post->img_ext === 'gif') imagegif($thumb_image, 'img/mini/'.$post->img_name);
    }
}