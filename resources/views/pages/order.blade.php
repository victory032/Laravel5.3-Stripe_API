@extends('app')

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-1">
        <h1 class="text-success" style="text-align: center;">Payment</h1>
    </div>
</div>
<style>



  .alert {
    
    margin-top: 15px;
    color: black;
   line-height: 2.0px;

  }

</style>

<div class="row">
  <div class="col-md-4 col-md-offset-2">
    {!! Form::open(['url' => route('order-post'), 'data-parsley-validate', 'id' => 'payment-form']) !!}

  
      <div class="form-group">
        {!! Form::label(null, 'Amount: (USD)') !!}
        {!! Form::text(null, $amount, $amount_input) !!}
      </div>



        <div class="form-group" id="description-group">
            {!! Form::label('description', 'Payment Reference:') !!}
            {!! Form::text('description', null, [
              'class'                         => 'form-control',
              'required'                      => 'required',
              'data-parsley-required-message' => 'Descriptionis required',
              'data-stripe'                   => 'text',
              'data-parsley-type'             => 'text',
              'maxlength'                     => '250',
              'data-parsley-trigger'          => 'change focusout',
              'data-parsley-class-handler'    => '#description-group'
              ]) !!}

      <div class="form-group" id="cc-group">
            {!! Form::label(null, 'Credit card number:') !!}
            {!! Form::text(null, null, [
              'class'                         => 'form-control',
              'required'                      => 'required',
              'data-parsley-required-message' => 'Credit card number is required',
              'data-stripe'                   => 'number',
              'data-parsley-type'             => 'number',
              'maxlength'                     => '16',
              'data-parsley-trigger'          => 'change focusout',
              'data-parsley-class-handler'    => '#cc-group'
              ]) !!}


         <div class="form-group" id="ccv-group">
          {!! Form::label(null, 'CVC:') !!}
          {!! Form::text(null, null, [
              'class'                         => 'form-control',
              'required'                      => 'required',
              'data-parsley-required-message' => 'CVC is required',
              'data-stripe'                   => 'cvc',
              'data-parsley-type'             => 'number',
              'data-parsley-trigger'          => 'change focusout',
              'maxlength'                     => '4',
              'data-parsley-class-handler'    => '#ccv-group'
              ]) !!}
      </div>

       <div class="row">
        <div class="col-md-4">
          <div class="form-group" id="exp-m-group">
              {!! Form::label(null, 'Ex. Month') !!}
              {!! Form::selectMonth(null, null, [
                  'class'                 => 'form-control',
                  'required'              => 'required',
                  'data-parsley-required-message' => 'exm-m is required',
                  'data-stripe'           => 'exp-month'
              ], '%m') !!}
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group" id="exp-y-group">
              {!! Form::label(null, 'Ex. Year') !!}
              {!! Form::selectYear(null, date('Y'), date('Y') + 10, null, [
                  'class'             => 'form-control',
                  'required'          => 'required',
                  'data-parsley-required-message' => 'exm-y is required',
                  'data-stripe'       => 'exp-year'
                  ]) !!}
          </div>
        </div>
      </div>


       <div class="form-group" id="first-name-group">
          {!! Form::label('first_name', ' Cardholer name:') !!}
          {!! Form::text('first_name', null, [
              'class'                         => 'form-control',
              'required'                      => 'required',
              'data-parsley-required-message' => 'Cardholername is required',
              'data-parsley-trigger'          => 'change focusout',
              'data-parsley-pattern'          => '/^[a-zA-Z]*$/',
              'data-parsley-minlength'        => '2',
              'data-parsley-maxlength'        => '32',
              'data-parsley-class-handler'    => '#first-name-group'
              ]) !!}
      </div>


       <div class="form-group" id="address-group">
          {!! Form::label('address', ' Address:') !!}
          {!! Form::text('address', null, [
              'class'                         => 'form-control',
              'required'                      => 'required',
              'data-parsley-required-message' => 'Address is required',
              'data-parsley-trigger'          => 'change focusout',
              'data-parsley-pattern'          => '/^[a-zA-Z]*$/',
              'data-parsley-minlength'        => '2',
              'data-parsley-maxlength'        => '32',
              'data-parsley-class-handler'    => '#address-group'
              ]) !!}
      </div>

       <div class="form-group" id="addressTwo-group">
          {!! Form::label('addressTwo', ' Address (line 2):') !!}
          {!! Form::text('addressTwo', null, [
              'class'                         => 'form-control',
              'data-parsley-trigger'          => 'change focusout',
              'data-parsley-pattern'          => '/^[a-zA-Z]*$/',
              'data-parsley-minlength'        => '2',
              'data-parsley-maxlength'        => '32',
              'data-parsley-class-handler'    => '#addressTwo-group'
              ]) !!}
      </div>

      <div class="form-group" id="city-group">
          {!! Form::label('city', ' City:') !!}
          {!! Form::text('city', null, [
              'class'                         => 'form-control',
              'required'                      => 'required',
              'data-parsley-required-message' => 'City required',
              'data-parsley-trigger'          => 'change focusout',
              'data-parsley-pattern'          => '/^[a-zA-Z]*$/',
              'data-parsley-minlength'        => '2',
              'data-parsley-maxlength'        => '32',
              'data-parsley-class-handler'    => '#city-group'
              ]) !!}
      </div
