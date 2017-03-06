function checkEmail()
{
	var regex = /^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/g;
	var text = document.getElementById('email').value;
	if (text.search(regex) == -1)
	{	
		document.getElementById('error').innerHTML = '<div class="alert alert-danger\" id=\"error\">Неверный формат email</div>';
		return false;
	}
	else
	{
		document.getElementById("error").innerHTML = '';
		return true;
	}
}

function checkPassword()
{
	var text = document.getElementById('pas').value;
	if (text.length < 8)
	{
		document.getElementById('error').innerHTML = '<div class="alert alert-danger\" id=\"error\">Короткий пароль!</div>';
		return false;
	}
	else
	{
		document.getElementById("error").innerHTML = '';
		return true;
	}
}

function checkData()
{
	if ((checkEmail() == true) && (checkPassword() == true))
		document.getElementById("form").submit();
	else
		alert("Некорректный ввод!");
}


