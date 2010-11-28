<h1><?=$t->h1?></h1>
<? if ($t->is('form')) :?>
<?=$t->form->buildHtml('editForm')?>
    <script type="text/javascript">
        $('#editForm').bind('submit', function() {
            $(this).ajaxSubmit({
                target: '#page_content'
            });
            return false;
        });
    </script>
<? endif ?>