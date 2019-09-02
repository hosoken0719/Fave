<?php
namespace App\Model\Entity;


use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property int $role
 * @property \Cake\I18n\FrozenTime $last_login_at
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Shop[] $shops
 * @property \App\Model\Entity\Tag[] $tags
 */
class Shop extends Entity
{
    public function getPhotoShopThumbnail($id){
	    $dir = PHOTO_UPLOADDIR . '/shop_photos/' . $id . '/thumbnail/';
	    $photo_list = glob($dir . '*');
	    $photoShop = "";
	    if(!empty($photo_list)){
	        $photoShop_fullpath = max($photo_list); //最新写真のみ抽出
	        $photoShop_array = explode('/',$photoShop_fullpath); //サーバパスの取得となるため、最後のファイル名だけを取得
	        $photoShop = "https://fave-jp.info/img/shop_photos/" . $id . "/thumbnail/" . end($photoShop_array);
	    }else{
	        $photoShop = "https://fave-jp.info/img/no_image.png";
	    }
	    return $photoShop;
    }
}