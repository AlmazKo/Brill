<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$t->get('title')?></title>
<link href="<?=WEB_PREFIX?>Brill/css/page.css" type="text/css" rel=stylesheet />
<script type="text/javascript" src="<?=WEB_PREFIX?>Brill/js/jquery-1.4.3.min.js"></script>
<script type="text/javascript" src="<?=WEB_PREFIX?>Brill/js/jquery.form"></script>
</head>
<body>

<a href="newindex.php?view=keywords">Ключевики</a> | <a href="newindex.php?view=keywords&act=add">Добавить ключевики</a><br/>
<a href="newindex.php?view=thematics&act=view">Тематики</a> | <a href="newindex.php?view=thematics&act=add">Добавить тематику</a><br/>
<a href="newindex.php?view=sets&act=view">Сеты</a>
<br/><br/>

<h1><?=$t->get('h1')?></h1>
<? include_once ($t->get('tpl'));?>

</body>
</html>