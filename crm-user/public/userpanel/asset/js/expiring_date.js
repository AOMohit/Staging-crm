
$(document).ready(function() {
    expiringDate();
});

function expiringDate(){
    $.ajax({
        url:"getExpiryDate",
        type:"get",
        data:{
            _token:"{{csrf_token()}}"
        },
        success:function(response){
            if(response)
            {
                let dateParts = response.data.expiry_date.split("-");
                let formattedexpiryDate = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;
           
                $('.expiring-point').html(
                    '<strong>Point Expiring Soon: ' + response.data.totalpoint + '</strong><br> Expiring Date:  '+ formattedexpiryDate
                );
            }
            else{

                $('#expiryPoints').hide();
            }

           
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", xhr.responseText);
        }
    });

}
