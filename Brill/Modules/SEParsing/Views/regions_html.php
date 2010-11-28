<h1>Регионы</h1>
<?=TFormat::viewErrorsContent($t->getErrors())?>
<?=$t->tbl->build('regions','table')?>