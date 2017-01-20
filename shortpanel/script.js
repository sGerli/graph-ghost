$(document).ready(function () {
    printLinks()
})
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
//    var content = "<div class='edit-box' id='edit-container'><div class='container'><form method='post' id='newData'><h2>Title</h2><input type='text' name='newTitle'><h2>Image Link </h2><input type='text' name='newImage'><h2>Description</h2><textarea rows='5' name='newDescription'></textarea><input type='hidden' name='oldShort'></h2>Short</h2><input type='text' name='newShort'><h2>Full Link</h2><input type='text' name='newLink'><input type='submit' value='submit'></form><button id='delete-button'>Delete</button><button onclick='cancel()' id='cancel-button'>Cancel</button><button onclick='submit()'>Submit</button></div></div>"
//    $(document.body).append(content)
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

function printLinks() {
    $.get("api/get.php", function (links) {
        for (var i in links) {
            var link = links[i]
            var content = "<div class='link'><div class='card'><img src='" + link.image + "'><div class='card-text'><h1>" + link.title + "</h1><p>" + link.description + "</p></div></div><a href='" + link.link + "' target='_blank'>" + link.link + "</a></div>" + "<div><span onclick=\"popup('" + link.short + "')\">" + link.short + "</span><span class='click-count'>" + link.clicks + "</span><span><img src='edit.svg' onclick=\"edit('" + link.short + "')\"></span></div>"
            $(".links").append(content)
        }
    }, "json")
}