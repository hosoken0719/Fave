
aa
<!DOCTYPE html>
<html>
  <head>
    <?php echo $this->Html->charset(); ?>
    <title><?php echo $title_for_layout; ?> / Fave</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0", content="width=device-width">
    <!-- <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <?php echo $this->Html->script('swiper.min.js'); ?>
    <!-- <link rel="stylesheet" href="https://cdn.rawgit.com/balzss/luxbar/ae5835e2/build/luxbar.min.css"> -->
    <?php echo $this->Html->css('bootstrap.min.css'); ?>
    <?php echo $this->Html->css('swiper.min.css'); ?>
    <!-- <?php echo $this->Html->css('jquery.fit-sidebar.css'); ?> -->
    <!-- <?php echo $this->Html->css('meanmenu.css'); ?> -->
    <?php echo $this->Html->css('main'); ?>
    
    <!-- <?php echo $this->Html->script('jquery.fit-sidebar'); ?> -->
    <?php if(! isset($user)): 
      echo $this->Html->css('login.css'); 
      endif;
    ?>
    <script src="http://demo.itsolutionstuff.com/plugin/jquery.js"></script>
  <script src="http://demo.itsolutionstuff.com/plugin/croppie.js"></script>
  <link rel="stylesheet" href="http://demo.itsolutionstuff.com/plugin/bootstrap-3.min.css">
  <link rel="stylesheet" href="http://demo.itsolutionstuff.com/plugin/croppie.css">
  </head>
  <body>
  <div class="container">
    <header id="header">
      <nav>
        <div id="header_logo" class="text-left">
          <!-- <h1><a href="/"><img src="http://around-tokyo.jp/img/title.png"></a></h1> -->
          <h1><a href="/"><?php echo $this->Html->image('logo.svg'); ?></a></h1>
        </div>
    <?php if(isset($user)): //ログインしている場合?>
        <div id="header-link" class="text-center header-fluid-box">
          <div class="header-fluid-box-inner">
            <ul class="list-unstyled list-inline">
              <li class="home_button"><a href="/">ホーム</a></li>
              <li><a href="/follows">フォロー</a></li>
              <li><a href="/searches">検索</a></li>
              <li><a href="/regists">投稿</a></li>
              <li><a href="/accounts">アカウント</a></li>
            </ul>
          </div>
        </div>
      </nav>
  <?php endif; ?>
    </header>
      <div class="main">
        <div class="row">
          <?php echo $this->fetch('content'); ?>
        </div>
      </div>
  <?php //else: //ログインしていない場合 ?>
<!--     <div class="main">
      <div class="row">
      <?php //echo $this->fetch('content'); ?>
      </div>
    </div> -->
  <?php //endif; ?>
    </div>
      <script type="text/javascript">
$(function(){
//モーダルウィンドウを出現させるクリックイベント
$("#modal-open").click( function(){

  //キーボード操作などにより、オーバーレイが多重起動するのを防止する
  $( this ).blur() ;  //ボタンからフォーカスを外す
  if( $( "#modal-overlay" )[0] ) return false ;   //新しくモーダルウィンドウを起動しない (防止策1)
  //if($("#modal-overlay")[0]) $("#modal-overlay").remove() ;   //現在のモーダルウィンドウを削除して新しく起動する (防止策2)

  //オーバーレイを出現させる
  $( "body" ).append( '<div id="modal-overlay"></div>' ) ;
  $( "#modal-overlay" ).fadeIn( "fast" ) ;

  //コンテンツをセンタリングする
  centeringModalSyncer() ;

  //コンテンツをフェードインする
  $( "#modal-content" ).fadeIn( "fast" ) ;

  //[#modal-overlay]、または[#modal-close]をクリックしたら…
  $( "#modal-overlay,#modal-close" ).unbind().click( function(){

    //[#modal-content]と[#modal-overlay]をフェードアウトした後に…
    $( "#modal-content,#modal-overlay" ).fadeOut( "fast" , function(){

      //[#modal-overlay]を削除する
      $('#modal-overlay').remove() ;

    } ) ;

  } ) ;

} ) ;


//リサイズされたら、センタリングをする関数[centeringModalSyncer()]を実行する
$( window ).resize( centeringModalSyncer ) ;

  //センタリングを実行する関数
  function centeringModalSyncer() {

    //画面(ウィンドウ)の幅、高さを取得
    var w = $( window ).width() ;
    var h = $( window ).height() ;

    // コンテンツ(#modal-content)の幅、高さを取得
    // jQueryのバージョンによっては、引数[{margin:true}]を指定した時、不具合を起こします。
//    var cw = $( "#modal-content" ).outerWidth( {margin:true} );
//    var ch = $( "#modal-content" ).outerHeight( {margin:true} );
    var cw = $( "#modal-content" ).outerWidth();
    var ch = $( "#modal-content" ).outerHeight();

    //センタリングを実行する
    $( "#modal-content" ).css( {"left": ((w - cw)/2) + "px","top": ((h - ch)/2) + "px"} ) ;

  }

} ) ;

