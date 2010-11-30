<div id="wizzard_subscribe">
    <h3>Создание новой рассылки</h3>
<?php
switch ($t->get('step')):
case 0: ?>
<?=$t->get('info_text')?>
<?=$t->get('form')->buildHtml('UserForm')?>
<?php break;?>
<?php case 1: ?>
<?=$t->get('info_text')?>
<?=$t->get('form')->buildFree('UserForm', $t->get('tbl')->buildHtml())?>
<?php break;?>
<?php case 2: ?>
<?=$t->get('info_text')?>
<?=$t->get('form')->buildHtml('UserForm')?>
<?php break;?>
<?php case 3: ?>
Рассылка успешна добавлена.

<a href="#">Начать рассылку</a>
<?php endswitch;?>
</div>