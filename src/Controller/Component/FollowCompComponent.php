<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;


class FollowCompComponent extends Component {

//▽▼▽▼▽▼ショップ情報▽▼▽▼▽▼
  //ショップをフォローしているかの判定
     public function isShopFollow($id) {
        $FollowUsersTable = TableRegistry::get('follows');

        $result = $FollowUsersTable->find()
        ->where([
            'follow' => $id['follow'],
            'follower_shop' => $id['follower_shop']])
        ->select([
          'rating' => 'rating',
          'review' => 'review'])
        ->first();

        if(is_null($result)){ //未フォロー
          $data['rating'] = 0;
          $data['review'] = null;
        }else{ //フォロー中
          $data['rating'] = $result->rating;
          $data['review'] = $result->review;
        }

        return $data;
     }

    //お店の写真を取得
    public function getFollowShopPhotos($id){
        //ショップの写真パスを取得
        $dir = SHOPPHOTO_UPLOADDIR . '/photo_shop/' . $id . '/thumbnail/';
        $photo_list = glob($dir . '*.png');
        if(!empty($photo_list)){
            $photoShop_fullpath = max($photo_list); //最新写真のみ抽出
            $photoShop_array = explode('/',$photoShop_fullpath); //サーバパスの取得となるため、最後のファイル名だけを取得
            $photoShop = "https://fave-jp.info/img/photo_shop/" . $id . "/thumbnail/" . end($photoShop_array);
        }else{
            $photoShop = "https://fave-jp.info/img/no_image.svg";
        }
        return $photoShop;
    }

    //仮引数で取得したショップidをフォローしているユーザ一覧を取得
    public function getShopFollowUserDatas($shop_id){
      $FollowsTable = TableRegistry::get('follows');
      $followUserDatas = $FollowsTable->Find()
        ->where([
            'follower_shop' => $shop_id,
            // 'Users.shop' => '0',
            // 'Users.status' => '1'
        ])
        ->contain(['users' => ['sexs']])
        ->select([
            'user_id' => 'follows.follow',
            'username' => 'users.username',
            'nickname' => 'users.nickname',
            'sex_id' => 'users.sex',
            'sex_typename' => 'sexs.typename',
            'address' => 'users.address',
            // 'created' => 'follow_users.created'
        ]);
      return $followUserDatas;
    }

    // ショップの全フォロワー数を取得
    public function getShopFollowUserCount($id){
      $FollowsTable = TableRegistry::get('follows');
      $followUserDatas = $FollowsTable->Find()
          ->where([
              'follower_shop' => $id,
          ])
          ->count();
        return $followUserDatas;
      }

  //ショップの全フォロワーのレーティングの平均を取得
     public function getShopRatingByShopId($id) {
        $FollowUsersTable = TableRegistry::get('follows');

        $sql = $FollowUsersTable->find();
        $result = $sql
        ->where([
            'follower_shop' => $id])
        ->select([
            'rating_avg' => $sql->func()->avg('rating')])
        ->first();
        return $result;
     }

  // ショップのフォローのうち、自分がフォローしているユーザ数を取得
    public function getShopCountMyFollowUser($id){
      $FollowsTable = TableRegistry::get('follows');

      $result = $FollowsTable->Find()
      ->where([
        'follow IN' => $id['FollowedId'],
        'follower_shop' => $id['follower_shop']
      ])
      ->count();
      return $result;
    }

  // ショップのフォローのうち、自分がフォローしているユーザのレーティングを取得
    public function getShopRatingMyFollowUser($id){
      $FollowsTable = TableRegistry::get('follows');

      $sql = $FollowsTable->Find();
      $result = $sql->where([
        'follow IN' => $id['FollowedId'],
        'follower_shop' => $id['follower_shop']
      ])
      ->select([
        'rating_avg' => $sql->func()->avg('rating')])
      ->first();
      return $result;
    }


// ▲△▲△▲△ショップ情報▲△▲△▲△
// ▽▼▽▼▽▼ユーザ情報▽▼▽▼▽▼

