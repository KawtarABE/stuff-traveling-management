// ajax start

// funtion to make server request and verify data
function showMsg(str,num,msg,id) {
    if (str.length == 0) {
        document.getElementById(id).innerHTML = "";
        return;
    }
    else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById(id).innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "validation"+ num + ".php?" + msg + "=" + str, true);
        xmlhttp.send();
    }
}


// funtion to make server request and verify data
function showMsg1(str,str1,num,msg,id) {
    if (str.length == 0) {
        document.getElementById(id).innerHTML = "";
        return;
    }
    else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById(id).innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "validation"+ num + ".php?" + msg + "=" + str + "&id=" + str1, true);
        xmlhttp.send();
    }
}

function getnumericDetails(str,num,msg,id) {
    if(str.length == 0) {
        document.getElementById(id).value = "";
        return;
    }
    else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            let data = this.responseText.slice(33);
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById(id).value = parseFloat(data);
            }
        }
        xmlhttp.open("GET", "validation" + num +".php?" + msg + "=" + str, true);
        xmlhttp.send();
    }
}

function getnumericDetails1(str,str1,num,msg,id) {
    if(str.length == 0) {
        document.getElementById(id).value = "";
        return;
    }
    else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            let data = this.responseText.slice(33);
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById(id).value = parseFloat(data);
            }
        }
        xmlhttp.open("GET", "validation" + num + ".php?" + msg + "=" + str + "&num=" + str1, true);
        xmlhttp.send();
    }
}

function getDetails(str,num,msg,id) {
    if(str.length == 0) {
        document.getElementById(id).value = "";
        return;
    }
    else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            let data = this.responseText.slice(33);
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById(id).value = data;
            }
        }
        xmlhttp.open("GET", "validation" + num +".php?" + msg + "=" + str, true);
        xmlhttp.send();
    }
}


function getDetails1(str,str1,num,msg,id) {
    if(str.length == 0) {
        document.getElementById(id).value = "";
        return;
    }
    else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            let data = this.responseText.slice(33);
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById(id).value = data;
            }
        }
        xmlhttp.open("GET", "validation" + num +".php?" + msg + "=" + str + '&num='+ str1, true);
        xmlhttp.send();
    }
}
// ajax end


// javascript start

    // show message
    function show(x) {
        document.getElementById(x).style.display = "block";
   }

    // hide error messages
    function hide(x) {
        document.getElementById(x).style.display = "none";
    }

// javascript end
