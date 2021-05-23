<!DOCTYPE html>
<html lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">	
	<title>LE UIKit</title>
	<link rel="stylesheet"  type="text/css" href="/assets/css/le_uikit.css" />
	<meta name="viewport" content="width=device-width,initial-scale=1">
<?/*
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
	<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

<link rel="stylesheet"
      href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/styles/default.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/highlight.min.js"></script>

<script>hljs.highlightAll();</script>
*/?>
<link rel="stylesheet"
      href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/styles/default.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/highlight.min.js"></script>

<link rel="stylesheet" href="/pub/css/le_form.css">
<script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>



  <!-- Editor's Dependecy Style -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.4/codemirror.min.css"
  />
  <!-- Editor's Style -->
  <link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css" />
</head>
<body>
<div id="page_cont" style="max-width:1000px;margin:10px auto;padding:10px;">
<button type="button" class="btn btn-primary">Button</button>

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
<button type="submit">Сохранить</button>
<button class="le_btn_blue" type="submit">Синяя</button>
<button class="le_btn_red" type="submit">Красная</button>
<button class="le_btn_green" type="submit">Зеленая</button>

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



</div>
<script>
/*ClassicEditor
    .create( document.querySelector( '.cktxt' ) )
    .then( editor => {
        console.log( editor );
    } )
    .catch( error => {
        console.error( error );
    } );
*/

</script>


  <script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>

  <script>
 
    
      const editor = new toastui.Editor({
        el: document.querySelector('#editor'),
        previewStyle: 'vertical',
        height: '500px',
        initialValue: '### hello world \n```\n<?="<?"?>php\n```'   
      });


 editor.on('change',function(e){
     /*hljs.highlightAll();*/
     document.querySelectorAll('div.te-preview pre').forEach(block => {
        // then highlight each
        hljs.highlightBlock(block);
        });
     
     });   

</script>

</body>
</html>


