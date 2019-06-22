$(function() {
  $("#ac_word").autocomplete({
    source: '/ajax/acword',
    autoFocus: true,
    delay: 50,
    minLength: 2,
    open: function() {
      $('.ui-autocomplete').off('menufocus');
      // $('.ui-autocomplete').off('menufocus hover mouseover mouseenter');
    },
  });


  $('#ac_user').autocomplete({
    source: '/ajax/acuser',
    autoFocus: false,
    delay: 10,
    minLength: 2,
    select: function(e, ui) {
      location.href = ui.item.userlink;
      return false;
    },
    open: function() {
      $('.ui-autocomplete').off('menufocus');
      // $('.ui-autocomplete').off('menufocus hover mouseover mouseenter');
    },
    })
    .autocomplete('instance')._renderItem = function(ul, item) {
    return $( '<li>' )
      // .append( '<a>' +  item.label + '</a>' )
      .append(
        '<div class=¥"summary¥"><div class=¥"photo¥"><img src=¥"' + item.icon + ' ¥" class=¥"img-circle img-responsive¥"></div><div class=¥"infor¥">' + 
        '<span class=¥"name¥"><p>' + item.username +  '</p></span><p></p></span></div></div></div>'
      )
      .appendTo( ul );
    };

    $('#ac_user').on('focus', function(){ $('#ac_user').triggerHandler('keydown');});

});




  // $('#ac_user').autocomplete({
  //   source: '/ajax/acuser',
  //   autoFocus: false,
  //   delay: 500,
  //   minLength: 2,
  //   select: function(e, ui) {
  //     location.href = ui.item.userlink;
  //     return false;
  //   },
  //   open: function() {
  //     $('.ui-autocomplete').off('menufocus');
  //     // $('.ui-autocomplete').off('menufocus hover mouseover mouseenter');
  //   },
  //   }).autocomplete('instance')._renderItem = function(ul, item) {
  //   return $( '<li>' )
  //     // .append( '<a>' +  item.label + '</a>' )
  //     .append(
  //           '<div class=¥"summary¥"><div class=¥"photo¥"><img src=¥"' + item.icon + ' ¥" class=¥"img-circle img-responsive¥"></div><div class=¥"infor¥">' + 
  //           '<span class=¥"name¥"><p>' + item.username +  '</p></span><p></p></span></div></div></div>'
  //           )
  //     .appendTo( ul );
  //   };
  // 