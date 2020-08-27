function validateForm() {
    var x = document.forms["loginForm"]["username"].value;
    var y = document.forms["loginForm"]["password"].value;
    var z = document.forms["loginForm"]["confirmPassword"].value;
    var e = document.forms["loginForm"]["email"].value;

    if (x == "" || y =="" || z == "" || e == "") {
        alert("Please fill all fields!");
        return false;
    }
}

function validateForm2() {
    var x = document.forms["loginForm"]["username"].value;
    var y = document.forms["loginForm"]["password"].value;

    if (x == "" || y =="") {
        alert("Username/password is empty!");
        return false;
    }
}
