<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$t->get('title')?></title>
<link href="<?=WEB_PREFIX?>Brill/css/page.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div id="block_login" class="">
<table border="0">
<tbody>
    <tr><td style="width: 150px; height: 80px;">
         <img src="<?=WEB_PREFIX?>Brill/img/logo_default.png" align="middle" alt="Daemonic"/>
         <b>Daemonic</b>
    </td>
    <td class="last_col">
        <?=$t->get('form')->buildHtml('auth_form','mini_form', 'Зайти')?>
        <div style="clear: both;"></div></td>
    </tr>
    <tr>
        <td>Ошибка авторизации</td>
        <td style="text-align: right; padding: 0 6px 3px 0"><a href="#">Регистрация</a></td>
</tr>
</tbody></table>
</div>

</body>
</html>