function RegisterformValidation(){
	var first_name = document.getElementById('first_name').value;
	var last_name = document.getElementById('last_name').value;
	var email = document.getElementById('email').value;
	var password = document.getElementById('password').value;
	var c_password = document.getElementById('c_password').value;
	var username = document.getElementById('username');
	var phone = document.getElementById('phone');
	if(first_name == ""){
		alert("First Name field must be filled")
		return false;
	}else if(last_name == ""){
		alert("Last Name field must be filled")
		return false;
	}else if(email == ""){
		alert("Email field must be filled")
		return false;
	}else if(password != c_password){
		alert("Password does not match")
		return false;
	}else{
		if(!validateUsername(username)){
			return false
		}
		if(!validatePhone(phone)){
			return false
		}
	}	

	
}

function PasswordRestformValidation(){
	var password = document.getElementById('new_password').value;
	var c_password = document.getElementById('c_new_password').value;
	if(password == "" || c_password == ""){
		alert("All the fields are required")
		return false;
	}else if(password != c_password){
		alert("Password does not match")
		return false;
	}else{
		return true;
	}	
}


function validatePhone(fld) {
    var error = "";
    var stripped = fld.value.replace(/[\(\)\.\-\ ]/g, '');
   if (fld.value == "") {
        error = "You didn't enter a phone number.\n";
        fld.style.background = 'Yellow';
        alert(error);
        return false;
    } else if (isNaN(parseInt(stripped))) {
        error = "The phone number contains illegal characters. Don't include dash (-)\n";
        fld.style.background = 'Yellow';
        alert(error);
        return false;
    } else if (!(stripped.length == 10)) {
        // error = "The phone number is the wrong length. Make sure you included an area code. Don't include dash (-)\n";
        // fld.style.background = 'Yellow';
        // alert(error);
        return true;
    }
    return true;
}

function validateUsername(fld) {
    var error = "";
    var illegalChars = /\W/; // allow letters, numbers, and underscores
    if (fld.value == "") {
        fld.style.background = 'Yellow';
        error = "You didn't enter a username.\n";
        alert(error);
        return false;
    } else if ((fld.value.length < 5) || (fld.value.length > 15)) {
        fld.style.background = 'Yellow';
        error = "The username is the wrong length.\n";
        alert(error);
        return false;
    } else if (illegalChars.test(fld.value)) {
        fld.style.background = 'Yellow';
        error = "The username contains illegal characters.\n";
        alert(error);
        return false;
    } else {
        fld.style.background = 'White';
    }
    return true;
}

function validateUsername(fld) {
    var error = "";
    var illegalChars = /\W/; // allow letters, numbers, and underscores
    if (fld.value == "") {
        fld.style.background = 'Yellow';
        error = "You didn't enter a username.\n";
        alert(error);
        return false;
    } else if ((fld.value.length < 5) || (fld.value.length > 15)) {
        fld.style.background = 'Yellow';
        error = "The username is the wrong length.\n";
        alert(error);
        return false;
    } else if (illegalChars.test(fld.value)) {
        fld.style.background = 'Yellow';
        error = "The username contains illegal characters.\n";
        alert(error);
        return false;
    } else {
        fld.style.background = 'White';
    }
    return true;
}