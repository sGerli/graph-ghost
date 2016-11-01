document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("flash").addEventListener("click", function () {
        closeFlash()
    })
})

function printFlash(message) {
    var flash = document.getElementById('flash')
    flash.innerHTML = "<h2>" + message + "</h2><br><h3>Click to dismiss</h3>"
    flash.className = "flash"
}

function closeFlash() {
    var flash = document.getElementById('flash')
    flash.parentNode.removeChild(flash)
}

function popup(link) {
    var host = location.protocol + "//" + location.hostname + "/"
    prompt("Copy the selected link", host + link);
}

function edit(short) {
    document.getElementById("editField").value = short
    document.getElementById("editForm").submit()
}

function deleteItem(short) {
    document.getElementById("delete").value = short
    document.getElementById("deleteForm").submit()
}

function cancel() {
    var edit = document.getElementById("edit-container")
    edit.parentNode.removeChild(edit)
}

function submit() {
    document.getElementById("newData").submit()
}
$(document).ready(function () {
    printLinks()
})

function printLinks() {
    $.get("api/get.php", function (links) {
        for (var i in links) {
            var link = links[i]
            var content = "<div class='link'><div class='card'> <img src='" + link.image + "'><div class='card-text'><h1>" + link.title + "</h1><p>" + link.description + "</p></div></div><a href='" + link.link + "' target='_blank'>" + link.link + "</a></div>" + "<div><span onclick=\"popup('" + link.short + "')\">" + link.short + "</span><img src='edit.svg' onclick=\"edit('" + link.short + "')\"></div>"
            $(".links").append(content)
        }
    }, "json")
}

function getDataEntry(short) {
    $.get("api/get.php", {
        id: short
    }, function (link) {
        console.log(link)
        return link
    }, "json")
}