<div id="auth_form">
    <form class="auth_form" action="<?=$act_url?>" method="POST">
        <label>Логин<input type="text" id="login_field" name="login"></label>
        <label>Пароль<input type="password" name="password"></label>
        <button class="btn" type="submit">Войти</button>
        <input type="hidden" name="login_ok" value="yes" />
        <?if(isset($err)):?>
        <span class="login_err"><?=$err?></span>
        <?endif;?>
    </form>
</div>


<style>
#auth_form {position:relative;margin:0;padding:0;}
form.auth_form {
width: 300px;
margin: 50px 0 50px -165px;
left: 50%;
position: relative;
font-size: 14px;
overflow: hidden;
border: 1px solid #d0d0d0;
padding: 15px;
box-shadow: 0 0 5px 2px #ccc;
font-family:sans-serif;
}

.auth_form label {
    display:block;
    margin-bottom:15px;
}

.auth_form input
{
	margin-top:3px;
    height: 35px;
	padding:5px;
	line-height: 40px;
	width: 100%;
	font-size:16px;
	border: 1px solid #ccc;
	box-sizing: border-box;
}

.auth_form input:focus {outline: 1px solid #b9d1eb;}



.auth_form .btn {
    text-align: center;
    height: 40px;
    font-size: 16px;
    min-width: 200px;
    display: block;
    margin: 0 auto;
    cursor: pointer;
}
.auth_form .login_err {
    font-size:90%;
    padding-top:10px;
    display:block;
    text-align: center;
    color: #ff4800;
}
</style>

<script>document.getElementById("login_field").focus();</script>