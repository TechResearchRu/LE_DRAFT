<?$tpl->need_static(['tui-editor','jquery','le_form','ckeditor5','highlight.js'])?>
<a href="/editor_test">Назад к списку</a>
<h2>Редактор статьи</h2>

<div class="txt_cont" id="editor" style="overflow:hidden;">
kokoko
</div>
<script>
window.addEventListener('load', (event) => 
{    
    ClassicEditor.create( document.querySelector( '#editor' ) )
            .catch( error => {
                console.error( error );
            } );




   /* editor.on('change',function(e){
        document.querySelectorAll('div.te-preview pre').forEach(block => {hljs.highlightElement(block);});
    });*/ 

    hljs.highlightAll();  

});
</script>