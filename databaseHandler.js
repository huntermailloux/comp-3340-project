function createTables() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "createTables.php", true);
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