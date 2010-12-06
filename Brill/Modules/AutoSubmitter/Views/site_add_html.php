<?=$t->get('info_text')?>
<? if($t->is('form')) : ?>
    <?=$t->get('form')->buildHtml('UserForm')?>
    <script type="text/javascript">
        $('#UserForm').bind('submit', function() {
            $(this).ajaxSubmit({
                target: '#page_content'
            });
            return false;
        });
    </script>
<? endif ?>