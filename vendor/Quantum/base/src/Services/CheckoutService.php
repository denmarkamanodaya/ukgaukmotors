<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : CheckoutService.php
 **/

namespace Quantum\base\Services;


use Cart;
use Quantum\base\Models\PaymentGateway;

class CheckoutService
{


    public function create_checkout_page()
    {
        if(Cart::count() < 1)
        {
            $view = \View::make('base::frontend.Commerce.Checkout.empty');
            $contents = $view->render();
            return $contents;
        }

        $cartItems = [];
        $subscription = false;
        $subtotal = 0;
        $total = 0;
        foreach (Cart::items() as $item)
        {
            if(isset($item->model_id) && isset($item->model))
            {
                $model = new $item->model;
                $item->product = $model::where('id', $item->model_id)->first();
                if(isset($item->product->subscription) && $item->product->subscription == 1) $subscription = true;
                if($item->type == 'invoice')
                {
                    $subtotal = $subtotal + $item->price;
                    $item->product->description = $item->description;
                    $item->product->summary = $item->summary;
                    $item->product->members_page_after_payment = '/members/invoices';
                    $item->product->guest_page_after_payment = '';
                } else {
                    $subtotal = $subtotal + $item->product->amount;
                }
            }

            array_push($cartItems, $item);
        }
        if($subtotal == 0) abort(404); //no price

        //Todo Coupon adjustment
        $total = $subtotal;

        $payment_buttons = $this->paymentButton($subscription);
        $sitecountry = \Countries::siteCountry();
        $view = \View::make('base::frontend.Commerce.Checkout.checkout', compact('cartItems', 'payment_buttons', 'sitecountry', 'subtotal', 'total'));
        $contents = $view->render();
        return $contents;
    }

    public function paymentButton($subscription)
    {
        $buttons = [];
        $member = \Auth::check() ? 'members/' : '';
        $gateways = PaymentGateway::where('status', 'active')->get();
        foreach($gateways as $gateway)
        {
            $buttons[$gateway->slug]['image'] = $subscription ? $gateway->subscription_button_image : $gateway->payment_button_image;
            $buttons[$gateway->slug]['url'] = url($member.'payment/'.$gateway->slug);
        }
        return $buttons;
    }
}