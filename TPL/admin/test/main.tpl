<?=LE::$TPL->fetch('svg_icons')?>
<?=LE::$TPL->fetch('test/top_menu')?>

<?php
function icon($id,$t="r")
{
    return '<svg class="lei_'.$t.'"><use href="#ico_'.$id.'"></svg>';
}

?>







<div id="content">









<div style="max-width:1200px;margin: 40px auto;">
<h2>Кнопки</h2>
<button class="le_btn">Кнопка</button>
<button class="le_btn">Кнопка<?=icon('edit')?></button>
<button class="le_btn">Кнопка<?=icon('download')?></button>
<button class="le_btn">Кнопка<?=icon('upload')?></button>
<button class="le_btn">Кнопка<?=icon('dropdown')?></button>
<button class="le_btn">Кнопка<?=icon('dropup')?></button>
<button class="le_btn">Кнопка<?=icon('acc')?></button>
<button class="le_btn">Кнопка<?=icon('plus')?></button>
<button class="le_btn">Кнопка<?=icon('trash')?></button>
<button class="le_btn"><?=icon('trash','l')?>Кнопка</button>
<button class="le_btn"><?=icon('settings','c')?></button>
<br>
<button class="le_btn_gr">Зеленая<?=icon('trash')?></button>
<button class="le_btn_rd">Красная<?=icon('trash')?></button>
<button class="le_btn_bl">Синяя<?=icon('trash')?></button>

<span></span>


<h2>Иконки</h2>
<div class="icons" style="font-size:40px;">
<i><svg><use href="#ico_edit"></svg></i>
<i><svg><use href="#ico_download"></svg></i>
<i><svg><use href="#ico_upload"></svg></i>
<i><svg><use href="#ico_trash"></svg></i>
<i><svg><use href="#ico_plus"></svg></i>
<i><svg><use href="#ico_trash2"></svg></i>
<i><svg><use href="#ico_dropdown"></svg></i>
<i><svg><use href="#ico_dropup"></svg></i>
<i><svg><use href="#ico_copy"></svg></i>
<i><svg><use href="#ico_acc"></svg></i>
<i><svg><use href="#ico_settings"></svg></i>
<i><svg><use href="#ico_items"></svg></i>
<i><svg><use href="#ico_post"></svg></i>
<i><svg><use href="#ico_book"></svg></i>
<i><svg><use href="#ico_close"></svg></i>
<i><svg><use href="#ico_menu"></svg></i>
<i><svg><use href="#ico_list"></svg></i>
<style>
</style>
</div>




<table class="tbl">
<caption>CRUD-табличка</caption>
<tr><th style="width:80px">
<button class="le_btn"><?=icon('plus','c')?></button>

</th><th>Header1</th><th>Header2</th><th>Header3</th></tr>
<tr><td><button class="le_btn"><?=icon('edit','c')?></button><button class="le_btn_rd"><?=icon('trash','c')?></button></td><td>val1</td><td>val2</td><td>val3</td></tr>
<tr><td><button class="le_btn"><?=icon('edit','c')?></button><button class="le_btn_rd"><?=icon('trash','c')?></button></td><td>val1</td><td>val2</td><td>val3</td></tr>
<tr><td><button class="le_btn"><?=icon('edit','c')?></button><button class="le_btn_rd"><?=icon('trash','c')?></button></td><td>val1</td><td>val2</td><td>val3</td></tr>
<tr><td><button class="le_btn"><?=icon('edit','c')?></button><button class="le_btn_rd"><?=icon('trash','c')?></button></td><td>val1</td><td>val2</td><td>val3</td></tr>
<tr><td><button class="le_btn"><?=icon('edit','c')?></button><button class="le_btn_rd"><?=icon('trash','c')?></button></td><td>val1</td><td>val2</td><td>val3</td></tr>
<tr><td><button class="le_btn"><?=icon('edit','c')?></button><button class="le_btn_rd"><?=icon('trash','c')?></button></td><td>val1</td><td>val2</td><td>val3</td></tr>


</table>


<h2>Формочка</h2>

<form class="le_form le_shadow">
<span class="le_form_head">Заголовок формы</span>

