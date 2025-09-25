</div>
</div>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

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
           
            if(response.data.length > 0){
            
                let dateParts = response.data[0].expiry_date.split("-");
                let formattedexpiryDate = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;
                $('#expiryPoints').css('display', 'block');
                $('.expiring-point').html(
                    'Point Expiring Soon: ' + response.data[0].remaining_points + '<br> <h6>Expiring Date:  '+  formattedexpiryDate +'</h6>'
                );
            }
            else{
                
                $('#expiryPoints').css('display', 'none');
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", xhr.responseText);
        }
    });

}

</script>

<script>
    function getTripPrice(value){
        
       $.ajax({
                url: "{{route('getPrice') }}",
                method: "POST",
                data: {
                   value:value,
                   
                    _token: "{{csrf_token()}}"

                },
                success: function(responce) {
                //   alert(responce);
                    $('#trip-price').text(responce);
                    $('#total-price').text(responce);
                    $('#trip-prices').val(responce);
                    
                }
               
            });
    }
</script>
    <script>
        @if (Session::has('message'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-center" 
            }
            toastr.success("{{ session('message') }}");
        @endif

        @if (Session::has('error'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-center" 
            }
            toastr.error("{{ session('error') }}");
        @endif

        @if (Session::has('info'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.info("{{ session('info') }}");
        @endif

        @if (Session::has('warning'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.warning("{{ session('warning') }}");
        @endif
    </script>
<script>
    function changeValue(type) {
        var val = $('#adultvalue').val();
        if (val >= 0) {
            if (type == 'plus') {
                var total = parseInt(val) + 1;
            } else {
                var total = parseInt(val) - 1;
            }
        } else {
            var total = 0;
        }
        $('#adultvalue').val(total);

        var child = $('#childvalue').val();
        var tripprice = $('#trip-prices').val();
        var traveler = parseInt(total) + parseInt(child);

        var total_price = parseInt(tripprice)* parseInt(traveler);

        $('.totalTravel').text(traveler);
        $('#price').val(total_price);
        $('#total-price').text(total_price);
        $('#adult-count').text(total);
        $('#child-count').text(child);



    }

    function changeValueChild(type) {
        var val = $('#childvalue').val();
        if (val >= 0) {
            if (type == 'plus') {
                var total = parseInt(val) + 1;
            } else {
                var total = parseInt(val) - 1;
            }
        } else {
            var total = 0;
        }
        $('#childvalue').val(total);

        var adult = $('#adultvalue').val();
         var tripprice = $('#trip-prices').val();
        var traveler = parseInt(total) + parseInt(adult);
        var total_price = parseInt(tripprice)* parseInt(traveler);

        $('.totalTravel').text(traveler);
        $('#price').val(total_price);
        $('#total-price').text(total_price);
        $('#adult-count').text(adult);
        $('#child-count').text(total);


    }

    function openModal(){
         var adult = $('#adultvalue').val();
         var child = $('#childvalue').val();

         

    }
  
</script>

</body>

</html>