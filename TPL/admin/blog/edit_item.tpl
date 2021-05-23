<?$tpl->need_static(['-tui-editor','jquery','le_form','ckeditor5','highlight.js','txt_cont'])?>
<a href="/admin/blog">Назад к списку</a>
<h2>Редактор статьи</h2>

<div id="editor" class="txt_cont">
<h1><?=$head?></h1>
<?=$html_cont?>
</div>


<button onclick="save_cont();">Сохранить</button>

<div id="result_cont" class="txt_cont">
load
</div>



<script>
editor = false;

function save_cont()
{
    cont_html = editor.getData();
    //cont_body = cont_html.getTitle();
	//alert(cont_body);
    const formData = new FormData();
    formData.append('clear', 'yes');
    formData.append('ajax', 'yes');
    formData.append('mod', 'save_content');
    formData.append('data[html_cont]', cont_html);
    formData.append('data[id]', <?=$id?>);

     fetch('/admin/blog', {method: 'POST',body: formData}).then((resp)=>{return resp.json()}).then(
		 (resp)=>{
			 if (!resp.success) return false;
			 window.location.href="/admin/blog/";
		 }
	 );

	//alert(response);
	//console.log(response);


    
}



window.addEventListener('load', (event) => 
{    
var options = {
				
				toolbar: {
					items: [
						'bold',
						'italic',
						'underline',
						'strikethrough',
						'link',
						'subscript',
						'superscript',
						'-',
						'code',
						'fontColor',
						'removeFormat',
						'undo',
						'redo'
					],
					shouldNotGroupWhenFull: true
				},
				language: 'ru',
				blockToolbar: [
					'codeBlock',
					'horizontalLine',
					'htmlEmbed',
					'imageUpload',
					'imageInsert',
					'indent',
					'outdent',
					'numberedList',
					'bulletedList',
					'blockQuote',
					'mediaEmbed',
					'insertTable',
					'alignment'
				],
				image: {
					toolbar: [
						'imageTextAlternative',
						'imageStyle:full',
						'imageStyle:side',
						'linkImage'
					]
				},
				table: {
					contentToolbar: [
						'tableColumn',
						'tableRow',
						'mergeTableCells',
						'tableCellProperties',
						'tableProperties'
					]
				},
				licenseKey: '',
                mediaEmbed: {previewsInData:true},
                simpleUpload: {uploadUrl: '/admin/blog'}
				
				
			};




		BalloonBlockEditor
			.create( document.querySelector( '#editor' ), options )
			.then( editor => {
				window.editor = editor;
	    		document.querySelector( '#result_cont' ).innerHTML=editor.getData();


				editor.model.document.on( 'change:data', () => 
				{
	    			console.log( 'The data has changed!' );
	    			document.querySelector( '#result_cont' ).innerHTML=editor.getData();
                    
				} 
				);

		
				
				
				
		
				
				
				
			} )
			.catch( error => {
				console.error( 'Oops, something went wrong!' );
				console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
				console.warn( 'Build id: 9v07c6uegbjg-a3pt58397xor' );
				console.error( error );
			} );    




});



</script>

<style>
		#editor,
        .txt_cont {
			border: 1px solid #d0d0d0;
			padding: 20px 30px;
			margin-top:40px;
		}
</style>