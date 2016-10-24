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

function deleteItem(short){
    document.getElementById("delete").value = short
    document.getElementById("deleteForm").submit()
}

function cancel(){
    var edit = document.getElementById("edit-container")
    edit.parentNode.removeChild(edit)
}

function submit(){
    document.getElementById("newData").submit()
}