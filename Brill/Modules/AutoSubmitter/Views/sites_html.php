<h3>Список всех сайтов</h3>
<table border="0" cellpadding="0" cellspacing="0" class="table">
    <?=$t->get('tbl')->buildHead()?>
<?php
foreach($t->get('tbl')->getValues() as $row) : 
 ?>
        <tr>
            <td><?=$row['host']?></td>
            <td><?=$row['config_status']?></td>
            </td>
        </tr>
<?php endforeach;?>
</table>