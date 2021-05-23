<?$tpl->need_static(['-tui-editor','jquery','le_form','ckeditor5','highlight.js','txt_cont','le_crud'])?>

<h2>Список статей</h2>
<a href="/admin/blog/edit:0">Создать</a>


<?foreach($cont_list as $id=>$cont):?>
<div href="/admin/blog/edit:<?=$id?>" class="el_cont" id="el_<?=$id?>">
<h3><a href="/admin/blog/edit:<?=$id?>"><?=empty($cont['head'])?"Статья ".$id: $cont['head']?></a>
<button style="" onclick="le_crud.rem(<?=$id?>,'#el_<?=$id?>')">Удалить</button>
</h3>

<div class="el_cont_content txt_cont">
<?=$cont['html']?>
</div>
</div>
<?endforeach;?>


<style>
.el_cont {
    text-decoration:none;
    display:block;
    color:inherit;
    border:1px solid #d0d0d0;
    margin: 20px;
    padding: 20px;

}

.el_cont:hover {background:#fafafa;}

.el_cont_content {border: 2px dotted #d9d9d9;padding:10px;}

</style>


<script>
window.addEventListener('load', (event) => 
{    

document.querySelectorAll('pre code').forEach((block) => {
    hljs.highlightElement(block);
  }); 
});

</script>