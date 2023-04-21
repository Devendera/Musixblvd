function validateForm(e){

    //$('#advance_form').on('submit', function (e) {
console.log(e);
    var error_free=true;
    if($('input[name=legal]:checked').length==0)
    {
       error_free=false;
       $('.errorMessage').html('Please Select One Option');
    }
    else if($('input[name=is_decisionmaker]:checked').length==0)
    {
        error_free=false;
        $('.errorMessage').text('Please Select sole decision maker');
    }
    else if($('input[name=is_musicprimary]:checked').length==0)
    {
        error_free=false;
        $('.errorMessage').text('Please Select music industry');
    }
    else if($('input[name=is_entertainmentprimary]:checked').length==0)
    {
        error_free=false;
        $('.errorMessage').text('Please Select One Option');
    }
    else if($('input[name=amount_period]:checked').length==0)
    {
        error_free=false;
        $('.errorMessage').text('Please Select One Option for royalties');
    }
    else if($('period').val()=="")
    {
        error_free=false;
        $('.errorMessage').text('Please Enter Years');
    }
    

    if (!error_free){
        return false;
    }
    else{
        $('.errorMessage').text('');
    }
   // });
 }