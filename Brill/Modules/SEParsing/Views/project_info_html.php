<h1>Проект &laquo;<?=$t->h1?>&raquo;</h1>
  <p>Сайт: <a href="<?=$t->site?>"><?=$t->site?></a></p>
  <p>Описание: <?=$t->descr?></p>
<p>Сеты проекта:
    <?=$t->tbl->build('sets','table')?>
</p>

<p>Страницы сайта:
    <?=$t->tbl_pages->build('pages','table')?>
</p>
