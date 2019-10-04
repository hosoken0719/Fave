	if(type == 'circle'){
		width = 150;
	} else if(type == 'square'){
		width = 200;
	}

	$image_crop = $('#image-demo').croppie({
	    enableExif: true,
	    viewport: {
	        width: width,
	        height: 150,
	        type: 'square'
	    },
	    boundary: {
	        width: 250,
	        height: 200
	    },
	    enableOrientation: true
	});

	// モーダルウィンドウの表示
	$('#upload_image').on('change', function () {
	  var reader = new FileReader();
	    reader.onload = function (event) {
	      $image_crop.croppie('bind', {
	        url: event.target.result
	      }).then(function(){
	        console.log('jQuery bind complete');
	      });
	    }
	    reader.readAsDataURL(this.files[0]);
	    $('#uploadimageModal').modal('show');
	});

	// モーダルウィンドウ
	//回転ボタン
	  $('.vanilla-rotate').on('click', function(event) {
	      $image_crop.croppie('rotate', '-90');
	  });

	// 登録ボタン
	 var csrf = $('input[name=_csrfToken]').val();
	$('.crop_image').on('click', function (event) {
	  $image_crop.croppie('result', {
	    type: 'canvas',
	    size: { width: 600, height: 600 }
	  }).then(function (image) {
	    $.ajax({
	      url: "/ajax/"+path,
	      type: "POST",
	      data: {
	      	"image":image,
	      	"id":id
	      },
	      beforeSend: function(xhr){
	        xhr.setRequestHeader('X-CSRF-Token', csrf);
	      },
	      success: function (data) {
	      	$('#uploadimageModal').modal('hide');
	      	location.reload()
	      	$('#uploaded_image').html(data);
	      },
	      error: function (data, status, errors){
	      }
	    });
	  });
	});