<div class="le_he">
    <label for="inp_name" class="le_fl"><span>Горизонтальный инпут</span></label>
    <div class="le_inp"><input type="text" value="kokoko" id="inp_name"></div>
</div>

<div class="le_ve">
    <label for="inp_name" class="le_fl"><span>Вертикальный инпут</span></label>
    <div class="le_inp"><input type="text" value="kokoko" id="inp_name"></div>
</div>

<div class="le_he">
    <label for="inp_name" class="le_fl"><span>Горизонтальный инпут у которого допустим пару строчек, строчки тут две <sup>*</sup></span></label>
    <div class="le_inp"><input type="text" value="kokoko" id="inp_name"></div>
</div>

<div class="le_he">

    <div for="select_koko" class="le_fl"><span>Горизонтальный селект</span></div>
    <div class="le_inp">
        <select id="select_koko">
            <option>select</option>
            <option>select</option>
            <option>select</option>
            <option>select</option>
            <option>select</option>
        </select>
    </div>

</div>

<div class="le_he le_me">
    <div for="inp_name" class="le_fl"><span>Радиокнопки</span></div>
    <div class="le_inp">
        <label><input type="radio" name="radio1">Radio1</label>
        <label><input type="radio" name="radio1">Radio2</label>
    </div>
</div>

<div class="le_ve le_me">
    <div for="inp_name" class="le_fl"><span>Радиокнопки вертикально</span></div>
    <div class="le_inp">
        <label><input type="radio" name="radio1">Radio1</label>
        <label><input type="radio" name="radio1">Radio2</label>
    </div>
</div>

<div class="le_he le_me">
    <div for="inp_name" class="le_fl"><span>Чекбоксы<sup>*</sup></span></div>
    <div class="le_inp">
        <label><input type="checkbox" name="checkbox1">checkbox1</label>
        <label><input type="checkbox" name="checkbox1">checkbox2</label>
    </div>
</div>

<div class="le_he le_me le_meh">
    <div for="inp_name" class="le_fl"><span>Радиокнопки в линию</span></div>
    <div class="le_inp">
        <label><input type="radio" name="radio1">Radio1</label>
        <label><input type="radio" name="radio1">Radio2</label>
    </div>
</div>

<div class="le_he">
    <label for="inp_name" class="le_fl"><span>Дата <sup>*</sup></span></label>
    <div class="le_inp"><input type="date" value="kokoko" id="inp_name"></div>
</div>

<div class="le_ve">
    <label for="inp_name" class="le_fl"><span>Текст</span></label>
    <div class="le_inp"><textarea class="cktxt">тролололо</textarea></div>
</div>

<div class="le_he">
    <label for="inp_name" class="le_fl"><span>Текст горизонтально</span></label>
    <div class="le_inp"><textarea>тролололо</textarea></div>
</div>

<div class="le_ve">
    <label for="inp_name" class="le_fl"><span>Текст</span></label>
    <div class="le_inp"><textarea class="tu-editor">тролололо</textarea></div>
</div>


<div class="le_bbl">
<button class="le_btn" type="submit">Сохранить</button>
<button class="le_btn_bl" type="submit">Синяя</button>
<button class="le_btn_rd" type="submit">Красная</button>
<button class="le_btn_gr" type="submit">Зеленая</button>
</div>

</form>

<div class="modal">
<div class="modal_in">
<div class="mod_h">Готово</div>
<div class="mod_cont">kokoko</div>
<div class="mod_btn">
<button class="le_btn_rd" type="submit">Красная</button>
</div>
</div>

</div>

<style>
.modal {
    display:none;
    background: #fafafa;
    background: transparent;
    position: fixed;
    top:0%;
    left:0%;
    width:100%;
    height:100%;
}

.modal .modal_in {
    box-sizing:border-box;
    background: #fafafa;
    border:1px solid #ccc;
    width:350px;
    max-width:95%;
    min-width:200px;
    margin: 20% auto;
    padding:10px;
}
.modal .mod_h {
    
}
</style>




<h2>Алерты</h2>
<h2>Конфирмы</h2>
<h2>Модальные окошки</h2>

+







</div>
<div class="mp_footer ">
    
    &copy; <a href="http://pavelb.ru/ ">LE Framework by Pavel Belyaev</a> | 2010-2021

	
</div>