<h3>Список всех рассылок</h3>
<table border="0" cellpadding="0" cellspacing="0" class="table">
    <?=$t->get('tbl')->buildHead()?>
<? $form = new oFormExt();

foreach($t->get('tbl')->getValues() as $row) : 
    $form->loadFromString($row['form']);
    $valuesForm = $form->getFields(); ?>
        <tr>
            <td><?=$row['name']?></td>
            <td><?

        $aForm = $form->getArrayAttr(array('title', 'value'));
        foreach ($aForm as $value) {
          echo '<p class="form_txt_field"><b class="header_field">' . $value['title'] . ':</b> '.  TFormat::cutRight($value['value'], 500).'</p>';
        }
        
        ?>
            </td>
        </tr>
<? endforeach;?>
</table>