  //ユーザをフォローしているか判定
     public function isUserFollow($id) {
        $FollowUsersTable = TableRegistry::get('follow_users');

        $result = $FollowUsersTable->find()
        ->where([
            'follow' => $id['follow'],
            'follower_user' => $id['follower_user']])
        ->first();

        if($result == null){ //未フォロー
            return 0;
        }else{ //フォロー済み
            return 1;
        }

     }

  //仮引数で取得したユーザがフォローしているお店を取得
  public function getLoginUserFollowShopArray($user_id){
    $FollowsTable = TableRegistry::get('follows');
    $login_user_follow_shop = $FollowsTable->Find()
    ->where(['follow' => $user_id])
    ->combine('id','follower_shop')
    ->toArray();
    return $login_user_follow_shop;
  }

  //仮引数で取得したユーザがフォローしているユーザを取得
  public function getLoginUserFollowUserArray($user_id){
    $FollowUsersTable = TableRegistry::get('follow_users');
    $login_user_follow_user = $FollowUsersTable->Find()
      ->where([
        'follow' => $user_id,
      ])
    ->combine('id','follower_user')
    ->toArray();
    return $login_user_follow_user;
  }

    //①フォローしているショップ
    public function getFollowerShopsByID($id){

        $FollowsTable = TableRegistry::get('follows');

        $query = $FollowsTable->Find()
        ->where([
            'follow' => $id,
        ])
        ->contain(['shops' => ['shoptypes','prefectures']])
        ->select([
            'shopname' => 'shops.shopname',
            'user_id' => 'shops.user_id',
            'shop_id' => 'shops.id',
            'pref' => 'prefectures.name',
            'addres' => 'shops.address',
            'lat' => 'shops.lat',
            'lng' => 'shops.lng',
            'thumbnail' => 'shops.thumbnail',
            'typename' => 'shoptypes.typename',
            'rating' => 'follows.rating',
            'created' => 'follows.created'
        ]);
        return $query;
    }


    // ②フォローしているユーザ一（フォロー）
    public function getFollowerUserByID($id){
        $FollowUsersTable = TableRegistry::get('follow_users');

        $query = $FollowUsersTable->Find()
        ->where([
            'follow' => $id,
        ])
        ->join([
            'table' => 'users',
            'type' => 'LEFT',
            'conditions'  => 'follow_users.follower_user = users.id' //getFollowerUserByIDと同じjoinだが、conditionsが異なるため、containは使えない
        ])
        ->join([
            'table' => 'sexs',
            'type' => 'LEFT',
            'conditions'  => 'users.sex = sexs.id'
        ])
        ->select([
            'user_id' => 'users.id',
            'username' => 'users.username',
            'nickname' => 'users.nickname',
            'sex_id' => 'users.sex',
            'sex_typename' => 'sexs.typename',
            'address' => 'users.address',
            'created' => 'follow_users.created'
        ]);
        
        return $query;
    }

    // ③フォローされているユーザ一（フォロワー）
    public function getFollowUserByID($id){

        $FollowUsersTable = TableRegistry::get('follow_users');

        $query = $FollowUsersTable->Find()
        ->where([
            'follower_user' => $id,
        ])
        ->join([
            'table' => 'users',
            'type' => 'LEFT',
            'conditions'  => 'follow_users.follow = users.id'
        ])
        ->join([
            'table' => 'sexs',
            'type' => 'LEFT',
            'conditions'  => 'users.sex = sexs.id'
        ])
        ->select([
            'user_id' => 'users.id',
            'username' => 'users.username',
            'nickname' => 'users.nickname',
            'sex_id' => 'users.sex',
            'sex_typename' => 'sexs.typename',
            'address' => 'users.address',
            'created' => 'follow_users.created'
        ]);
        return $query;
    }
  }
