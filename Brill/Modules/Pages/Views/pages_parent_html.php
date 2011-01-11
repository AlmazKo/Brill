<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$t->get('title')?></title>
<link href="<?=WEB_PREFIX?>Brill/css/page.css" type="text/css" rel=stylesheet />
<script type="text/javascript" src="<?=WEB_PREFIX?>Brill/js/jquery-1.4.3.min.js"></script>
<script type="text/javascript" src="<?=WEB_PREFIX?>Brill/js/jquery.form.js<?=USE_CACHE ? '' : '?uid='.  uniqid() ?>"></script>
<script type="text/javascript" src="<?=WEB_PREFIX?>Brill/js/jquery.blockUI.js"></script>
<script type="text/javascript" src="<?=WEB_PREFIX?>Brill/js/my.js"></script>
</head>
<body>
    <table id="page" cellpadding="0" cellspacing="0">
        <tr id="page_head"><td class="first_col">
            <a href="<?=WEB_PREFIX?>">
                <img src="<?=WEB_PREFIX?>Brill/img/logo_default.png" align="middle"/></a> <div><b>Daemonic</b><br /><i>core version: <?=CORE_VERSION?></i></div>

        </td>
            <td id="head_center" colspan="2"><div class="welcome">Приветствуем Вас на нашем сервисе!</div><?php include ($t->getTpl('user_block'))?></td></tr>
         <tr id="page_body"><td id="page_menu">
                 <?php include ($t->getTpl('block_menu'))?>
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
</body>
</html>