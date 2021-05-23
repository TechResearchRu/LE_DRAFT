<?$tpl->need_static(['tui-editor','jquery','le_form','ckeditor5','highlight.js'])?>
<a href="/editor_test">Назад к списку</a>
<h2>Редактор статьи</h2>

<div id="editor" style="overflow:hidden;"></div>
<a href="#" onclick="alert(editor.getHtml()); return false;">html get</a> | 
<a href="#" onclick="alert(editor.getMarkdown()); return false;">markdown get</a> 

<button onclick="save_md_cont();">Сохранить</button>

<script>
editor = false;

function save_md_cont()
{
    cont_md = editor.getMarkdown();
    cont_html = editor.getHtml();
    const formData = new FormData();
    formData.append('clear', 'yes');
    formData.append('ajax', 'yes');
    formData.append('mod', 'save_content');


    formData.append('data[md_cont]', cont_md);
    formData.append('data[html_cont]', cont_html);
    formData.append('data[id]', <?=$id?>);

    fetch('/editor_test', {method: 'POST',body: formData});
    
}



window.addEventListener('load', (event) => 
{    
     editor = new toastui.Editor({
        el: document.querySelector('#editor'),
        previewStyle: 'vertical',
        height: '500px',
        <?/*initialValue: "<?=$md_cont?>",*/?>
        initialValue: <?=json_encode($md_cont)?>,
        hooks: 
        {
            addImageBlobHook:function(blob, callback)
            {
                let formData = new FormData();

                formData.append("upl_img", blob, blob.name);
                formData.append("mod", 'upload_img');
                formData.append("ajax", 'yes');

                fetch('/editor_test', {method: 'POST',body: formData
                }).then(resp => resp.json()).then(resp=>{
                   if (!resp.success) {throw new Error('Validation error');}
                   callback(resp.data.url, 'alt text');
                });
            }  
        },
        customHTMLRenderer: 
        {
            
            
            
            
            
            
            image(node, context) 
            {
                const { destination } = node;
                const { getChildrenText, skipChildren } = context;
                console.log(node);

                skipChildren();

                return {
        type: 'html',
        //content: '<figure><img src="'+destination+'"></figure>'
        content: '<span><img src="'+destination+'"></span>'
      };

                /*return [
                
                {
                                type: 'openTag',
                                tagName: 'img',
                                selfClose: true,
                                attributes: {
                                src: destination,
                                alt: getChildrenText(node)+"kokoko",
                                title: "zhopa"}
                }
                
            ];*/

                return [
                { type: 'openTag', tagName: 'figure'},
                {
                                type: 'openTag',
                                tagName: 'img',
                                selfClose: true,
                                attributes: {
                                src: destination,
                                alt: getChildrenText(node)+"kokoko",
                                title: "zhopa"}
                }
                ,
                //{ type: 'openTag', tagName: 'figcaption' },
                //{ type: 'text', content: node.title },
                //{ type: 'closeTag', tagName: 'figcaption' },
                { type: 'closeTag', tagName: 'figure' }
            ];

            
            }
           
        }

    });


   /* editor.on('change',function(e){
        document.querySelectorAll('div.te-preview pre').forEach(block => {hljs.highlightElement(block);});
    });*/ 

    hljs.highlightAll();  

});
</script>