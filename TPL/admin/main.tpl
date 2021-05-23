<!DOCTYPE html>
<html lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">	
	<title><?=$tpl->meta['title'];?></title>
    <meta name="description"    content="<?=$tpl->meta['description'];?>">
	<meta name="keywords"       content="<?=$tpl->meta['keywords'];?>">
	<meta name="viewport"       content="width=device-width,initial-scale=1">
    <?=$tpl->head_cont;?>
</head>
<body>
<?=$tpl->cont_top;?>
<?=$tpl->fetch('main_body');?>
<?=$tpl->cont_bottom;?>
</body>
</html>