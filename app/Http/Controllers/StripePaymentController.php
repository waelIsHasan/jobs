<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

class StripePaymentController extends Controller
{
    
    public function stripePost(){
    
      $stripe = new \Stripe\StripeClient('sk_test_Gx4mWEgHtCMr4DYMUIqfIrsz');
       $tokenCard = $stripe->tokens->create([
          'card' => [
            'number' => '6205500000000000004',
            'exp_month' => '5',
            'exp_year' => '2026',
            'cvc' => '314',
          ],
        ]);
        $charge = $stripe->charges->create([
                'amount' => 1099,
                'currency' => 'usd',
                'source' => $tokenCard->id,
                ]);
                
        return response()->json(['msg' => true , 'date' => ['charge' => $charge , 'card_token' => $tokenCard]]);
}
  public function index(){
    return view('index');

  }

  public function checkout(){
// Set your secret key. Remember to switch to your live secret key in production.
// See your keys here: https://dashboard.stripe.com/apikeys
          $stripe = new \Stripe\StripeClient('sk_test_51PsgqTALREbdsj7FcImWFnWpyjXRtScZijLDUSfmusvaD4A982m9ptU0xA1CNpsEL2IxYm1YkkAOiVLDCvEck7ly00S1dl5pvM');

        $price =  $stripe->prices->create([
            'currency' => 'eur',
            'unit_amount' => 1000,
           // 'recurring' => ['interval' => 'month'], for subscription
            'product_data' => ['name' => 'Gold Plan'],
          ]);

           $session=$stripe->checkout->sessions->create([
                  'mode' => 'payment',
                  'line_items' => [
                    [
                      'price' =>$price->id,
                      'quantity' => 2,
                    ],
                  ],
                  'success_url' => route('success'),
                  'payment_method_types' => [
                    'bancontact',
                    'card',
                  ],
                ]);
                return $session;

      return redirect()->away($session->id);
  }
  
  public function success(){
    return view('index');

  }
  

}
