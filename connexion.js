let regexmail = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/; /*RFC 5322 Official Standard*/
let motDePasse = document.getElementById("pwd");
let email = document.getElementById("email");
const ok = '<img src="images/checked.png" alt="Ok" style="width:12px;height:12px;">';
const nok = '<img src="images/cancel.png" alt="Nok" style="width:12px;height:12px;">';

function doStateMailCheck() {
	doStateCheck(doCheckEmail(), "stateEmail");
}

function doStatePasswordCheck() {
	doStateCheck(doCheckPwd(), "statePassword");
}

function doStateCheck(fonction, element) {
    if (fonction) {
        document.getElementById(element).innerHTML = ok;
    } else {
        document.getElementById(element).innerHTML = nok;
    }
}

function doCheckEmail() {
    return regexmail.test(email.value);
}

function doCheckPwd() {
    return motDePasse.value.length > 0;
}

function doConnectCheck() {
	let allTips = document.getElementsByClassName("tip");
    for (let i = 0; i < allTips.length; i++) {
        allTips.item(i).innerHTML = '';
    }
    if (!(doCheckEmail() && doCheckPwd())) {
        showMistakes(); event.preventDefault();
    }
}

function showMistakes() {
    if (!doCheckEmail()) {
        document.getElementById('tip-email').innerHTML = 'Veuillez entrer une adresse e-mail correcte';
    }
    if (!doCheckPwd()) {
        document.getElementById('tip-pwd').innerHTML = 'Veuillez indiquer votre mot de passe';
    }
}