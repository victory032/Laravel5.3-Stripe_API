<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Purchase;

class PagesController extends Controller
{
    public function getOrder($amount = null)
    {

        $amount_input = [
            'class'                         => 'form-control',
            'required'                      => 'required',
            'data-stripe'                   => 'number',
            'data-parsley-type'             => 'number',
            'maxlength'                     => '16',
            'data-parsley-trigger'          => 'change focusout',
            'data-parsley-class-handler'    => '#cc-group',
            'name'                          => 'amount',
        ];

        if($amount) {
            $amount_input['readonly'] = 'readonly';
        }

    	return view('pages.order')
                ->with('amount', $amount)
                ->with('amount_input',$amount_input);	
    }


    public function postOrder(Request $request)
    {
    	
        $validator = \Validator::make(\Input::all(), [
            'first_name' => 'required|string|min:2|max:32',
            'email' => 'required|email',
            'amount' => 'required|numeric',
            'address' => 'required|string|min:2|max:32',
            'zip' =>'required|numeric',
            'city' =>'required|string|min:2|max:32',
            'country' => 'required|string|min:2|max:32'

        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        
        $product = 'Payment';
        $token = $request->input('stripeToken');
        $first_name = $request->input('first_name');
        $address = $request->input('address');
        $addressTwo = $request->input('addressTwo');
        $city = $request->input('city');
        $zipCode = $request->input('zip');
        $state = $request->input('state');
       /* $country = $request->input('country');*/
         $email = $request->input('email');
        $amount = $request->input('amount');
        $emailCheck = User::where('email', $email)->value('email');
        $description = $request->input('description');

        \Stripe\Stripe::setApiKey(env('STRIPE_SK'));

        
        if (!isset($emailCheck)) {
            
            try {
                $customer = \Stripe\Customer::create([
                'source' => $token,
                'email' => $email,
                'metadata' => [
                    "First Name" => $first_name,
                    "Address" => $address,
                    "AddressTwo" => $addressTwo,
                    "City" => $city,
                    "State" => $state,
                    "Country" => $country,
                    "Amount" => $amount,
                    "Description" => $description

                ]

                ]);

            } catch (\Stripe\Error\Card $e) {
                return redirect()->route('order')
                    ->withErrors($e->getMessage())
                    ->withInput();
            }

            $customerID = $customer->id;

         
            $user = User::create([
                'first_name' => $first_name,
                'email' => $email,
                'stripe_customer_id' => $customerID,
                'description' => $description
            ]);
        } else {
            $customerID = User::where('email', $email)->value('stripe_customer_id');
            $user = User::where('email', $email)->first();
        }

        
        try {
            $charge = \Stripe\Charge::create([
                'amount' => $amount*100,
                'currency' => 'usd',
                'description' => $description,
                'customer' => $customerID,
                'metadata' => [
                'product_name' => $product,
                ]]);
                
        } catch (\Stripe\Error\Card $e) {
            return redirect()->route('order')
                ->withErrors($e->getMessage())
                ->withInput();
        }

        // rec in the database
        Purchase::create([
            'user_id' => $user->id,
            'product' => $product,
            'amount' => $amount,
            'description' => $description,
                'stripe_transaction_id' => $charge->id,
        ]);

        return redirect()->route('order')

            ->with('successful', 'The pay was successful!');

    }

}



