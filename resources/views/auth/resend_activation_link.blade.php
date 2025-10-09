@extends('layouts.trader-auth')
@section('title','Signup Success')
@section('content')
<style>

    
.cbutton {
    border: 1px solid !important;
    border-radius: 8px;
    display: inline-block;
    padding: 20px;
    width: 200px;
    text-decoration: none;
}
.pbutton {
    border: 1px solid!important;
    border-radius: 8px;
    display: inline-block;
    padding: 20px;
    width: 200px;
}

.icon {
    font-size: 65px !important;
}

</style>


 
        <div class="row">
     
          <div class="col-lg-12 my-auto text-center">
                
                <center>
                    <img src="{{ get_user_logo() }}" alt="{{ config('app.name') }}" class="img-fluid" height="100"  style="max-width:100%">
                    <br><br>
                </center>
   
                <center>
                    <div class="ptext">
                        <span class="text-primary">Congratulations !!! </span>
                        
                        We Have Successfully sent Your {{ config('app.name') }}  Account activation link through your email {email}
                    </div>
                    <br><br>
                </center>

           
          </div>
    


@stop
@section('page-js')
<!-- BEGIN: Page JS-->

<!-- END: Page JS-->

@stop