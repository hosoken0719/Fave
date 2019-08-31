<?php ?>

<script type="text/javascript">

      var map;
      var marker_ary = new Array();
      var currentInfoWindow

      function initialize() {

        <?php if($map_default_center<>null): ?>

        var latlng = new google.maps.LatLng(<?= $map_default_center; ?>);
        setOption(latlng);

        <?php else: ?>

        // 現在地を取得
        navigator.geolocation.getCurrentPosition(
          // 取得成功した場合
          function(position) {
            var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
            setOption(latlng);
          },
          function(error) {
            var latlng = new google.maps.LatLng(35.681236,139.767125);
            setOption(latlng);
          }
        );

        <?php endif; ?>
      }


      function setOption(latlng){
        var myOptions = {
            zoom: <?php echo $map_zoom ?>,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            <?php if(!empty($gestureHandling)) : echo $gestureHandling . ','; endif;?>
          zoomControl: true,
          zoomControlOptions: {
            style: google.maps.ZoomControlStyle.LARGE,
            position: google.maps.ControlPosition.TOP_RIGHT
          },
          mapTypeControl: false,
          streetViewControl: true,
            streetViewControlOptions: {
            position: google.maps.ControlPosition.TOP_RIGHT
          },
        };

        map = new google.maps.Map(document.getElementById("googlemap"), myOptions);

        //イベント登録 地図の表示領域が変更されたらイベントを発生させる
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
      function MarkerSet(lat,lng,shopname,shoptype,shop_id){
          var marker_num = marker_ary.length;
          var marker_position = new google.maps.LatLng(lat,lng);
          var markerOpts = {
              map: map, 
              position: marker_position,
          };
          marker_ary[marker_num] = new google.maps.Marker(markerOpts);

          //MAP内の吹き出し表示
          var text = '<a href=/shops/' + shop_id + '>' + shopname + '<br />' + shoptype + '</a>';

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
            var LocateId = address[i]['shop_id'];
            var icon = address[i]['icon'];
            var shopaddress = address[i]['shopaddress'];
            var photo = address[i]['photo'];
            //マーカーをセット
            MarkerSet(LocateLats,LocateLng,LocateShopname,LocateShoptype,LocateId);

            //リスト表示
            //リストに対応するマーカー配列キーをセット
            var marker_num = marker_ary.length - 1;
            //右のリストHTML出力
            // $('.follow .shoplist ul').append("<li><div class='summary'><div class='icon'><img src='"+photo+"' class='img-circle img-responsive'></div><div class='infor'><span class='name'><p><a href='/shops/"+LocateId+"'>"+LocateShopname+"</a></p></span><span class='other'><p>"+LocateShoptype+"</p><p><a href='javascript:map_click("+marker_num+")'>"+shopaddress+"</a></p></span></div></li>");
            $('.follow .shoplist ul').append("<li><div class='summary'><div class='infor'><span class='name'><p><a href='/shops/"+LocateId+"'>"+LocateShopname+"</a></p></span><span class='other'><p>"+LocateShoptype+"</p><p><a href='javascript:map_click("+marker_num+")'>"+shopaddress+"</a></p></span></div></li><hr />");
        }
      }

  }

  function map_click(i) {
    google.maps.event.trigger(marker_ary[i], "click");
  }


  </script>

  <script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAP_KEY ?>&callback=initialize"  async defer></script> 