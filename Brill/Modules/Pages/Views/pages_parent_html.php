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
        <tr id="page_head"><td class="first_col"><img src="<?=WEB_PREFIX?>Brill/img/logo_1.png" align="middle"/>Проект</td><td>Мега система по захвату мира</td><td class="last_col"><?=$t->get('auth')->buildHtml()?></td></tr>
         <tr id="page_body"><td id="page_menu">

            <ul id="menu">
                <li> <span>Рассылки</span>
            <ul>
            <li id="subscribes" class="yes"><a href="<?=WEB_PREFIX?>AutoSubmitter/Subscribe/List/">Активные</a></li>
            <li class="none">Завершенные</li>

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
            <li class="no"><a href="<?=WEB_PREFIX?>SEParsing/Sets/">Сеты</a></li>
            <li class="no"><a href="<?=WEB_PREFIX?>SEParsing/Thematics/">Тематики</a></li>
            <li class="yes"><a href="<?=WEB_PREFIX?>SEParsing/Keywords/">Ключевики</a></li>
            </ul>

            </li>
            <li class="yes"> <span>Новости</span> </li>
            <li class="yes"> <span>Написать сообщение</span> </li>
            </ul>
             <td id="page_content">
               <?php include_once ($t->getTpl('content'))?>
             </td>
             <td>Полезная инфа</td></tr>
         <tr><td colspan="3" id="logs_bottom"><?=Log::viewLog()?></td></tr>
    </table>

<script type="text/javascript">
$(document).ready(function(){
    $('.yes').hover(function(e) {
        $(this).css({'color':'red'})
    },function() {
        $(this).css({'color':'#333'})
    });

    $('#page_menu a').click(function() {
        var block = $("#page_content");
        var position = block.position();
        var posImg = 300;
        block.append('<div id="loading"><img src="<?=WEB_PREFIX?>Brill/img/loader1.gif" style="margin-top:48px;margin-left:'+posImg+'px"></div>');
        $('#loading').css({'top': position.top, 'left':position.left,
                           'width': block.width(), 'height':block.height()+10,
                           'opacity':0.8});
        $('#loading').show(100);

        $.ajax({
            type: "GET",
            url: $(this).attr('href'),
            beforeSend:function(data, textStatus){


            },
            stop:function(data, textStatus){
              //  $('#loading').hide();
            },
            success: function(data, textStatus){
                block.html(data);
            },
            complete: function(){

                $('#loading').hide();
            }
        });
                                            
        return false;
    });
});

</script>

</body>
</html>