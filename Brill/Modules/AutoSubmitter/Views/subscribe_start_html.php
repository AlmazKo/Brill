<div id="wizzard_subscribe">
    <h3>Создание новой рассылки</h3>
<? 
switch ($t->get('step')):
case 0: ?>
<?=$t->get('info_text')?>
<?=$t->get('form')->buildHtml('UserForm')?>
<?php break;?>
<? case 1: ?>
<?=$t->get('info_text')?>
<?=$t->get('form')->buildFree('UserForm', $t->get('tbl')->buildHtml())?>
<?php break;?>
<? case 2: ?>
<?=$t->get('info_text')?>
<?=$t->get('form')->buildHtml('UserForm')?>
<?php break;?>
<? case 3: ?>
Рассылка успешна добавлена.

<a href="#">Начать рассылку</a>
<? endswitch;?>
</div>
<script type="text/javascript">
    $('#UserForm').bind('submit', function() {
        $(this).ajaxSubmit({
            target: '#page_content'
        });
        return false;
    });
</script>