</script>
  <?php if(!empty($map_shops)) : $locate_json = json_encode($map_shops); ?>
  <script type="text/javascript">

      var map;
      var marker_ary = new Array();
      var currentInfoWindow

      function initialize() {

          var latlng = new google.maps.LatLng(<?php echo $map_default_center ?>);        
          var myOptions = {
              zoom: <?php echo $map_zoom ?>,
              center: latlng,
              mapTypeId: google.maps.MapTypeId.ROADMAP,
              <?php if(!empty($gestureHandling)) : echo $gestureHandling; endif;?>
          };
          map = new google.maps.Map(document.getElementById("googlemap"), myOptions);
   
          //イベント登録　地図の表示領域が変更されたらイベントを発生させる
          google.maps.event.addListener(map, 'idle', function(){
              setPointMarker();
          });
      }


    //地図中央の緯度経度を表示
    function getMapcenter() {
        //地図中央の緯度経度を取得
        var mapcenter = map.getCenter();
 
        //テキストフィールドにセット
        document.getElementById("keido").value = mapcenter.lng();
        document.getElementById("ido").value = mapcenter.lat();
    }
 
    // //地図の中央にマーカー
    // function setMarker() {
    //     var mapCenter = map.getCenter();
 
    //     //マーカー削除
    //     MarkerClear();
 
    //     //マーカー表示
    //     MarkerSet(mapCenter.lat(),mapCenter.lng(),'test');
    // }
   
      //マーカー削除
      function MarkerClear() {
          //表示中のマーカーがあれば削除
          if(marker_ary.length > 0){
              //マーカー削除
              for (i = 0; i <  marker_ary.length; i++) {
                  marker_ary[i].setMap();
              }
              //配列削除
              for (i = 0; i <=  marker_ary.length; i++) {
                  marker_ary.shift();
              }
          }
      }

      //マーカー設置
      function MarkerSet(lat,lng,shopname,shoptype,account){
          var marker_num = marker_ary.length;
          var marker_position = new google.maps.LatLng(lat,lng);
          var markerOpts = {
              map: map, 
              position: marker_position,
          };
          marker_ary[marker_num] = new google.maps.Marker(markerOpts);
          
          var text = '<a href=/shops/' + account + '>' + shopname + '<br />' + shoptype + '</a>';

              var infoWndOpts = {
                  content : text
              };
              var infoWnd = new google.maps.InfoWindow(infoWndOpts);


              google.maps.event.addListener(marker_ary[marker_num], "click", function(){
   
                  //情報ウィンドウを開く
                  infoWnd.open(map, marker_ary[marker_num]);
                  //先に開いた情報ウィンドウがあれば、closeする
                  if (currentInfoWindow) {
                      currentInfoWindow.close();
                  }
   
                  //情報ウィンドウを開く
                  infoWnd.open(map, marker_ary[marker_num]);
   
                  //開いた情報ウィンドウを記録しておく
                  currentInfoWindow = infoWnd;
              });
      }

      function setPointMarker(){
        $('.follow .shoplist ul').empty();
          //マーカー削除
          // MarkerClear();
       
          //地図の範囲内を取得
          var bounds = map.getBounds();
          map_ne_lat = bounds.getNorthEast().lat();
          map_sw_lat = bounds.getSouthWest().lat();
          map_ne_lng = bounds.getNorthEast().lng();
          map_sw_lng = bounds.getSouthWest().lng();

      var address = JSON.parse('<?php echo $locate_json; ?>');


      for(let i = 0; i < address.length; i++) {

        //画面表示範囲にあるアドレスのみ表示
        if(
            address[i]['lat'] < map_ne_lat &&
            address[i]['lat'] > map_sw_lat &&
            address[i]['lng'] < map_ne_lng &&
            address[i]['lng'] > map_sw_lng){

            var LocateLats = address[i]['lat'];
            var LocateLng = address[i]['lng'];
            var LocateShopname = address[i]['shopname'];
            var LocateShoptype = address[i]['shoptype'];
            var LocateAccount = address[i]['account'];
            var icon = address[i]['icon'];
            var shopaddress = address[i]['shopaddress'];
            //マーカーをセット
            MarkerSet(LocateLats,LocateLng,LocateShopname,LocateShoptype,LocateAccount);

            //リスト表示
            //リストに対応するマーカー配列キーをセット
            var marker_num = marker_ary.length - 1;

            //HTML出力
            $('.follow .shoplist ul').append("<li><div class='summary'><div class='photo'><img src='"+icon+"' class='img-circle img-responsive'></div><div class='infor'><span class='name'><p><a href='/shops/"+LocateAccount+"'>"+LocateShopname+"</a></p></span><span class='other'><p><p>"+LocateShoptype+"</p><p><a href='javascript:map_click("+marker_num+")'>"+shopaddress+"</p></a></span></div></li>");
        }
      }

  }

  function map_click(i) {
    google.maps.event.trigger(marker_ary[i], "click");
  }


  </script>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC2JktuHRHcWc-sYSmXAPhRi4msD-UE0TM&callback=initialize"  async defer></script> 
<?php endif; ?>

  </body>


  
</html>