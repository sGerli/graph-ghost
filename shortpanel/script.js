function printFlash(message){
    var flash = document.getElementById('flash')
    flash.innerHTML = "<h2>"+ message + "</h2><br><h3>Click to dismiss</h3>"
    flash.className = "flash"
}

function closeFlash(){
    var flash = document.getElementById('flash')
    flash.className = "hidden"
}

function edit(short){
    document.getElementById("editField").value = short
    document.getElementById("editForm").submit()
}