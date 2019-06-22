<?php ?>




<div class="contents regist">
	<div class="col-sm-12">
		<article class="content">
			<div class="form">

<!-- 
<div class="containera">
  <div class="panel panel-default">
    <div class="panel-body">


      <div class="row">
        <div class="col-md-4 text-center">
        <div id="upload-demo" style="width:150px"></div>
        </div>
        <div class="col-md-4" style="padding-top:100px;">
        <strong>Select Image:</strong>
        <br/>
        <button class="vanilla-rotate">Rotate</button>
        <input type="file" id="upload">
        <br/>
        <button class="btn btn-success upload-result">Upload Image</button>
        </div>
        <div id="upload-demo-i"></div>
      </div>


    </div>
  </div>
</div> -->



        <fieldset>
          <legend><?= "" ?></legend>

          <?php
            echo $this->Form->create('',['class'=>'next','url' => ['controller' => 'ShopRegists', 'action' => 'checkshopname']]);
            
            echo "<div class='shopname'><div class='require'></div></div>";
            echo $this->Form->control('shopname', ['label' => 'ショップ名（必須)','name'=>'shopname','value'=>$shopname]);

            echo $this->Form->control('branch', ['label' => '支店名']);

            //フリガナ（必須項目のため、空欄の場合はcontrollerからフラグを受け取る）
            echo "<div class='kana'><div class='require'></div></div>";
            echo $this->Form->control('kana', ['label' => 'フリガナ（必須)','name'=>'kana','value'=>$kana]);
            
            echo $this->Form->button(__('進む'));
            echo $this->Form->end();
          ?>
          
        </fieldset>
      </div>
    </article>
  </div>
</div>
  


<script type="text/javascript">
    
$(".next").submit(function(){
  var chkForm = true;
  if ($("input[name='shopname']").val() == ''){
      $('.shopname .require').text("*必須項目です"); 
      chkForm = false;
  }else{
      $('.shopname .require').text(''); 
  }

  if ($("input[name='kana']").val() == ''){
      $('.kana .require').text("*必須項目です"); 
      chkForm = false;
  }else{
      $('.kana .require').text(''); 
  }

  var str = $("input[name='kana']").val();
  if(!str.match(/^[ァ-ヶー　]*$/)){  
    $('.kana .require').text("*全角カタカタで入力下さい"); 
    chkForm = false;
  }

  return chkForm;
});


// croppieの設定

$uploadCrop = $('#upload-demo').croppie({
    enableExif: true,
    viewport: {
        width: 200,
        height: 150,
        type: 'square'
    },
    boundary: {
        width: 250,
        height: 200
    },
    showZoom: false,
    enableOrientation: true
});


  $('.vanilla-rotate').on('click', function(ev) {
      $uploadCrop.croppie('rotate', '-90');
  });


$('#upload').on('change', function () { 
  var reader = new FileReader();
    reader.onload = function (e) {
      $uploadCrop.croppie('bind', {
        url: e.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
      
    }
    reader.readAsDataURL(this.files[0]);
});
 var csrf = $('input[name=_csrfToken]').val();

$('.upload-result').on('click', function (ev) {
  $uploadCrop.croppie('result', {
    type: 'canvas',
    size: { width: 800, height: 600 }
  }).then(function (resp) {
    $.ajax({
      url: "/ajax/shopimage",
      type: "POST",
      data: {"image":resp},
      beforeSend: function(xhr){
        xhr.setRequestHeader('X-CSRF-Token', csrf);
      },
      success: function (data) {
      },
      error: function (data, status, errors){
      }
    });
  });
});


// croppieの設定

// cropperの設定

  $(function(){
        // 初期設定
  var options =
  {
    aspectRatio: 16 / 9,
    viewMode:1,
    rotatable:true,
    crop: function(e) {
          cropData = $('#select-image').cropper("getData");
          $("#upload-image-x").val(Math.floor(cropData.x));
          $("#upload-image-y").val(Math.floor(cropData.y));
          $("#upload-image-w").val(Math.floor(cropData.width));
          $("#upload-image-h").val(Math.floor(cropData.height));
    },
    zoomable:false,
    minCropBoxWidth:162,
    minCropBoxHeight:162,
    rotate:90,
  }

        // 初期設定をセットする
  $('#select-image').cropper(options);

  $("#profile-image").change(function(){
                // ファイル選択変更時に、選択した画像をCropperに設定する
    $('#select-image').cropper('replace', URL.createObjectURL(this.files[0]));
  });
});

  // cropperの設定

</script>