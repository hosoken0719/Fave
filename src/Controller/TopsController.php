<?php
// おそらく使ってないファイル!!

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
class TopsController extends AppController
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
      $followData = $FollowsTable->find()->where(['follower' => $this->Auth->user('id')]);
      echo $this->Auth->user('id');
var_dump($followData);

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
        ->where([
            'follower IN' => $follower , 
            // 'NOT' => [
            //   'follower IN' => $hideList,  //自分がフォローしているユーザは対象外
            // ]
        ])
        ->join([
          'table' => 'shops',
          'alias' => 'shops',
          'type' => 'inner',
          'conditions' => [
            'AND' => [
              'follows.follower = shops.id',
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
            'user_id' => 'follows.follower',
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



}