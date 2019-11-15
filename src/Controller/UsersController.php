<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

  public function initialize()
  {
      parent::initialize();
      $this->loadComponent('FollowComp'); // コンポーネントの読み込み
      $this->Auth->allow();
  }



  public function index()
  {

    // if (!$this->Auth->user()) {
    // // 非ログイン
    //   $this->viewBuilder()->setLayout('top'); //レイアウトのテンプレートをdefaultからtopに変更
    //     //inputとselectboxのtemplate

    //   $this->render('top'); //viewファイルをindexからtopに変更

    // } else {
    // // ログイン


      $UserTable = TableRegistry::get('users');
      $FollowsTable = TableRegistry::get('follows');

      $ShoptypeTable = TableRegistry::get('shoptypes');
      $this->set('typename',$ShoptypeTable->find('list'));
      //*自分がフォローしているユーザを取得
      $followData = $FollowsTable->find()->where(['follow' => $this->Auth->user('id')]);


      if($followData->isEmpty()){
      } else {
        //*Followerの取得

        $follower = array();
        foreach($followData as $data){
            array_push($follower , $data->follower);
        }


    //*$followerがfollowしているユーザ($favorite)
        $favoriteDatas = $FollowsTable->Find()
        // ->where([
        //     'follow IN' => $follower ,
        //     'NOT' => [
        //       'follower IN' => $follower,  //自分がフォローしているユーザは対象外
        //     ]
        // ])
        ->where([
            'follow IN' => $follower ,
            'NOT' => [
              'follow IN' => $follower,  //自分がフォローしているユーザは対象外
            ]
        ])
        ->join([
          'table' => 'shops',
          'alias' => 'shops',
          'type' => 'inner',
          'conditions' => [
            'AND' => [
              'follows.follow = shops.user_id',
              'shops.status = 1',
            ]
          ]
        ])
        ->join([
          'table' => 'shoptypes',
          'alias' => 'shoptype',
          'type' => 'LEFT',
          'conditions'  => 'shops.shoptype = shoptype.id'
        ])
        ->select([
            'user_id' => 'follows.follow',
            'shop_id' => 'shops.id',
            'shopname' => 'shops.shopname',
            'shop_accountname' => 'shops.accountname',
            'address' => 'shops.address',
            'introduction' => 'shops.introduction',
            'typename' => 'shoptype.typename',
            'update' => 'follows.created',
        ])
        ->order(['follows.created' => 'DESC']);

              //*ショップタイプの取得
        $ShoptypeTable = TableRegistry::get('shoptypes');
        $this->set('typename',$ShoptypeTable->find('list'));

        $template = [
          'label' => '<div{{attrs}}>{{text}}</div>',
          'input' => '<div class="inputbox"><input type="{{type}}" name="{{name}}"{{attrs}}></div>',
          'select' => "<div class='selectbox'><select name='{{name}}'{{attrs}}>{{content}}</select></div>",
        ];
        $this->set(compact('template'));

        $this->set('title','Fave');
        $this->set('header_link','home');
        $this->set(compact('favoriteDatas'));

      } //inputとselectboxのtemplate
  $template = [
    'label' => '<div{{attrs}}>{{text}}</div>',
    'input' => '<div class="inputbox"><input type="{{type}}" name="{{name}}"{{attrs}}></div>',
    'select' => "<div class='selectbox'><select name='{{name}}'{{attrs}}>{{content}}</select></div>",
  ];

  $this->set(compact('template'));
    // }
  }

  //①フォローショップ一覧を作成
  public function followShops(){
      $this->loadComponent('ShopComp'); // コンポーネントの読み込み
    //フォローショップ・フォローユーザ・フォロワーで共通の値を取得
    $user_infor = $this->setCommonValue();

    $FollowShopsIn =  $this->FollowComp->getFollowerShopsByID($user_infor['user_id'])->order(['follows.rating'=>'DESC']);
    
    //表示ユーザのフォローショップを取得
    // $FollowShopsIn =  $this->FollowComp->getFollowerShopsByID($user_infor['user_id'])->where(['follower_shop IN' => $user_infor['LoginUserFollowShop']])->order(['follows.rating'=>'DESC']); //ログインユーザのお気に入り登録ショップ
    // $FollowShopsIn = $this->ShopComp->rating_avg($FollowShopsIn,$user_infor['LoginUserFollowerUser']);
    // $FollowShopsNotIn = $this->FollowComp->getFollowerShopsByID($user_infor['user_id'])->where(['NOT' => ['follower_shop IN' => $user_infor['LoginUserFollowShop']]]); //ログインユーザのお気に入り未登録ショップ
    // $FollowShopsNotIn = $this->ShopComp->rating_avg($FollowShopsNotIn,$user_infor['LoginUserFollowerUser']);
    $this->set('LoginUserFollowerUser',$user_infor['LoginUserFollowerUser']);
    $this->set(compact('login_user_follow_shop','FollowShopsIn','FollowShopsNotIn'));
  }

  //②フォローユーザを取得
  public function followUsers(){
    //フォローショップ・フォローユーザ・フォロワーで共通の値を取得
    $user_infor = $this->setCommonValue();

    // //ログインユーザと共通のお店をフォローしているユーザを優先表示
    if(!empty($user_infor['LoginUserFollowShop'])){
      $FollowedUsersIn= $this->FollowComp->getFollowerUserByID($user_infor['user_id'])->where(['follower_user IN' => $user_infor['LoginUserFollowShop']]);
      $FollowedUsersNotIn= $this->FollowComp->getFollowerUserByID($user_infor['user_id'])->where(['NOT' => ['follower_user IN' => $user_infor['LoginUserFollowShop']]]);
    }else{
      $FollowedUsersIn = [];
      $FollowedUsersNotIn= $this->FollowComp->getFollowerUserByID($user_infor['user_id']);
    }
    $this->set(compact('FollowedUsersIn','FollowedUsersNotIn'));
  }

  //③フォロワーを取得
  public function followers(){
    //フォローショップ・フォローユーザ・フォロワーで共通の値を取得
    $user_infor = $this->setCommonValue();

    //ログインユーザと共通のお店をフォローしているユーザを優先表示
    $FollowerUsersIn = $this->FollowComp->getFollowUserByID($user_infor['user_id'])->where(['follow IN' => $user_infor['LoginUserFollowShop']]);
    $FollowerUsersNotIn = $this->FollowComp->getFollowUserByID($user_infor['user_id'])->where(['NOT' => ['follow IN' => $user_infor['LoginUserFollowShop']]]);

    $this->set(compact('FollowerUsersIn','FollowerUsersNotIn'));
  }




  //①フォローショップ・②フォローユーザ・③フォロワーで共通の値を取得
  private function setCommonValue(){

    // $UserTable = TableRegistry::get('users');
    // $TableEntity[('follows')] =  TableRegistry::get('follows');
    // $TableEntity[('follow_users')] =  TableRegistry::get('follow_users');

    $this->loadComponent('FollowComp'); // コンポーネントの読み込み

    //ログインIDを取得
    $this->set('login_id',$this->Auth->user('id'));

    // 表示しているユーザ名を取得
    $user_name = $this->setUserName();
    $this->set(compact('user_name'));

    // 表示しているユーザのIDを取得
    $user_id = $this->setUserdataByUsername($user_name);

    // 表示しているユーザをフォロー中か判定
    $this->setIsFollowtxt($user_id);

    // 表示しているユーザのフォローショップ・フォローユーザ・フォロワー数の取得
    $this->setCountQuery($user_id);

    // アバターのセット
    $this->setAvatar($user_id);

    //ログインユーザがフォローしているショップを取得
    $LoginUserFollow['follower_shop'] = $this->FollowComp->getLoginUserFollowShopArray($this->Auth->user('id'));

    //ログインユーザがフォローしているユーザーを取得
    $LoginUserFollow['follower_user'] = $this->FollowComp->getLoginUserFollowUserArray($this->Auth->user('id'));

    $this->set(compact('LoginUserFollow'));


    return ['user_id'=>$user_id,'LoginUserFollowShop'=>$LoginUserFollow['follower_shop'],'LoginUserFollowerUser'=>$LoginUserFollow['follower_user']];
  }


  // private function getLoginUserFollowShop(){
  //   $myFollowShops = $this->FollowComp->getFollowShopsByID($this->Auth->user('id'));

  //   //表示ユーザと自分のフォローショップが一致しているか判定し、別々の配列に代入して、表示する際に一致したショップを上位にする。
  //   $login_user_follow_shop = [];
  //   foreach ($myFollowShops as $myFollowShop) {
  //     $login_user_follow_shop[] = $myFollowShop->shop_id;
  //   }

  //   return $login_user_follow_shop;
  // }

  //自分がフォローしているショップを取得
  private function getLoginUserFollowShop(){
    $FollowsTable = TableRegistry::get('follows');
    $login_user_follow_shop = $FollowsTable->Find()
    ->where(['follow' => $this->Auth->user('id')])
    ->combine('id','follower_shop')
    ->toArray();
    return $login_user_follow_shop;
  }


  //自分をフォローしている（フォロワー）ユーザーを取得
  private function getLoginUserFollowerUser(){
    $FollowUsersTable = TableRegistry::get('follow_users');

    $login_user_follow_user = $FollowUsersTable->Find()
      ->where([
        'follower_user' => $this->Auth->user('id'),
      ])
      ->combine('id','follow')
      ->toArray();
    return $login_user_follow_user;
  }

  // URLのパラメータからユーザ名を取得
  private function setUserName(){
    $user_name = $this->request->getParam("user_name");
    $this->set('user_name',$user_name);
    return $user_name;
  }

  //ユーザ名からユーザを取得
  private function setUserdataByUsername($user_name){
    $UserTable = TableRegistry::get('users');
    $this->loadComponent('FollowComp'); // コンポーネントの読み込み

    //対象ユーザを、URLの引数（usernameから、user_idを取得）
    $userDatas = $UserTable->find()
    ->where([
      'username' => $user_name,
    ])
    ->contain(['sexs'])
    ->select([
      'user_id' => 'users.id',
      'nickname' => 'users.nickname',
      'sex_typename' => 'sexs.typename',
      'address' => 'users.address',
    ])
    ->first();

    $this->set('user_id',$userDatas->user_id);
    $this->set('nickname',$userDatas->nickname);
    $this->set('sex',$userDatas->sex_typename);
    $this->set('address',$userDatas->address);

    return $userDatas->user_id;
  }

  //フォロー済みかを判定し、Viewのフォローボタン内のテキストを作成
  private function setIsFollowtxt($user_id){
    $isFollowed['follow'] = $this->Auth->user('id');
    $isFollowed['follower_user'] = $user_id;

    if($this->FollowComp->isUserFollow($isFollowed) === 0){
      $follow_status['text'] = "フォローする";
      $follow_status['tag'] = "follow";
    }else{
      $follow_status['text'] = "フォロー中";
      $follow_status['tag'] = "followed";
    }

    $this->set(compact('follow_status'));
  }

   //フォローショップ・フォローユーザ・フォロワー数の取得
  private function setCountQuery($user_id){
    $query = $this->FollowComp->getFollowerShopsByID($user_id);
    $count['follow_shops'] = $query->count();

    $query = $this->FollowComp->getFollowerUserByID($user_id);
    $count['follow_users'] = $query->count();

    $query = $this->FollowComp->getFollowUserByID($user_id);
    $count['followers'] = $query->count();

    $this->set(compact('count'));
  }

  //アバターのセット
  private function setAvatar($user_id){
     if(file_exists(PHOTO_UPLOADDIR.'/user_photos/'.$user_id.'.png')){
             $avatar = '/img/user_photos/thumbnail/max_'.$user_id.'.png';
      }else{
          $UsersTable = TableRegistry::get('Users');
          $query = $UsersTable->find()->contain(['social_accounts'])->where(['Users.id' => $user_id])->select(['avatars' => 'social_accounts.avatar'])->first();
          if(!Empty($query->avatars)){
              $avatar = $query->avatars;
          }else{
              $avatar = 'avatar.png';
          }
      }

    $this->set(compact('avatar'));
  }


}