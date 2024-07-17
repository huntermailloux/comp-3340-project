function createTables() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "phpScripts/createTables.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = xhr.responseText;
            alert("Success!" + response);
            location.reload();
        }
    };
    xhr.send("createTables=true");
}

// ******************************************** //
//               User Table                     //
// ******************************************** //
function createUser() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "phpScripts/createUser.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = xhr.responseText;
            alert("User created.");
            location.reload();
        } else {

        }
    }

}

function updateUser() {}

function pullUser() {

}

function deleteUser() {}

// ******************************************** //
//               Posts Table                    //
// ******************************************** //
function createPost() {}

function updatePost() {}

function pullPost() {}

function deletePost() {}

// ******************************************** //
//             Comments Table                   //
// ******************************************** //
function createComment() {}

function updateComment() {}

function pullComment() {}

function deleteComment() {}

// ******************************************** //
//        UserCommunications Table              //
// ******************************************** //
function createCommunication() {}

function pullCommunication() {}
