<?php
$t = RegistryContext::instance();
$t->simpleAccessToVars();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title><?=_title?></title>
<link href="/newindex/css/page.css" type="text/css" rel=stylesheet />
</head>
<body>

<a href="newindex.php?view=keywords">Ключевики</a> | <a href="newindex.php?view=keywords&act=add">Добавить ключевики</a><br/>
<a href="newindex.php?view=thematics&act=view">Тематики</a> | <a href="newindex.php?view=thematics&act=add">Добавить тематику</a><br/>
<a href="newindex.php?view=sets&act=view">Сеты</a>
<br/><br/>

<h1><?=_h1?></h1>
<? include_once _tpl;?>
<? /*include_once 'Logs.tpl'*/ ?>

</body>
</html>