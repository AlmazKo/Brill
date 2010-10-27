<h3>Список всех рассылок</h3>
<table border="0" cellpadding="0" cellspacing="0" class="table">
    <?=$t->get('tbl')->buildHead()?>
<? $form = new oFormExt();
foreach($t->get('tbl')->getValues() as $row) : ?>
<? $form->loadFromString($row['form']);
$valuesForm = $form->getFields();
?>
      <tr><td>  <?=$row['name']?>
          <td> 
              <?
foreach($valuesForm as $name => $settings) {
 echo $settings['title'] . ': '. $settings['value'] . '<br>';
}
?>
<?= $valuesForm['title']?>




<? endforeach;?>
</table>


