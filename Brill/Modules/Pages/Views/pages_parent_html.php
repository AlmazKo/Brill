<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$t->get('title')?></title>
<link href="/Brill/Brill/css/page.css" type="text/css" rel=stylesheet />
<script type="text/javascript" src="/Brill/Brill/js/jquery-1.4.3.min.js"></script>
<script type="text/javascript" src="/Brill/Brill/js/jquery.form"></script>
</head>
<body>
    <table id="page" cellpadding="0" cellspacing="0">
        <tr id="page_head"><td class="first_col"><img src="/Brill/Brill/img/logo_1.png" align="middle"/>Проект</td><td>Мега система по захвату мира</td><td class="last_col"><?=$t->get('auth')->buildHtml()?></td></tr>
         <tr id="page_body"><td id="page_menu">
                 <ul>
                     <li id="startSubscribe">Начать новую рассылку</li>

                 </ul>
             <td id="page_content">
               <?=$t->get('content')?>
                 <br />
                 

             </td>
             <td>Полезная инфа</td></tr>
         <tr><td colspan="3"><?=Log::viewLog()?></td></tr>
    </table>

<script type="text/javascript">
$(document).ready(function(){
    $('#startSubscribe').click(function() {
         $("#page_content").load("/Brill/AutoSubmitter/Subscribe/Start");
    });
});

</script>

</body>
</html>