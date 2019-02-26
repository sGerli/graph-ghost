function edit(short, fullLink, title, description, image) {
    document.getElementById("title").value = title
    document.getElementById("image").value = image
    document.getElementById("desc").value = description
    document.getElementById("newLink").value = fullLink
    document.getElementById("newShort").value = short
    document.getElementById("oldShort").value = short
    document.getElementById("editModal").style.display = "block"
}

function cancelEdit() {
    document.getElementById("title").value = ""
    document.getElementById("image").value = ""
    document.getElementById("desc").value = ""
    document.getElementById("newLink").value = ""
    document.getElementById("newShort").value = ""
    document.getElementById("oldShort").value = ""
    document.getElementById("editModal").style.display = "none"
}

function deleteItem(short) {
    document.getElementById("delete").value = short
    document.getElementById("deleteForm").submit()
}

function cancel() {
    var edit = document.getElementById("edit-container")
    edit.parentNode.removeChild(edit)
}

function deleteLink(short) {
    document.getElementById("deleteModalShort").value = short
    document.getElementById("deleteModal").style.display = "block"
}

function cancelDelete() {
    document.getElementById("deleteModalShort").value = ""
    document.getElementById("deleteModal").style.display = "none"
}