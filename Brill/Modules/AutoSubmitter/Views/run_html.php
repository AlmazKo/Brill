<?=TFormat::viewErrorsContent($t->getErrors())?>
<? if($t->is('form')) : ?>
    <?=$t->get('form')->buildHtml('subscribe_form')?>
    <script type="text/javascript">
        $('#subscribe_form').bind('submit', function() {
            $(this).ajaxSubmit({
                target: '#page_content'
            });
            return false;
        });
    </script>
<? endif ?>