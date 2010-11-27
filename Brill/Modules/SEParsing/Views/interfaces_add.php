<h1><?=$t->h1?></h1>
<? if ($t->is('form')) :?>
<?=$t->form->buildHtml('InterfaceForm')?>
    <script type="text/javascript">
        $('#InterfaceForm').bind('submit', function() {
            $(this).ajaxSubmit({
                target: '#page_content'
            });
            return false;
        });
    </script>
<? else : ?>
Данные успешно добавлены
<? endif ?>