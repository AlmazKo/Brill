<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$t->get('title')?></title>
<link href="<?=WEB_PREFIX?>Brill/css/page.css" type="text/css" rel=stylesheet />
<script type="text/javascript" src="<?=WEB_PREFIX?>Brill/js/jquery-1.4.3.min.js<?=USE_CACHE ? '' : '?uid='.  uniqid() ?>"></script>
<script type="text/javascript" src="<?=WEB_PREFIX?>Brill/js/jquery.form.js<?=USE_CACHE ? '' : '?uid='.  uniqid() ?>"></script>
</head>
<body>
    <table id="page" cellpadding="0" cellspacing="0">
        <tr id="page_head"><td class="first_col"><img src="<?=WEB_PREFIX?>Brill/img/logo_1.png" align="middle"/>Проект</td><td>Ситема управления ботами</td><td class="last_col"><?=$t->get('auth')->buildHtml('auth_form','mini_form', 'Зайти')?></td></tr>
         <tr id="page_body"><td id="page_menu">

            <ul id="menu">
                <li> <span>Рассылки</span>
            <ul>
            <li id="subscribes" class="yes"><a href="<?=WEB_PREFIX?>AutoSubmitter/Subscribe/List/">Активные</a></li>
            <li class="none">Завершенные</li>

            <li id="startSubscribe" class="yes"><a href="<?=WEB_PREFIX?>AutoSubmitter/Subscribe/Run/">Запустить рассылку</a></li>

            <li id="startSubscribe" class="yes"><a href="<?=WEB_PREFIX?>AutoSubmitter/Subscribe/Start/">Начать новую</a></li>
            </ul>
            </li>
            <li> <span>Сайты</span>
            <ul>
            <li class="yes" id="listSites"><a href="<?=WEB_PREFIX?>AutoSubmitter/Sites/List/">Сконфигурированные</a></li>
            <li class="yes" id="addSites"><a href="<?=WEB_PREFIX?>AutoSubmitter/Sites/Add/">Добавить</a></li>
            </ul>

            </li>

            <li> <span>Парсинг</span>
            <ul>
            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/Sets/">Сеты</a>
                <b class="opt"><a href="<?=WEB_PREFIX?>SEParsing/Sets/add/"><img src="<?=WEB_PREFIX?>Brill/img/add.png" /></a></b>
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
            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/Interfaces/">Интерфейсы бота</a>
                <b class="opt"><a href="<?=WEB_PREFIX?>SEParsing/Interfaces/add/"><img src="<?=WEB_PREFIX?>Brill/img/add.png" /></a>
                <a href="<?=WEB_PREFIX?>SEParsing/Interfaces/massAdd/"><img src="<?=WEB_PREFIX?>Brill/img/mass_add.png" /></a></b>
            </li>

            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/YandexAccesses/">Доступы к Яндексу</a>
                <b class="opt"><a href="<?=WEB_PREFIX?>SEParsing/YandexAccesses/add/"><img src="<?=WEB_PREFIX?>Brill/img/add.png" /></a></b>
            </li>
            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/Regions/">Регионы</a>
                <b class="opt"><a href="<?=WEB_PREFIX?>SEParsing/Regions/add/"><img src="<?=WEB_PREFIX?>Brill/img/add.png" /></a></b>
            </li>
            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/Hosts/">Источники</a></li>
            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/Limits/">Ограничения</a></li>
            <li class="no"><a href="<?=WEB_PREFIX?>SEParsing/StatsToday/">Сегодняшняя статистика</a></li>
            <li class="no"><a href="<?=WEB_PREFIX?>SEParsing/Thematics/">Ошибки</a></li>
            </ul>

            </li>
            <li class="yes"> <span>Новости</span> </li>
            <li class="yes"> <span>Написать сообщение</span> </li>
            </ul>
             <td id="page_content" colspan="2">
               <?php include_once ($t->getTpl('content'))?>
             </td>
             </tr>
         <tr><td colspan="3" id="logs_bottom"><?=Log::viewLog()?></td></tr>
    </table>

<script type="text/javascript">

    function loadBlock (block, url) {
        var position = block.position();
        var posImg = 300;
        block.append('<div id="loading"><img src="<?=WEB_PREFIX?>Brill/img/loader1.gif" style="margin-top:48px;margin-left:'+posImg+'px"></div>');
        $('#loading').css({'top': position.top, 'left':position.left,
                           'width': block.width(), 'height':block.height()+10,
                           'opacity':0.8});
        $('#loading').show(100);  //alert('e[ns ;sdlk');
        $.ajax({
            type: "GET",
            url: url,
            stop:function(data, textStatus){
              //  $('#loading').hide();
            },
            success: function(data, textStatus){
                block.html(data);
            },
            complete: function(){
                $('#loading').hide();
                setTimeout(function(){$('.error_content').fadeOut(500)}, 2000);
            }
        });
        return false;
}

$(document).ready(function(){

    $('.yes').hover(function(e) {
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


    $('#page_menu a').click(function() {
        var block = $("#page_content");
        loadBlock(block, $(this).attr('href'));
        return false;
    });
});

</script>

</body>
</html>