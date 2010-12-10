<h3>Список всех рассылок</h3>
<table border="0" cellpadding="0" cellspacing="0" class="table">
<?=$t->get('tbl')->buildHead()?>
<?php $form = new oFormExt();
$tbl = $t->get('tbl');
$pk = $tbl->getFields();
$pk = $pk[1];
$i = 1;
foreach($tbl->getValues() as $row) :
    $form->loadFromString($row['form']);
    $valuesForm = $form->getFields(); ?>
        <tr>
            <td class="col_iterator"><?=$i++?></td>
            <td class="options"><?=$tbl->buildOpt($row[$pk])?></td>
            <td><?=$row['name']?></td>
            <td><?php

        $aForm = $form->getArrayAttr(array('title', 'value'));
        $preview = $aForm['preview'];
        $author = $aForm['name'];
?>
            <p class="form_txt_field">
                <b class="header_field"> <?=$preview['title']?> :</b> <?=TFormat::cutCenter($preview['value'], 500)?>
            </p>
            <p class="form_txt_field">
                <b class="header_field"> <?=$author['title']?> :</b> <?=TFormat::cutCenter($author['value'], 500)?>
            </p>
            <?
                if ($tbl->isOptions()) {
                    echo '<td class="options">';
                        echo '<a href="' . Routing::constructUrl(array('act' => 'edit'), false) . '?' .$pk.  '=' . $row[$pk].'" ajax="1"><img src="' . WEB_PREFIX .'Brill/img/edit.png" /></a>';
                        echo '<a href="' . Routing::constructUrl(array('act' => 'del'), false) . '?' .$pk.  '=' . $row[$pk].'" ajax_areusure="1"><img src="' . WEB_PREFIX .'Brill/img/del.png" /></a>';
                    echo '</td>';
                }
        ?>
            </td>
        </tr>
<?php endforeach;?>
</table>