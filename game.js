const log = document.getElementById("log");
const input = document.getElementById("input");

let history = [];
let historyPos = -1;


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
if(sessionStorage.getItem("history") != null && sessionStorage.getItem("history").length > 0) {
    history = JSON.parse(sessionStorage.getItem("history"));
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


input.onkeydown = async function (event) {
	if (event.keyCode == 13 && input.value.trim() != "") {
        input.value = input.value.trim();

        if (input.value == "clear")
            overrideLog("");
        else if (input.value == "exit") {
			sessionStorage.setItem("log", log.innerHTML);
            sessionStorage.setItem("history", JSON.stringify(history));
            performAction();
            window.location.href = "/index.html";
		}
        else {
          logAdd(">" + input.value);
          logAdd(await performAction());
        }
        history.unshift(input.value);
		historyPos = -1;
        input.value = "";
	}
	else if(event.keyCode == 38 && historyPos < history.length - 1)
		    input.value = history[++historyPos];
	else if (event.keyCode == 40) {
        if(historyPos <= 0) {
            input.value = "";
            historyPos = -1;
        }
        else
            input.value = history[--historyPos];
    }
}

function performAction() {
    return fetch("https://game.spidermilk.ddnsfree.com", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            jwt: jwt,
            action: input.value
        })
    }).then(response => {
        return response.json();
    }).then(data => {
        return data.responseText;
    }).catch(error => {
        return "An error appeared while trying to communicate with the server. " + error;
    });
}