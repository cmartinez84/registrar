$(function(){

    $("#select_all").change(function(){  //"select all" change
        $(".checkbox input").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

//".checkbox" change
    $('.checkbox input').change(function(){
    //uncheck "select all", if one of the listed checkbox item is unchecked
        if(false == $(this).prop("checked")){ //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.checkbox input:checked').length == $('.checkbox input').length-1 ){
            $("#select_all").prop('checked', true);
        }
    });
});
