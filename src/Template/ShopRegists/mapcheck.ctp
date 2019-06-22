<?php ?>

<div class="contents regist">
	<div class="col-sm-12">
		<article class="content">
			<div class="form">


    <fieldset>
       <legend><?= '地図確認' ?></legend>
      <p class="notice">お店の位置が間違っている場合は、ピンを移動して下さい</p>
      <div id="googlemap">
      </div>

      <?php
        echo $this->Form->create('',['url' => ['controller' => 'ShopRegists', 'action' => 'option2']]);
        echo $this->Form->control('lat', ['id' => 'lat','type'=>'hidden','value'=>$lat]);
        echo $this->Form->control('lng', ['id' => 'lng','type'=>'hidden','value'=>$lng]);
        echo $this->Form->button(__('進む'));
        echo $this->Form->end();

        echo $this->Form->create('',['class'=>'prev' , 'url' => ['action' => 'addresscheck']]);
        echo $this->Form->button('戻る',['class'=>'back']);
        echo $this->Form->end();
      ?>
    </fieldset>
    
</div>
</article>
</div>
</div>

<script type="text/javascript">

      var map;
      var marker_ary = new Array();
      var currentInfoWindow

      function initialize() {
            var latlng = new google.maps.LatLng(<?= $map_default_center ?>);
            setOption(latlng);
      }


      function setOption(latlng){
        var myOptions = {
            zoom: <?php echo $map_zoom ?>,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            <?php if(!empty($gestureHandling)) : echo $gestureHandling . ','; endif;?>
	        zoomControl: true,
          disableDoubleClickZoom: true,
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
 

    		var marker = new google.maps.Marker({
    			position: latlng,
    			draggable: true	// ドラッグ可能にする
    		});

    		marker.setMap(map);

    		google.maps.event.addListener( marker, 'dragend', function(ev){
      		// イベントの引数evの、プロパティ.latLngが緯度経度。
      		document.getElementById('lat').value = ev.latLng.lat();
      		document.getElementById('lng').value = ev.latLng.lng();
    		});


        //イベント登録 地図の表示領域が変更されたらイベントを発生させる
        // google.maps.event.addListener(map, 'idle', function(){
        //     getMapcenter();
        //     setMarker();
        // });
      }



  </script>

  <script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAP_KEY ?>&callback=initialize"  async defer></script> 
