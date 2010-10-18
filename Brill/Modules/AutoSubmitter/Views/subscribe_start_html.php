<?=$t->get('info_text_1')?>
<?=$t->get('form')->buildHtml('UserForm')?>


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