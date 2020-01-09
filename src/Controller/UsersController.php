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

    $template = [
      'label' => '<div{{attrs}}>{{text}}</div>',
      'input' => '<div class="inputbox"><input type="{{type}}" name="{{name}}"{{attrs}}></div>',
      'select' => "<div class='selectbox'><select name='{{name}}'{{attrs}}>{{content}}</select></div>",
    ];

    $this->set(compact('template'));

  }

  //①フォローショップ一覧を作成
  public function followShops(){
      $this->loadComponent('ShopComp'); // コンポーネントの読み込み
    //フォローショップ・フォローユーザ・フォロワーで共通の値を取得
    $user_infor = $this->setCommonValue();

    // $FollowShopsIn =  $this->FollowComp->getFollowerShopsByID($user_infor['user_id'])->order(['follows.rating'=>'DESC'])->select(['shop_id'=>'follower_shop']);
    $FollowShopsIn = $this->FollowComp->getFollowerShopsByID($user_infor['user_id'])->order(['follows.rating'=>'DESC'])->select(['shop_id'=>'follower_shop']);
    $FollowsTable = TableRegistry::getTableLocator()->get('follows');

    //サブクエリ1（自分がフォローしているショップをフォローしている全ユーザの平均rating）
    $sub_query_avg_followed = $FollowsTable->find()->from(['sub_follows'=>'follows'])->where(['sub_follows.follower_shop = follows.follower_shop']);
    $sub_query_avg_followed = $this->ShopComp->getAvgFollowed($sub_query_avg_followed,$user_infor['LoginUserFollowerUser'])->group(['sub_follows.follower_shop']);

    // //サブクエリ2（自分がフォローしているショップをフォローしている全ユーザ数）
    $sub_query_cnt_followed = $FollowsTable->find()->from(['sub_follows'=>'follows'])->where(['sub_follows.follower_shop = follows.follower_shop']);
    $sub_query_cnt_followed = $this->ShopComp->getCntFollowed($sub_query_cnt_followed,$user_infor['LoginUserFollowerUser'])->group(['sub_follows.follower_shop']);

    $FollowShopsIn = $FollowShopsIn->select(['avg_followed' => $sub_query_avg_followed,'cnt_followed' => $sub_query_cnt_followed]);

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