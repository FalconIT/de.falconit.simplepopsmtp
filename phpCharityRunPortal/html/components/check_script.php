<script>
function check_mail() {
	var email1 = document.getElementById('email');
	var email2 = document.getElementById('email2');
	if(email1.value != email2.value) {
		email2.setCustomValidity('E-Mail-Adressen sind unterschiedlich!');
	}else{
		email2.setCustomValidity('');
	}
}

function check_pass(input) {
	var pass1 = document.getElementById('pass');
	var pass2 = document.getElementById('pass2');
	if(pass1.value != pass2.value) {
		pass2.setCustomValidity('Passwörter sind unterschiedlich!');
	}else{
		pass2.setCustomValidity('');
	}
}
</script>