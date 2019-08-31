<?php
namespace App\Controller\Manages;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Manages Controller
 *
 *
 * @method \App\Model\Entity\Manage[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ShopConfirmsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */

    public $components = ['ShopRegist','Businesshour'];

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('ShopComp'); // コンポーネントの読み込み
    }

    public function index()
    {

        $ShopTable = TableRegistry::getTableLocator()->get('shops');

        $shopDatas = $ShopTable->find()
        ->where(['confirm IS' => null])
        ->select([
            'shopname' => 'shops.shopname',
            'shop_id' => 'shops.id',
            ]
        );
        $this->set(compact('shopDatas'));

    }

    public function check(){

        $shop_id = $this->request->getParam('shop_id');
        $shop_datas = $this->ShopComp->getShopData($shop_id);

        $shopname = $shop_datas->shopname;
        $branch = $shop_datas->branch;
        $kana = $shop_datas->shop_kana;
        $shoptype = $shop_datas->shoptype;
        $shoptype2 = $shop_datas->shoptype2;
        $pref = $shop_datas->pref;
        $address = $shop_datas->address;
        $building = $shop_datas->building;
        $phone_number = $shop_datas->phone_number;
        $business_hour_detail = $shop_datas->business_hour_detail;
        $homepage = $shop_datas->homepage;
        $instagram = $shop_datas->instagram;
        $parking = $shop_datas->parking;
        $thumbnail = $shop_datas->thumbnail;
        $instagram = $shop_datas->instagram;

        $this->set(compact('shop_id','shopname','branch','kana','shoptype','shoptype2','pref','address','building','phone_number','business_hour_detail','parking','homepage','instagram','thumbnail','instagram'));

        $ShoptypeTable = TableRegistry::getTableLocator()->get('shoptypes');
        $this->set('typename',$ShoptypeTable->find('list'));

        //*県一覧の取得
        $PrefTable = TableRegistry::getTableLocator()->get('prefectures');
        $this->set('pref_list',$PrefTable->find('list')->where(['enable' => 1]));

        $ShopPhotoTable = TableRegistry::getTableLocator()->get('shop_photos');
        $shop_photos = $ShopPhotoTable->find()->where(['shop_id'=>$shop_id]);
        $this->set('shop_photos',$shop_photos);




        //inputとselectboxのtemplate
        $template = [
            'label' => '<div{{attrs}}>{{text}}</div>',
            'input' => '<div class="inputbox"><input type="{{type}}" name="{{name}}"{{attrs}}></div>',
            'select' => "<div class='selectbox'><select name='{{name}}'{{attrs}}>{{content}}</select></div>",
        ];

    $this->set(compact('template'));

    }


    public function register(){

        // $ShopTable = TableRegistry::getTableLocator()->get('shops');

        $registDatas = $this->request->getdata();
        $ShopTable = $this->loadModel('Shops');
        list($basic_information,$shop_photos) = $this->getPostData($registDatas);
        $shopData = $ShopTable->get($basic_information['shop_id']);
        // $registDatas = $this->readSessionValue();
        //Postデータを営業時間・営業日・それ以外に分別して配列に格納

        // //不足情報を付加
        if($basic_information['confirm'] ==="0"){
            $basic_information['confirm'] = null;
        }

        //ショップデータの保存
            // $shopEntity = $ShopTable->newEntity();
            $shopEntity = $ShopTable->patchEntity($shopData, $basic_information);


            //ショップ情報の登録が成功した場合、続けて営業時間の登録
            if($ShopTable->save($shopEntity)){
                $ShopPhotosTable = TableRegistry::getTableLocator()->get('shop_photos');
                //既に登録があれば一旦削除
                $ShopPhotosTable->deleteAll(['shop_id' => $basic_information['shop_id']]);
                //新規登録
                foreach ($shop_photos as $key => $url) {
                    $shop_photo=[];
                    $shop_photo['url'] = $url;
                    $shop_photo['shop_id'] = $basic_information['shop_id'];
                    $shop_photo['provider'] = 'instagram';
                    $phooPhotoEntity = $ShopPhotosTable->newEntity();
                    $phooPhotoEntity = $ShopPhotosTable->patchEntity($phooPhotoEntity, $shop_photo);
                    $ShopPhotosTable->save($phooPhotoEntity);
                }
            }

            return $this->redirect(['action' => 'index']);

    }


    public function writeSessionValue($key,$value){
        $postData = $this->request->getdata();
    }

    //セッション変数を取得
    private function readSessionValue($name=null){
        if(!empty($name)){
            $value = 'shop.'.$name;
        }else{
            $value = 'shop';
        }
        return $this->session->read($value);
    }


    //Postデータを営業時間・営業日・それ以外に分別して配列に格納
    private function getPostData($registDatas){
        $basic_information = [];
        $shop_photos = [];
        foreach ($registDatas as $key => $value) {
            if(strpos($key,'shop_photo') === false) {
                $basic_information[$key] = $value;
            //thumbnail以外の写真
            }else{
                $shop_photos[$key] = $value;
            }
        }

        return [$basic_information,$shop_photos];

    }
}
