const log = document.getElementById("log");

let inputLog = [];
let inputLogPos = 0;

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
