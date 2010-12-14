<div id="block_login" class="<? if ($t->is('error')) :?>block_error<? endif?>">
<? if ($t->is('form')) :?>
<table border="0">
<tbody>
    <tr><td style="width: 150px; height: 80px;">
         <img src="<?=WEB_PREFIX?>Brill/img/logo_default.png" align="middle" alt="Daemonic"/>
         <b>Daemonic</b>
    </td>
    <td class="last_col">
        <?=$t->get('form')->buildHtml('auth_form','mini_form', 'Зайти')?>
        <div style="clear: both;"></div></td>
    </tr>
    <tr>
        <td colspan="2"><? if ($t->is('error')) :?>
                <?=$t->error?>
            <? endif?>
        <div style="float:right; text-align: right; padding: 0 6px 3px 0"><a href="#">Регистрация</a></div></td>
</tr>
</tbody></table>
<? else : ?>
    <table border="0">
<tbody>
    <tr><td style="width: 150px; height: 80px;">
         <img src="<?=WEB_PREFIX?>Brill/img/logo_default.png" align="middle" alt="Daemonic"/>
         <b>Daemonic</b>
    </td>
    <td class="last_col">
        <div style="margin:0 29px"><a href="<?=WEB_PREFIX?>">Перейти к работе</a></div>
        <div style="clear: both;"></div></td>
    </tr>
    <tr><td colspan="2">Вы успешно авторизованы</td>
    </tr>
    <tr>
</tr>
</tbody></table>

    
<? endif ?>

</div>