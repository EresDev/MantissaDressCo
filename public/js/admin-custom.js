$(document).ready(function(){
   $('input[type=text], input[type=password], select').addClass('form-control');
});

function showError(_msg, _obj){

    if(_msg == 'empty'){
        _msg = "This field is required and can't be empty.";
    }
    if(_msg == "int"){

        _msg = "This field must be an integer.";
    }
    if(_msg == "float"){

        _msg = "This field must be an numeric value with or without decimal points.";
    }
    if($(_obj).parent().hasClass("input-group")){
        $(_obj).parent().parent().append('<div class="alert alert-danger">'+_msg+'</div>');
    }
    else{
        $(_obj).parent().append('<div class="alert alert-danger">'+_msg+'</div>');
    }

    $(_obj).focus();

    $('html, body').animate({
        scrollTop: $(".alert-danger").eq(0).offset().top-200
    }, 500);
}
function removeErrors(){
    $(".alert-danger").remove();

}