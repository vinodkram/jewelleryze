<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{__('Pay with paypal')}}</title>
    <link rel="stylesheet" href="{{ asset('user/css/bootstrap.min.css') }}">
    <script src="{{ asset('user/js/jquery-3.7.0.min.js') }}"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">

                <form action="{{ route('user.checkout.pay-with-paypal-from-react') }}" id="paypalForm">
                    <input type="hidden" name="billing_address_id" value="{{ $billing_address_id }}">
                    <input type="hidden" name="shipping_address_id" value="{{ $shipping_address_id }}">
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="shipping_method_id" value="{{ $shipping_method_id }}">
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $("#paypalForm").submit();
        });

    </script>
</body>
</html>

