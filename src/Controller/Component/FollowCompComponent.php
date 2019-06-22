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

    //引数で取得したショップidをフォローしているユーザ一覧を取得
    public function getShopFollowUserDatas($id){
      $FollowsTable = TableRegistry::get('follows');
      $followUserDatas = $FollowsTable->Find()
        ->where([
            'follower_shop' => $id,
            // 'Users.shop' => '0',
            // 'Users.status' => '1'
        ])
        ->join([
            'table' => 'users',
            'alias' => 'Users',
            'type' => 'LEFT',
            'conditions'  => 'follows. follow= Users.id'
        ])
        ->select([
            'user_id' => 'follows.follow',
            'username' => 'Users.username',
            'nickname' => 'Users.nickname',
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

  //ショップの全フォロワーのレーティングを取得
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

  public function getLoginUserFollowShopArray($id){
    $FollowsTable = TableRegistry::get('follow_users');
    $login_user_follow_user = $FollowsTable->Find()
    ->where(['follow' => $id])
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
        ->contain(['shops' => ['shoptypes']])
        ->select([
            'shopname' => 'shops.shopname',
            'user_id' => 'shops.user_id',
            'shop_id' => 'shops.id',
            'pref' => 'shops.pref',
            'city' => 'shops.city',
            'ward' => 'shops.ward',
            'lat' => 'shops.lat',
            'lng' => 'shops.lng',
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

// ▲△▲△▲△ユーザ情報▲△▲△▲△
 


     //新規にフォロー
     // public function follow($id){
     //    $FollowsTable = TableRegistry::get('follows');

     //    $follow = $FollowsTable->newEntity();

     //    $follow->follow = $id['follow'];
     //    $follow->follower = $id['follower'];

     //    $FollowsTable->save($follow);
     // }

  // //フォローされているショップ一覧を取得    
 //    public function getFollowerShopDatas($id){

 //        $FollowsTable = TableRegistry::get('follows');
 //     $followerShopDatas = $FollowsTable->Find()
 //            ->where([
 //                'follower' => $id,
 //                // 'Users.shop' => '1',
 //                'shops.status' => '1'
 //            ])
 //            ->join([
 //               'table' => 'shops',
 //                'alias' => 'shops',
 //                'type' => 'inner',
 //                'conditions' => [
 //                  'AND' => [
 //                    'follows.follow = shops.user_id',
 //                    'shops.status = 1',
 //              ]
 //            ]
 //            ])
 //            ->join([
 //                'table' => 'shoptypes',
 //                'alias' => 'shoptype',
 //                'type' => 'LEFT',
 //                'conditions'  => 'shops.shoptype = shoptype.id'
 //            ])
 //            ->join([
 //                'table' => 'users',
 //                'alias' => 'Users',
 //                'type' => 'LEFT',
 //                'conditions'  => 'follows.follow = Users.id'
 //            ]
 //            )
 //            ->select([
 //                'user_id' => 'follows.follow',
 //                // 'icon' => 'Users.icon',
 //                'username' => 'Users.username',
 //                'nickname' => 'Users.nickname',
 //                'shopname' => 'shops.shopname',
 //                'photo' => 'shops.photo',
 //                'shop_accountname' => 'shops.accountname',
 //                'pref' => 'shops.pref',
 //                'city' => 'shops.city',
 //                'ward' => 'shops.ward',
 //                'introduction' => 'shops.introduction',
 //                'typename' => 'shoptype.typename',
 //                'update' => 'follows.created',
 //            ]);

 //        return $followerShopDatas;
 //    }

  // //フォローされているユーザ一覧を取得    
 //    public function getFollowerUserDatas($id){

 //        $FollowsTable = TableRegistry::get('follows');
 //        $followerUserDatas = $FollowsTable->Find()
 //           ->where([
 //                'follower' => $id,
 //                // 'Users.shop' => '0',
 //                // 'Users.status' => '1'
 //            ])
 //            ->join([
 //                'table' => 'users',
 //                'alias' => 'Users',
 //                'type' => 'LEFT',
 //                'conditions'  => 'follows.follow = Users.id'
 //            ]
 //            )
 //            ->select([
 //                'user_id' => 'follows.follow',
 //                // 'icon' => 'Users.icon',
 //                'username' => 'Users.username',
 //                'nickname' => 'Users.nickname',
 //            ]);

 //        return $followerUserDatas;
 //    }


    // public function getShopDataSummary($id){

    //     $ShopsTable = TableRegistry::get('shops');
    //     $shopData = $ShopsTable->find()
    //     //非公開時の処理を追加すること！！！
    //     ->where([
    //         'shops.id' => $id,
    //         'shops.status' => '1'
    //     ])
    //     ->join([
    //         'table' => 'shoptypes',
    //         'alias' => 'shoptypes',
    //         'type' => 'LEFT',
    //         'conditions'  => 'shops.shoptype = shoptypes.id',
    //     ])
    //     ->select([
    //         'shopname' => 'shopname',
    //         'shop_id' => 'shops.id',
    //         'pref' => 'pref',
    //         'city' => 'city',
    //         'ward' => 'ward',
    //         'town' => 'town',
    //         'typename' => 'shoptypes.typename'
    //     ])
    //     ->first();

    //     return $shopData;
    // }
 //   //フォローしているショップ一覧を取得
  //   public function getFollowShopDatas($id){

  //       $FollowsTable = TableRegistry::get('follows');

        // $followShopDatas = $FollowsTable->Find()
  //           ->where([
  //               'follow' => $id,
  //               // 'Users.shop' => '1',
  //               // 'shops.status' => '1'
  //           ])
  //           ->join([
  //              'table' => 'shops',
  //               'alias' => 'shops',
  //               'type' => 'inner',
  //               'conditions' => [
  //                 'AND' => [
  //                   'follows.follower = shops.user_id',
  //                   'shops.status = 1',
  //             ]
  //           ]
  //           ])
  //           ->join([
  //               'table' => 'shoptypes',
  //               'alias' => 'shoptype',
  //               'type' => 'LEFT',
  //               'conditions'  => 'shops.shoptype = shoptype.id'
  //           ])
  //           // ->join([
  //           //     'table' => 'users',
  //           //     'alias' => 'Users',
  //           //     'type' => 'LEFT',
  //           //     'conditions'  => 'follows.follower = Users.id'
  //           // ]
  //           // )
  //           ->select([
  //               'user_id' => 'follows.follower',
  //               // 'icon' => 'Users.icon',
  //               // 'username' => 'Users.username',
  //               // 'nickname' => 'Users.nickname',
  //               'shop_id' => 'shops.id',
  //               'shopname' => 'shops.shopname',
  //               'photo' => 'shops.photo',
  //               'shop_accountname' => 'shops.accountname',
  //               'pref' => 'shops.pref',
  //               'city' => 'shops.city',
  //               'ward' => 'shops.ward',
  //               'introduction' => 'shops.introduction',
  //               'typename' => 'shoptype.typename',
  //               'update' => 'follows.created',
  //           ]);


  //       return $followShopDatas;
  //    }
}