<h1><?=$t->h1?></h1>
<? if ($t->is('form')) :?>
<?=$t->form->buildHtml('addForm', 'Form', 'Добавить')?>
    <script type="text/javascript">
        $('#addForm').bind('submit', function() {
            $(this).ajaxSubmit({
                target: '#page_content'
            });
            return false;
        });
    </script>
<? endif ?>