>
      <div class="form-group" id="zipCode-group">
          {!! Form::label('zip', ' Zip:') !!}
          {!! Form::number('zip', null, [
               'class'                         => 'form-control',
              'required'                      => 'required',
              'data-parsley-required-message' => 'Zip required',
              'data-parsley-trigger'          => 'change focusout',
              'data-parsley-pattern'          => '/^[0-9]*$/',
              'data-parsley-minlength'        => '2',
              'data-parsley-maxlength'        => '32',
              'data-parsley-class-handler'    => '#city-group'
              ]) !!}
      </div>




      <div class="form-group" id="state-group">
          {!! Form::label('state', ' State/Province:') !!}
          {!! Form::text('state', null, [
              'class'                         => 'form-control',
              'data-parsley-trigger'          => 'change focusout',
              'data-parsley-pattern'          => '/^[a-zA-Z]*$/',
              'data-parsley-minlength'        => '2',
              'data-parsley-maxlength'        => '32',
              'data-parsley-class-handler'    => '#state-group'
              ]) !!}
      </div>



       <!--  <div class="form-group" id="country-group">

             {!! Form::label('country', ' Country:') !!}
             <br>
             {!! Form::select('country', ['Singapore' => 'Singapore']) !!};

         </div> -->



      <div class="form-group" id="country-group">
          {!! Form::label('country', ' Country:') !!}
          {!! Form::text('country', null, [
              'class'                         => 'form-control',
              'required'                      => 'required',
              'data-parsley-required-message' => 'Country is required',
              'data-parsley-trigger'          => 'change focusout',
              'data-parsley-pattern'          => '/^[a-zA-Z]*$/',
              'data-parsley-minlength'        => '2',
              'data-parsley-maxlength'        => '32',
              'data-parsley-class-handler'    => '#country-group'
              ]) !!}

      </div>

      <div class="form-group" id="email-group">
          {!! Form::label('email', 'Email address:') !!}
          {!! Form::email('email', null, [
              'class'                         => 'form-control',
              'placeholder'                   => 'email@example.com',
              'required'                      => 'required',
              'data-parsley-required-message' => 'Email name is required',
              'data-parsley-trigger'          => 'change focusout',
              'data-parsley-class-handler'    => '#email-group'
              ]) !!}
      </div>

       <div class=class="btn btn-default" role="button" >
            {!! Form::submit('Pay', ['class' => 'btn btn-success btn-order', 'id' => 'submitBtn', 'style' => 'margin-top: 20px;']) !!}

            
        </div>
        
    {!! Form::close() !!}
    <div>

@if(Session::has('successful'))
         <div class="alert alert-success">
            {!! Session::get('successful') !!}
        </div>
</div>

    <a href="/payment" class="btn btn-warning" role="button" >Cancel</a>

    </div>
  </div>

</div>

    



@endif



@endsection