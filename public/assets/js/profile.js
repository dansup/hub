$( document ).ready( function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery("time.timeago").timeago();
    var acoptions = { 
        paramName: 'query',
        serviceUrl: '/api/v1/node/autocomplete.json',
        minChars:3,
        width:320,
        onSelect: function(suggestion) {
            console.log(suggestion['value']);
            location.assign('/nodes/' + suggestion['value']);
        }
    };
    $('#autocomplete').autocomplete(acoptions);
    var flwx = $.ajax( {
      url: "/nodes/" + nodeIp + "/followed.json", 
      method: "POST",
      dataType: 'json'
    });
    flwx.done(function(data) {
    if(data === true) {
        $('button.followButton').addClass('following');
        $('button.followButton').text('Following');
    }
    else {

    }
    });
  });
$('.panel-google-plus > .panel-footer > .input-placeholder, .panel-google-plus > .panel-google-plus-comment > .panel-google-plus-textarea > button[type="reset"]').on('click', function(event) {
      var $panel = $(this).closest('.panel-google-plus');
          $comment = $panel.find('.panel-google-plus-comment');
          
      $comment.find('.btn:first-child').addClass('disabled');
      $comment.find('textarea').val('');
      
      $panel.toggleClass('panel-google-plus-show-comment');
      
      if ($panel.hasClass('panel-google-plus-show-comment')) {
          $comment.find('textarea').focus();
      }
 });
 $('.panel-google-plus-comment > .panel-google-plus-textarea > textarea').on('keyup', function(event) {
      var $comment = $(this).closest('.panel-google-plus-comment');
      
      $comment.find('button[type="submit"]').addClass('disabled');
      if ($(this).val().length >= 1) {
          $comment.find('button[type="submit"]').removeClass('disabled');
      }
 });
$('button.followButton').on('click', function(e){
    e.preventDefault();
    $button = $(this);
    if($button.hasClass('following')){
      var unflw = $.ajax( {
          url: "/nodes/" + nodeIp + "/unfollow.json", 
          method: "POST",
          dataType: 'json'
        });
        unflw.done(function(data) {
          if(data = true) {
            var fbtn = $('#node-followers').text();
            var followers = parseInt(fbtn);
            $('#node-followers').text(followers-1);
          }
          else {

          }
        });
        
        $button.removeClass('following');
        $button.removeClass('unfollow');
        $button.text('Follow');
    } else {
      var flw = $.ajax( {
          url: "/nodes/" + nodeIp + "/follow.json", 
          method: "POST",
          dataType: 'json'
        });
        flw.done(function(data) {
          if(data['follower_addr'] == remAddr) {
            var fbtn = $('#node-followers').text();
            var followers = parseInt(fbtn);
            $('#node-followers').text(followers+1);
            $('#followModal').modal();
          }
          else {
            return;
          }
        });
        
        $button.addClass('following');
        $button.text('Following');
    }
});

$('button.followButton').hover(function(){
     $button = $(this);
    if($button.hasClass('following')){
        $button.addClass('unfollow');
        $button.text('Unfollow');
    }
}, function(){
    if($button.hasClass('following')){
        $button.removeClass('unfollow');
        $button.text('Following');
    }
});