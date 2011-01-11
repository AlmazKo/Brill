<h1><?=$t->get('h1')?></h1>
<?=TFormat::viewErrorsContent($t->getErrors())?>
<?=TFormat::viewMessagesContent($t->getMessages())?>
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
<? else :?>
    <? if($t->is('text')) : ?>
    <?=$t->get('text')?>
    <? endif ?>
<? endif ?>
