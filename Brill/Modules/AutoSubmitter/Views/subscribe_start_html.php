<?=$t->get('info_text_1')?>
<?=($t->is('form')) ? $t->get('form')->buildHtml('UserForm') : ''?>
<?=($t->is('f')) ? $t->get('f')->buildFree('UserForm', $t->get('tbl')->buildHtml()) : ''?>
<script type="text/javascript">
$(document).ready(function() {
    $('#UserForm').bind('submit', function() {
        $(this).ajaxSubmit({
            target: '#page_content'
        });
        return false;
    });
});
</script>