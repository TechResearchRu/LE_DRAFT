<?$tpl->need_static(['-tui-editor','jquery','le_form','ckeditor5','highlight.js'])?>

<h2>Список статей</h2>


<?foreach($cont_list as $id=>$cont):?>
<a href="/editor_test/edit:<?=$id?>" class="el_cont">
<h3>Статья <?=$id?></h3>
<div class="el_cont_content">
<?=$cont['html']?>
</div>
</a>
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