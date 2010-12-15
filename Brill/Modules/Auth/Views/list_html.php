<h1><?=$t->h1?></h1>
<?=TFormat::viewErrorsContent($t->getErrors())?>
<?=$t->tbl->build('table','table')?>