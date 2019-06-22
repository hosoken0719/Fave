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
  }



  public function index()
  {

    if (!$this->Auth->user()) {
    // 非ログイン
      $this->viewBuilder()->setLayout('top'); //レイアウトのテンプレートをdefaultからtopに変更
      $this->render('top'); //viewファイルをindexからtopに変更
    } else {
    // ログイン


      $UserTable = TableRegistry::get('users');
      $FollowsTable = TableRegistry::get('follows');

      //非表示ボタンを押した場合
      if($this->request->is('post')){
      //フォローする場合(非表示にするクエリではない場合)


      //非表示にする場合

        // //現在の非表示リストの取り出し
        //   $hide = $UserTable->Find('all', array('conditions' => array('id' => $this->Auth->user('id')),'fields'=>'hide'));
        //   $hide = array_shift($hide); //配列の階層が一つ深いため取り出し

        // //空の場合はカンマなし
        //   if(empty($hide['User']['hide'])){
        //     $insert_hide = $this->request->data['User']['hide'];
        //   }
        // //追記の場合はカンマ区切り
        //   else{
        //     $insert_hide = $hide['User']['hide'] . ','. $this->request->data['User']['hide'];
        //   }

        // //更新の条件を作成
        //   $data = array(
        //     'hide' => "'" . $insert_hide . "'",
        //   );

        // //クエリの発行
        //   $option =  array('id' => $this->Auth->user('id'));      
        //   $this->User->updateALL($data ,$option);
      }
    


    //*自分がフォローしているユーザを取得
      $followData = $FollowsTable->find()->where(['follow' => $this->Auth->user('id')]);


      if($followData->isEmpty()){
        
      } else {
        //*Followerの取得

        $follower = array();
        foreach($followData as $data){
            array_push($follower , $data->follower);
        }

        //*非表示リストの作成
        //*現在の非表示リストの取り出し
        // $hide = $UserTable->find()->where(['id' => $this->Auth->user('id')])->select(['hide'])->first();;
        // $hideArray = explode(',',$hide->hide);  //配列に代入
        // $hideList = array_merge($follower,$hideArray);  //既にフォローしているユーザと、非表示指定のユーザは表示しない
        // $hideList[] = $this->Auth->user('id'); // 自分も表示しない
        // $hideList = array_unique($hideList);  //重複削除
        // $hideList = array_values($hideList);  //配列番号(index)を振り直し
        // $hideList = array_filter($hideList);  //空白の配列を削除

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
            'pref' => 'shops.pref',
            'city' => 'shops.city',
            'ward' => 'shops.ward',
            'introduction' => 'shops.introduction',
            'typename' => 'shoptype.typename',
            'update' => 'follows.created',
        ])
        ->order(['follows.created' => 'DESC']);

        $this->set('title','Fave');
        $this->set(compact('favoriteDatas'));

      }
    }
  }

  //①フォローショップ一覧を作成
  public function followShops(){
    //フォローショップ・フォローユーザ・フォロワーで共通の値を取得
    $user_infor = $this->setCommonValue();

    //表示ユーザのフォローショップを取得
    $FollowShopsIn =  $this->FollowComp->getFollowerShopsByID($user_infor['user_id'])->where(['follower_shop IN' => $user_infor['LoginUserFollowShop']]);
    $FollowShopsNotIn = $this->FollowComp->getFollowerShopsByID($user_infor['user_id'])->where(['NOT' => ['follower_shop IN' => $user_infor['LoginUserFollowShop']]]);

    $this->set(compact('login_user_follow_shop','FollowShopsIn','FollowShopsNotIn'));
  }

  //②フォローユーザを取得
  public function followUsers(){    
    //フォローショップ・フォローユーザ・フォロワーで共通の値を取得
    $user_infor = $this->setCommonValue();

    //ログインユーザと共通のお店をフォローしているユーザを優先表示
    $FollowedUsersIn= $this->FollowComp->getFollowerUserByID($user_infor['user_id'])->where(['follow IN' => $user_infor['LoginUserFollowShop']]);
    $FollowedUsersNotIn= $this->FollowComp->getFollowerUserByID($user_infor['user_id'])->where(['NOT' => ['follow IN' => $user_infor['LoginUserFollowShop']]]);

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
    
    $UserTable = TableRegistry::get('users');
    $TableEntity[('follows')] =  TableRegistry::get('follows');

    $TableEntity[('follow_users')] =  TableRegistry::get('follow_users');

    $this->loadComponent('FollowComp'); // コンポーネントの読み込み

    //ログインIDを取得
    $this->set('login_id',$this->Auth->user('id'));

    // ユーザ名を取得
    $user_name = $this->setUserName();
    
    // ユーザIDを取得
    $user_id = $this->setUserdataByUsername($user_name);
   
    // フォロー中か判定
    $this->setIsFollowtxt($user_id);

    //フォローショップ・フォローユーザ・フォロワー数の取得
    $this->setCountQuery($user_id);

    //ログインユーザがフォローしているショップを取得
    $LoginUserFollow['follower_shop'] = $this->FollowComp->getLoginUserFollowShopArray($this->Auth->user('id'));
    $LoginUserFollow['follower_user'] = $this->getLoginUserFollowUser();
    $LoginUserFollow['follow'] = $this->getLoginUserFollowUser();

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

  //自分のフォローショップを取得
  private function getLoginUserFollowShop(){
    $FollowsTable = TableRegistry::get('follows');
    $login_user_follow_shop = $FollowsTable->Find()
    ->where(['follow' => $this->Auth->user('id')])
    ->combine('id','follower_shop')
    ->toArray();
    return $login_user_follow_shop;
  }

  //自分のフォローユーザーを取得
  private function getLoginUserFollowUser(){
    $FollowUsersTable = TableRegistry::get('follow_users');

    $login_user_follow_user = $FollowUsersTable->Find()
      ->where([
        'follow' => $this->Auth->user('id'),
      ])
    ->combine('id','follower_user')
    ->toArray();
    return $login_user_follow_user;
  }

  //自分のフォロワーユーザーを取得
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

  //ユーザ名を取得
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
      $follow_txt = "フォローする";  
    }else{
      $follow_txt = "フォロー中";  
    }

    $this->set(compact('follow_txt'));
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


}