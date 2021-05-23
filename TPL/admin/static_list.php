<?php
$st_cnf = &LE::$TPL->static_list;
$st_dep = &LE::$TPL->static_dep;

$st_cnf[] = [
    'mod'=>'highlight.js',
    'pos'=>'top',
    'type'=>'css',
    'link'=>'//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/styles/default.min.css'];

$st_cnf[] = [
    'mod'=>'highlight.js',
    'pos'=>'bottom',
    'type'=>'js',
    'link'=>'//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/highlight.min.js'];

$st_cnf[] = [
    'mod'=>'le_form',
    'type'=>'css',
    'link'=>'/pub/css/le_form.css'];

 $st_cnf[] = [
     'mod'=>'ckeditor5',
    'type'=>'js',
    'link'=>'/pub/js/ckeditor5.js']; 

$st_cnf[] = [
    'mod'=>'codemirror',
    'type'=>'css',
    'link'=>'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.4/codemirror.min.css'];  

$st_cnf[] = [
     'mod'=>'tui-editor',
    'type'=>'css',
    'link'=>'https://uicdn.toast.com/editor/latest/toastui-editor.min.css']; 

$st_cnf[] = [
    'mod'=>'tui-editor',
    'type'=>'js',
    'link'=>'https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js'
    //'link'=>'/pub/js/tui-editor.js'
    
    ];

$st_cnf[] = [
    'mod'=>'jquery',
    'type'=>'js',
    'link'=>'https://code.jquery.com/jquery-3.6.0.slim.min.js'];

$st_cnf[] = [
    'mod'=>'txt_cont',
    'type'=>'css',
    'link'=>'/pub/css/txt_cont.css'];

$st_cnf[] = [
    'mod'=>'embedly',
    'type'=>'js',
    'link'=>'//cdn.embedly.com/widgets/platform.js'];

$st_cnf[] = [
    'mod'=>'le_crud',
    'type'=>'js',
    'link'=>'/pub/js/le_crud.js'];


$st_dep['tui-editor']=['codemirror','oth'];