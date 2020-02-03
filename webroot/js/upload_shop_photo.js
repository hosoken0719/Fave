$(function() {
  var file = null; // 選択されるファイル
  var blob = null; // 画像(BLOBデータ)
  const THUMBNAIL_WIDTH = 1080; // 画像リサイズ後の横の長さの最大値

  // ファイルが選択されたら
  $('input[type=file]').change(function() {

    // ファイルを取得
    file = $(this).prop('files')[0];
    // 選択されたファイルが画像かどうか判定
    if (file.type != 'image/jpeg' && file.type != 'image/png') {
      // 画像でない場合は終了
      file = null;
      blob = null;
      return;
    }
    //画像の方向を取得
    EXIF.getData(file, function(){
      exif = file.exifdata.Orientation;
    });

    // 画像をリサイズ
    var image = new Image();
    var reader = new FileReader();
    reader.onload = function(e) {
      image.onload = function() {

        var width, height;
          // 画像は横のサイズを指定値にあわせる
        var image_aspect,canvas_width,canvas_height;
        image_aspect = (exif == 5 || exif == 6 || exif == 7 || exif == 8) ? image.width / image.height : image.height / image.width;

        canvas_width = THUMBNAIL_WIDTH;
        canvas_height = Math.floor(THUMBNAIL_WIDTH * image_aspect);

        // サムネ描画用canvasのサイズを上で算出した値に変更
        var canvas = document.getElementById( "canvas" );
        var ctx = canvas.getContext('2d');

        // canvasに既に描画されている画像をクリア
        ctx.clearRect(0,0,width,height);

        //canvasの縦横サイズを指定
        var canvas = $('#canvas')
          .attr('width', canvas_width)
          .attr('height', canvas_height);

        //描画サイズの横長の場合に合わせて指定。縦長の場合はswitch内で値を上書き
        draw_width = canvas_width;
        draw_height = canvas_height;

        switch(exif){

          case 2:
            ctx.transform(-1, 0, 0, 1, canvas_width, 0);
          break;

          case 3:
            ctx.transform(-1, 0, 0, -1, canvas_width, canvas_height);
          break;

          case 4:
            ctx.transform(1, 0, 0, -1, 0, canvas_height);
          break;

          case 5:
            ctx.transform(-1, 0, 0, 1, 0, 0);
            ctx.rotate((90 * Math.PI) / 180);
            draw_width = canvas_height;
            draw_height = canvas_width;
          break;

          case 6:
            ctx.transform(1, 0, 0, 1, canvas_width, 0);
            ctx.rotate((90 * Math.PI) / 180);
            draw_width = canvas_height;
            draw_height = canvas_width;
          break;

          case 7:
            ctx.transform(-1, 0, 0, 1, canvas_width, canvas_height);
            ctx.rotate((-90 * Math.PI) / 180);
            draw_width = canvas_height;
            draw_height = canvas_width;
          break;

          case 8:
            ctx.transform(1, 0, 0, 1, 0, canvas_height);
            ctx.rotate((-90 * Math.PI) / 180);
            draw_width = canvas_height;
            draw_height = canvas_width;
          break;

          default:
          break;

        }


        // canvasにサムネイルを描画（表示サイズはCSSで設定）
        ctx.drawImage(image,0,0,draw_width,draw_height);

        // canvasからbase64画像データを取得
        var base64 = canvas.get(0).toDataURL('image/jpeg');
        // base64からBlobデータを作成
        var barr, bin, i, len;
        bin = atob(base64.split('base64,')[1]);
        len = bin.length;
        barr = new Uint8Array(len);
        i = 0;
        while (i < len) {
          barr[i] = bin.charCodeAt(i);
          i++;
        }

        blob = new Blob([barr], {type: 'image/jpeg'});
        $('.js-modal').fadeIn();
      }
      image.src = e.target.result;
    }
    reader.readAsDataURL(file);
  });
  // アップロード開始ボタンがクリックされたら
  $('#upload').click(function(){

    // ファイルが指定されていなければ何も起こらない
    if(!file || !blob) {
      return;
    }
    var csrf = $('input[name=_csrfToken]').val();
    var name, fd = new FormData();
    fd.append('id',id);
    fd.append('file', blob); // ファイルを添付する
    $.ajax({
      url: "/ajax/shopphoto",
      type: "POST",
        data: fd,
      processData: false,
      contentType: false,
      beforeSend: function(xhr){
        xhr.setRequestHeader('X-CSRF-Token', csrf)
      },
      success: function (data) {
        $('.js-modal').fadeOut();
        window.location.href = "https://fave-jp.info/shops/"+id+"/photo";
      },
      error: function (data, status, errors){
        $('.js-modal').fadeOut();
      }
    });

  });

  $('.js-modal-close').on('click',function(){
    $('.js-modal').fadeOut();
    return false;
  });
});

//参考
//https://qiita.com/komakomako/items/8efd4184f6d7cf1363f2
//https://nori-life.com/javascript-canvas-exif-adjust/
