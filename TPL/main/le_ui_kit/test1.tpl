<?$tpl->need_static(['tui-editor','jquery','le_form','ckeditor5','highlight.js'])?>


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
<button class="le_btn le_btn_blue" type="submit">Синяя</button>
<button class="le_btn le_btn_red" type="submit">Красная</button>
<button class="le_btn le_btn_green" type="submit">Зеленая</button>
</div>

</form>

<br>
<br>
<br>
<br>
<h2>Markdown Editor from ToastUI</h2>

<div id="editor"></div>
<a href="#" onclick="alert(editor.getHtml()); return false;">html get</a> | 
<a href="#" onclick="alert(editor.getMarkdown()); return false;">markdown get</a> |
<a href="#" onclick="hljs.highlightAll(); return false;">Code hilight</a> |



<script>
window.addEventListener('load', (event) => 
{

    
function uploadImage(blob) {
    let formData = new FormData();

    formData.append("upl_img", blob, blob.name);
    formData.append("opt", 'upload_img');
    formData.append("clear", 'yes');

    return fetch('/ui_test', {
        method: 'POST',
        body: formData
    }).then(response => {
        if (response.ok) {
            return response.json();
        }

        throw new Error('Server or network error');
    });
};

function onAddImageBlob(blob, callback) {
    
    uploadImage(blob)
        .then(response => {
            if (!response.success) {
                throw new Error('Validation error');
            }

            callback(response.data.url, 'alt text');
        }).catch(error => {
            console.log(error);
        });
};
   
    
    
    const editor = new toastui.Editor({
        el: document.querySelector('#editor'),
        previewStyle: 'vertical',
        height: '500px',
        initialValue: '### hello world \n```\n<?="<?"?>php\n```',
        hooks: 
        {
            addImageBlobHook:function(blob, callback)
            {
                let formData = new FormData();

                formData.append("upl_img", blob, blob.name);
                formData.append("opt", 'upload_img');
                formData.append("clear", 'yes');

                fetch('/ui_test', {method: 'POST',body: formData
                }).then(resp => resp.json()).then(resp=>{
                   if (!resp.success) {throw new Error('Validation error');}
                   callback(resp.data.url, 'alt text');
                });
            }  
        }
    });


    editor.on('change',function(e){
        document.querySelectorAll('div.te-preview pre').forEach(block => {hljs.highlightBlock(block);});
    }); 

    hljs.highlightAll(); 

    $(function() {
    $('#nav-icon6').click(function(){
        $(this).toggleClass('open');
    });
}); 

});
</script>


<hr/>

<button class="c-hamburger c-hamburger--rot">
  <span>toggle menu</span>
</button>
<button class="c-hamburger c-hamburger--htx">
  <span>toggle menu</span>
</button>
<button class="c-hamburger c-hamburger--htla">
  <span>toggle menu</span>
</button>
<button class="c-hamburger c-hamburger--htra">
  <span>toggle menu</span>
</button>


	
<div id="nav-icon6">
    <span></span>
</div>

