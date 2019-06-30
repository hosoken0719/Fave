$(function() {
    $( 'input[name="rating"]:radio' ).change( function() {
        event.preventDefault();
        $.ajax({
            type: 'GET',
            url: '/ajax/shoprating',
            data: {
                shop: $(':hidden[name="shop_id"]').val(),
                rating: $(this).attr('data-rating')
                // button: $(':hidden[name="button"]').val(),
            },
            dataType: 'json',
        })
        .then(
            function(data) {
                var response = JSON.parse(data);
                if(response.result == 'fail'){
                    alert('失敗しました。一度画面を更新してください');
                // }else{
                //     alert('失敗しました。一度画面を更新してください');
                }
            // },
            // function(data) {
            //     // alert('失敗しました。一度画面を更新してください');
            }
        )
    })
});

// ユーザをフォローする
$(function() {


    $(document).on('click', '.follow_button', function(){
        event.preventDefault();
        $.ajax({
            type: 'GET',
            url: '/ajax/followuser',
            data: {
                user: $(this).attr('data-user'),
                button: $(this).attr('data-button'),
            },
            dataType: 'json',

        })
        .then(
            function(data) {
                var response = JSON.parse(data);
                if(response.result == 'follow'){
                    document.getElementById('button'+response.button).value='フォロー中';
                }else if(response.result == 'followed'){
                    document.getElementById('button'+response.button).value='フォローする';
                }
            },
            function(data) {
                // var response = JSON.parse(data);
                // alert(response);
                alert('失敗しました。一度画面を更新してください');
            }
        )
    })
});







// お店をお気に入りに追加する
$(function() {
    $(document).on('click', '.favorite_button', function(){
     const csrf = $('input[name=_csrfToken]').val();
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/ajax/shoprating',
            data: {
                shop: $(this).attr('data-shop'),
                rating: $("input[name='input_rating']:checked").val(),
                review: $("#input_review").val(),
            },
            dataType: 'json',
            beforeSend: function(xhr){
            $('.loading').removeClass('hide');//loading画像
            xhr.setRequestHeader('X-CSRF-Token', csrf);
            }
        })
        .then(
            function(data) {
                var response = JSON.parse(data);
                document.getElementById('favorite_button').value='更新';
            },
            function(data) {
                alert('失敗しました。一度画面を更新してください');
            }
        ).then(//always
            function() {
                $('.loading').addClass('hide');
            }
        )
    })
});

$(function() {
    $(document).on('click', '.delete_favorite', function(){
        event.preventDefault();
        $.ajax({
            type: 'GET',
            url: '/ajax/deleteshoprating',
            data: {
                shop: $(this).attr('data-shop'),
            },
            dataType: 'json',
                        //loading画像
            beforeSend: function(){
            $('.loading').removeClass('hide');
          }
        })
        .then(
            function(data) {
                var response = JSON.parse(data);
                document.getElementById('favorite_button').value='更新';                
            },
            function(data) {
                var response = JSON.parse(data);
                alert('失敗しました。一度画面を更新してください');
            }
        ).then(//always
            function() {
                $('.loading').addClass('hide');
            }
        )
    })
});
