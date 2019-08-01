<?php ?>

<div class="contents regist">
    <article class="contain-white">
    <div class="contain_inner">
			<div class="form confirm">


    <fieldset>
      <legend><?= '登録内容確認' ?></legend>
        <dl>
          <dt>ショップ名</dt><dd><?= h($shopname) ?></dd>
        </dl>
        <dl>
          <dt>支店名</dt><dd><?= h($branch) ?></dd>
        </dl>
        <dl>
          <dt>フリガナ</dt><dd><?= h($kana) ?></dd>
        </dl>
          <dl>
            <dt>ショップタイプ</dt><dd><?= h($shoptype) ?></dd>
          </dl>
          <dl>
            <dt>ショップタイプ2</dt><dd><?= h($shoptype2) ?></dd>
          </dl>
          <dl>
            <dt>住所</dt><dd><?= h($address_full. " " . $building) ?></dd>
          </dl>
          <dl>
            <dt>営業時間</dt>
            <dd>
              <?php
                for($i = 0 ; $i <= 6 ; $i++) {
                  echo $week_ja[$i] . "曜日 : ";
                  $length = count($bussiness_hours[$i]);
                  $no = 0;
                  foreach ($bussiness_hours[$i] as $day) {
                    echo $day['open'] . " - " . $day['close'];
                    if(++$no !== $length){
                      echo  " , ";
                    }
                  }
                  if($no === 0){
                    echo "定休日";
                  }
                  echo "<br />";
                }
              ?>
            </dd>
          </dl>
          <dl>
            <dt>営業時間備考</dt><dd><?= nl2br(h($business_hour_detail)) ?></dd>
          </dl>
          <dl>
            <dt>駐車場</dt><dd><?= h($parking) ?></dd>
          </dl>
          <dl>
            <dt>ホームページ</dt><dd><?= $this->Html->link($homepage, $homepage,array('target'=>'_blank')); ?></dd>
          </dl>
        </fieldset>
    <fieldset>
       <legend><?= '地図確認' ?></legend>
      <p class="notice">お店の位置が間違っている場合は、ピンを移動して下さい</p>
      <div id="googlemap">
      </div>

      <?php
        echo $this->Form->create('',['url' => ['controller' => 'ShopRegists', 'action' => 'register']]);
        echo $this->Form->control('lat', ['id' => 'lat','type'=>'hidden','value'=>$lat]);
        echo $this->Form->control('lng', ['id' => 'lng','type'=>'hidden','value'=>$lng]);
        echo $this->Form->button(__('登録'));
        echo $this->Form->end();

        echo $this->Form->create('',['class'=>'prev' , 'url' => ['action' => 'index']]);
        echo $this->Form->button('戻る',['class'=>'back']);
        echo $this->Form->end();
      ?>
    </fieldset>
    
</div>
</article>
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
(function ($) {
    var escapes = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        },
        escapeRegexp = /[&<>"']/g,
        hasEscapeRegexp = new RegExp(escapeRegexp.source),
        unescapes = {
            '&amp;': '&',
            '&lt;': '<',
            '&gt;': '>',
            '&quot;': '"',
            '&#39;': "'"
        },
        unescapeRegexp = /&(?:amp|lt|gt|quot|#39);/g,
        hasUnescapeRegexp = new RegExp(unescapeRegexp.source),
        stripRegExp = /<(?:.|\n)*?>/mg,
        hasStripRegexp = new RegExp(stripRegExp.source),
        nl2brRegexp = /([^>\r\n]?)(\r\n|\n\r|\r|\n)/g,
        hasNl2brRegexp = new RegExp(nl2brRegexp.source),
        br2nlRegexp = /<br\s*\/?>/mg,
        hasBr2nlRegexp = new RegExp(br2nlRegexp.source);

    $.fn.textWithLF = function (text) {
        var type = typeof text;

        return (type == 'undefined')
            ? htmlToText(this.html())
            : this.html((type == 'function')
                ? function (index, oldHtml) {
                    var result = text.call(this, index, htmlToText(oldHtml));
                    return (typeof result == 'undefined')
                        ? result
                        : textToHtml(result);
                } : textToHtml(text));
    };

    function textToHtml(text) {
        return nl2br(escape(toString(text)));
    }

    function htmlToText(html) {
        return unescape(strip(br2nl(html)));
    }

    function escape(string) {
        return replace(string, escapeRegexp, hasEscapeRegexp, function (match) {
            return escapes[match];
        });
    }

    function unescape(string) {
        return replace(string, unescapeRegexp, hasUnescapeRegexp, function (match) {
            return unescapes[match];
        });
    }

    function strip(html) {
        return replace(html, stripRegExp, hasStripRegexp, '');
    }

    function nl2br(string) {
        return replace(string, nl2brRegexp, hasNl2brRegexp, '$1<br>');
    }

    function br2nl(string) {
        return replace(string, br2nlRegexp, hasBr2nlRegexp, '\n');
    }

    function replace(string, regexp, hasRegexp, replacement) {
        return (string && hasRegexp.test(string))
            ? string.replace(regexp, replacement)
            : string;
    }

    function toString(value) {
        if (value == null) return '';
        if (typeof value == 'string') return value;
        if (Array.isArray(value)) return value.map(toString) + '';
        var result = value + '';
        return '0' == result && 1 / value == -(1 / 0) ? '-0' : result;
    }
})(jQuery);
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
