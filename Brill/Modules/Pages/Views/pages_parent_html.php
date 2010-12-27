<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$t->get('title')?></title>
<link href="<?=WEB_PREFIX?>Brill/css/page.css" type="text/css" rel=stylesheet />
<script type="text/javascript" src="<?=WEB_PREFIX?>Brill/js/jquery-1.4.3.min.js"></script>
<script type="text/javascript" src="<?=WEB_PREFIX?>Brill/js/jquery.form.js<?=USE_CACHE ? '' : '?uid='.  uniqid() ?>"></script>
<script type="text/javascript" src="<?=WEB_PREFIX?>Brill/js/jquery.blockUI.js"></script>
</head>
<body>
    <table id="page" cellpadding="0" cellspacing="0">
        <tr id="page_head"><td class="first_col">
            <a href="<?=WEB_PREFIX?>">
                <img src="<?=WEB_PREFIX?>Brill/img/logo_default.png" align="middle"/></a> <div><b>Daemonic</b><br /><i>core version: <?=CORE_VERSION?></i></div>

        </td>
            <td id="head_center" colspan="2"><div class="welcome">Приветствуем Вас на нашем сервисе!</div><?php include ($t->getTpl('user_block'))?></td></tr>
         <tr id="page_body"><td id="page_menu">

            <ul id="menu">
            <li> <span>Пользователи и доступы</span>
            <ul>
            <li id="subscribes" class="yes"><a href="<?=WEB_PREFIX?>Auth/Users/List/">Пользователи</a>
            <b class="opt"><a href="<?=WEB_PREFIX?>Auth/Users/Add/"><img src="<?=WEB_PREFIX?>Brill/img/add.png" /></a></b>

            </li>
            </ul>
            </li>

                <li> <span>Рассылки</span>
            <ul>
            <li id="subscribes" class="yes"><a href="<?=WEB_PREFIX?>AutoSubmitter/Subscribe/List/">Рассылки</a></li>
            <li id="startSubscribe" class="yes"><a href="<?=WEB_PREFIX?>AutoSubmitter/Subscribe/Add/">Создать новую рассылку</a></li>
            </ul>
            </li>
            <li> <span>Сайты</span>
            <ul>
            <li class="yes" id="listSites"><a href="<?=WEB_PREFIX?>AutoSubmitter/Sites/List/">Сконфигурированные</a></li>
            <li class="yes" id="addSites"><a href="<?=WEB_PREFIX?>AutoSubmitter/Sites/Add/">Добавить</a></li>
            </ul>

            </li>

            <li> <span>SE парсинг</span>
            <ul>
            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/Projects/">Проекты</a>
                <b class="opt"><a href="<?=WEB_PREFIX?>SEParsing/Projects/add/"><img src="<?=WEB_PREFIX?>Brill/img/add.png" /></a></b>
            </li>

            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/Sets/">Сеты</a>
                <b class="opt"><a href="<?=WEB_PREFIX?>SEParsing/Keywords/massAdd/"><img src="<?=WEB_PREFIX?>Brill/img/add.png" /></a></b>
            </li>
            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/Thematics/">Тематики</a>
                <b class="opt"><a href="<?=WEB_PREFIX?>SEParsing/Thematics/add/"><img src="<?=WEB_PREFIX?>Brill/img/add.png" /></a></b>
            </li>
            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/Keywords/">Ключевики</a>
                <b>
                    <a href="<?=WEB_PREFIX?>SEParsing/Keywords/add/"><img src="<?=WEB_PREFIX?>Brill/img/add.png" /></a>
                    <a href="<?=WEB_PREFIX?>SEParsing/Keywords/massAdd/"><img src="<?=WEB_PREFIX?>Brill/img/mass_add.png" /></a>
                </b>
            </li>
            <li class="sep"></li>

            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/YandexAccesses/">Аккаунты <span class="yandex"><span>Y</span>andex</span></a>
                <b class="opt"><a href="<?=WEB_PREFIX?>SEParsing/YandexAccesses/add/"><img src="<?=WEB_PREFIX?>Brill/img/add.png" /></a></b>
            </li>
            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/Regions/">Регионы</a>
                <b class="opt"><a href="<?=WEB_PREFIX?>SEParsing/Regions/add/"><img src="<?=WEB_PREFIX?>Brill/img/add.png" /></a></b>
            </li>
            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/Sets/">Отчет по демонам</a></li>

                <li> <span>Демона</span>
                    <ul>
                        <li class="yes">KeywordsYandexXML</li>
                        <li class="yes">KeywordsDotYandexXML</li>
                        <li class="yes">FullPagesYandexXML</li>
                        <li class="yes">KeywordsGoogle</li>
                        <li class="yes">KeywordsRambler</li>
                        <li class="yes">KeywordsWordstat</li>
                    </ul>

                </li>
            </ul>
            </li>
            
            
            
            
            
            
            <li> <span>Настройки</span>
            <ul>
            <li class="yes"><a href="<?=WEB_PREFIX?>Settings/Interfaces/">Интерфейсы бота</a>
                <b class="opt"><a href="<?=WEB_PREFIX?>Settings/Interfaces/add/"><img src="<?=WEB_PREFIX?>Brill/img/add.png" /></a>
                <a href="<?=WEB_PREFIX?>Settings/Interfaces/massAdd/"><img src="<?=WEB_PREFIX?>Brill/img/mass_add.png" /></a></b>
            </li>
            <li class="no"><a href="<?=WEB_PREFIX?>Settings/StatsToday/">Статистика за сегодня</a></li>
            <li class="yes"><a href="<?=WEB_PREFIX?>Settings/Hosts/">Источники</a></li>
            <li class="yes"><a href="<?=WEB_PREFIX?>Settings/Limits/">Ограничения</a></li>
            <li class="no"><a href="<?=WEB_PREFIX?>Settings/Errors/">Ошибки</a></li>
            </ul>

            </li>
 
            
            </ul>
            </td>
             <td id="page_content" colspan="2">
               <?php include ($t->getTpl('content'))?>
             </td>
             </tr>
    </table>
    <div id="footer">
        <span class="view_debug">Подробнее &raquo;</span>
        <span class="info_product">This product includes PHP software, freely available from http://www.php.net/software/</span>
        <? if (Log::isView()) : ?>
            <div class="debug_full"><?=Log::viewLog()?></div>
        <? endif ?>
    </div>
<script type="text/javascript">

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

</script>

</body>
</html>