<h1><?=$t->h1?></h1>
<?=$t->form->buildHtml('UserForm')?>
    <script type="text/javascript">
        $('#UserForm').bind('submit', function() {
            $(this).ajaxSubmit({
                target: '#page_content'
            });
            return false;
        });
    </script>