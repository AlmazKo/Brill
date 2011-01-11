    function loadBlock (block, url) {
        var position = block.position();
        var posImg = 300;
        block.append('<div id="loading">Загрузка...</div>');
        $('#loading').css({'top': position.top + 1, 'left':position.left, 'right': '0',
                           'height':block.height()+10,
                           'opacity':0.9});
        $('#loading').show();  //alert('e[ns ;sdlk');
        $.ajax({
            type: "GET",
            url: url,
            stop:function(data, textStatus){
              //  $('#loading').hide();
            },
            error: function(XHR, textStatus, errorThrown){
                if (303 == XHR.status) {
                    window.location.href=XHR.responseText;
                }
                if (403 == XHR.status) {
                    block.html(XHR.responseText);
                }
            },
            success: function(data, textStatus, XHR){
                block.hide();
                block.html(data);
                block.fadeIn(200);
//alert('!');
//
//                 $.blockUI({
//            theme:     false,
//            message: data,
//            fadeIn: 500,
//            fadeOut: 500,
//            showOverlay: true,
//            centerX: true,
//            focusInput: true,
//            css: {
//                top: '100px',
//                border: '1px solid #999',
//                padding: '2px',
//                backgroundColor: '#fff',
//                '-webkit-border-radius': '5px',
//                '-moz-border-radius': '5px',
//                opacity: 1,
//                cursor: 'default'
//            },
//          	overlayCSS:  {
//                backgroundColor: '#000',
//                opacity:	  	 0.6,
//                cursor:		  	 'wait'
//            }
//        });






            },
            complete: function(){
              //  $('#loading').fadeOut(200);
               // setTimeout(function(){$('.error_content').fadeOut(2000)}, 5000);
              //  $('a img').css('opacity', 0.5);
            }
        });
        return false;
}

$(document).ready(function(){

$.blockUI.defaults.overlayCSS.backgroundColor = '#ff0'; 



    $('form').live('submit', function() {
        $(this).ajaxSubmit({
            target: '#page_content'
        });
        return false;
    });


//
//    $('a img').css('opacity', 0.5);
//
//    $('a img').live("mousemove", function(){
//        $(this).css('opacity', 1);
//    });
//
//    $('a img').live("mouseout", function(){
//        $(this).css('opacity', 0.5);
//    });


    $('#footer .view_debug').hover(function(e) {
        $(this).css({'color':'red'});
    },function() {
        $(this).css({'color':'#999'});
    });

$('#footer .view_debug').click(function(){
    if ($('.debug_full').css('display') == 'block') {
        $('#footer').height(18);
        $('.debug_full').hide();
        $(this).html('Подробнее &raquo;');
    } else {
        $('#footer').height(300);
        $('.debug_full').show();
        $(this).html('&laquo; свернуть');
    }

    
});
    $('li .yes').hover(function(e) {
        $(this).css({'color':'red'});
        $(this).children('b').css({'display': 'inline'});
    },function() {
        $(this).css({'color':'#333'});
        $(this).children('b').hide();
    });


    $('a[areusure]').live("click", function(){
        if (confirm("Вы действительно уверены?")) {

        } else {
            return false;
        }
    });


    $('a[ajax]').live("click", function(){
          var block = $("#page_content");
          loadBlock(block, $(this).attr('href'));
          return false;
    });

    //аякс с запросом подтверждения
    $('a[ajax_areusure]').live("click", function(){
        if (confirm("Вы, действительно хотите удалить запись?")) {
            var block = $("#page_content");
            loadBlock(block, $(this).attr('href'));
            return false;
        } else {
            return false;
        }
    });

    $('.notice').live("click", function(){
        $(this).fadeOut(200);
    });

    $('#page_menu a').click(function() {
        var block = $("#page_content");
        loadBlock(block, $(this).attr('href'));
        return false;
    });

    $('#page_content td').live("mousemove", function(){
       $(this).parent('tr').css({'background-color':'#ecf1f4'});
    });

    $('#page_content td').live("mouseout", function(){
        $(this).parent('tr').css({'background-color':'#fff'});
    });
});
