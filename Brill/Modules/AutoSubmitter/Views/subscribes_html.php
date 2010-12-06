<h3>Список всех рассылок</h3>
<table border="0" cellpadding="0" cellspacing="0" class="table">
<?=$t->get('tbl')->buildHead()?>
<?php $form = new oFormExt();
$tbl = $t->get('tbl');
$pk = $tbl->getFields();
$pk = $pk[0];

foreach($tbl->getValues() as $row) :
    $form->loadFromString($row['form']);
    $valuesForm = $form->getFields(); ?>
        <tr>
            <td><?=$row['name']?></td>
            <td><?php

        $aForm = $form->getArrayAttr(array('title', 'value'));
        foreach ($aForm as $value) : ?>
            <p class="form_txt_field">
                <b class="header_field"> <?=$value['title']?> :</b> <?=TFormat::cutCenter($value['value'], 500)?>
            </p>

        <?php endforeach;?>
            <?
                if ($t->get('tbl')->isOptions()) {
                    echo '<td class="options">';
                    echo $t->get('tbl')->buildOpt($row[$pk]);
                    if (isset($this->_opts[oTable::OPT_EDIT])) {
                        echo '<a href="' . Routing::constructUrl(array('act' => 'edit'), false) . '?' .$pk.  '=' . $row[$pk].'" ajax="1"><img src="' . WEB_PREFIX .'Brill/img/edit.png" /></a>';
                    }
                    if (isset($this->_opts[oTable::OPT_DEL])) {
                        echo '<a href="' . Routing::constructUrl(array('act' => 'del'), false) . '?' .$pk.  '=' . $row[$pk].'" ajax_areusure="1"><img src="' . WEB_PREFIX .'Brill/img/del.png" /></a>';
                    }

                    echo '</td>';
                }
        ?>
            </td>
        </tr>
<?php endforeach;?>
</table>