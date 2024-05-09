$(document).ready(function() {

    // s2 - select2 (combobox)


    //s2 discipliny
    $('.select_disc').select2({
        dropdownAutoWidth : true,
        placeholder: "-- Výběr disciplín --"
    });


     //s2 opakovani, kola, sport
     $('.select_opak, .select_kolo, .select_sport, .select_pohlavi, .custom-select').select2({
        dropdownAutoWidth: true,
    });

    //s2 uzivatel
    $('.select_uziv').select2({
        dropdownAutoWidth : true,
        placeholder: "-- Výběr sportovců --"
    });








    //switch u akce
    var vypisA = $("#vypis_a");
    var editA = $("#edit_a");
    editA.css("display", "none");

    $("#switch").click(function() {
        if (vypisA.css("display") === "block") {
            vypisA.css("display", "none");
            editA.css("display", "block");
        } else {
            vypisA.css("display", "block");
            editA.css("display", "none");
        }
    });

    //switch u soupisky
    var vypisS = $("#vypis_s");
    var editS = $("#edit_s");
    editS.css("display", "none");

    $("#switch").click(function() {
        if (vypisS.css("display") === "block") {
            vypisS.css("display", "none");
            editS.css("display", "block");
        } else {
            vypisS.css("display", "block");
            editS.css("display", "none");
        }
    });



    
});