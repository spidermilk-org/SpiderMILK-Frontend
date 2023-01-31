const log = document.getElementById("log");
let history = [];
let historyPos = 0;


const jwt = getJWT();
if(jwt == null || jwtExpired(jwt))
    window.location.href = "/login.php";

function getJWT() {
    const cookieArray = document.cookie.split(';');
    for(let i = 0; i < cookieArray.length; i++) {
        const cookie = cookieArray[i].trim();
        if(cookie.startsWith('sm_jwt_login='))
            return cookie.substring(13);
    }
    return null;
}

function jwtExpired(jwt) {
    const payload = JSON.parse(atob(jwt.split('.')[1]));
    const now = new Date().getTime() / 1000;
    return now > payload.exp;
}


if(sessionStorage.getItem("log") != null && sessionStorage.getItem("log").length > 0) {
	overrideLog(sessionStorage.getItem("log"));
}
function logAdd(text) {
	if (log.innerHTML == "")
		overrideLog(text);
    else {
        log.innerHTML += "<br />" + text;
        log.scrollTop = log.scrollHeight;
    }
}
function overrideLog(text) {
    log.innerHTML = text;
}
