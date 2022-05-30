////check token availability and redirect
if(!validateCurrentToken(userToken)){
    window.location.href = "./index.html";
    Cookies.remove('__scman_us_t');
}

////start
$(document).ready(function() {
    //noteRm Modal confirm rm button event
    $(document).on("click","#confimNoteDelete", function () {
        deleteNoteFromtaskConfirm();
    });

    //noteEdit Modal confirm confirm button event
    $(document).on("click","#editNoteNoteConfirm", function () {
        editNoteConfirm();
    });

    //noteAddNew Modal confirmbutton event
    $(document).on("click","#addNoteConfirm", function () {
        writeNewNoteForTask($('#offcanvasNotes').attr('taskID'));
    });
});