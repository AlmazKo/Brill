<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$t->get('title')?></title>
<link href="/ba/Brill/css/page.css" type="text/css" rel=stylesheet />
<script type="text/javascript" src="/ba/Brill/js/jquery-1.4.3.min.js"></script>
<script type="text/javascript" src="/ba/Brill/js/jquery.form"></script>
</head>
<body>
    <table id="page" cellpadding="0" cellspacing="0">
        <tr id="page_head"><td class="first_col"><img src="/ba/Brill/img/logo_1.png" align="middle"/>Проект</td><td>Мега система по захвату мира</td><td class="last_col"><?=$t->get('auth')->buildHtml()?></td></tr>
         <tr id="page_body"><td id="page_menu">
                 
            <ul id="menu">
                <li> <span>Рассылки</span>
            <ul>
            <li id="subscribes" class="yes">Активные</li>
            <li class="none">Завершенные</li>

            <li id="startSubscribe" class="yes">Начать новую</li>
            </ul>
            </li>
            <li class="yes"> <span>Новости</span> </li>
            <li class="yes"> <span>Написать сообщение</span> </li>
            </ul>
             <td id="page_content">
               <? include_once ($t->get('tpl'))?>
                 <br />
                 

             </td>
             <td>Полезная инфа</td></tr>
         <tr><td colspan="3"><?=Log::viewLog()?></td></tr>
    </table>

<script type="text/javascript">
$(document).ready(function(){
    $('#startSubscribe').click(function() {
         $("#page_content").load("/ba/AutoSubmitter/Subscribe/Start/");
    });
    $('#subscribes').click(function() {
         $("#page_content").load("/ba/AutoSubmitter/Subscribe/List/");
    });

    $('.yes').hover(function(e) {
        $(this).css({'color':'green'})
    },function() {
        $(this).css({'color':'#333'})
    })
});

</script>

</body>
</html>