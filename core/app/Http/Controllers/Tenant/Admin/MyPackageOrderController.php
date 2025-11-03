<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\PaymentLogs;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class MyPackageOrderController extends Controller
{
    public function my_payment_logs(){
        $package_orders = tenant()->payment_log()->get();
        $current_package = PaymentLogs::where('id', tenant()->renewal_payment_log_id)->first();
//        dd($current_package);


        return view('tenant.admin.my-orders.package-order')->with(['package_orders' => $package_orders, 'current_package'=> $current_package]);
    }

    public function generate_package_invoice(Request $request)
    {
        $payment_details = PaymentLogs::with('user')->find($request->id);

        if (!$payment_details) {
            return abort(404);
        }

        $user = $payment_details->user;

        $pdf = PDF::loadView('tenant.frontend.invoice.order', [
            'payment_details' => $payment_details,
            'user' => $user,
        ])->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->stream('invoice.pdf');
    }

    public function package_order_cancel(Request $request){
        $this->validate($request,[
            'package_id' => 'required'
        ]);

        $user = tenant()->user()->first()->payment_log();
        $order_details = $user->where('id',$request->package_id)->first();

        if (empty($order_details)) {
            return abort(404);
        }

        //send mail to admin
        $order_page_form_mail =  get_static_option('order_page_form_mail');
        $order_mail = $order_page_form_mail ? $order_page_form_mail : get_static_option('site_global_email');
        $order_details->status = 'cancel';
        $order_details->save();
        //send mail to customer
        $data['subject'] = __('one of your package order has been cancelled');
        $data['message'] = __('hello').'<br>';
        $data['message'] .= __('your package order ').' #'.$order_details->id.' ';
        $data['message'] .= __('has been cancelled by the user');

        //send mail while order status change
        try {
            Mail::to($order_mail)->send(new BasicMail($data['message'],$data['subject']));
        }catch (\Exception $e){
            //handle error
            return redirect()->back()->with(['msg' => __('Order Cancel, mail send failed'), 'type' => 'warning']);
        }
        if (!empty($order_details)){
            //send mail to customer
            $data['subject'] = __('your order status has been cancel');
            $data['message'] = __('hello'). '<br>';
            $data['message'] .= __('your order').' #'.$order_details->id.' ';
            $data['message'] .= __('status has been changed to cancel');
            try {
                //send mail while order status change
                Mail::to($order_details->email)->send(new BasicMail($data['message'], $data['subject']));
            }catch (\Exception $e){
                //handle error
                return redirect()->back()->with(['msg' => __('Order Cancel, mail send failed'), 'type' => 'warning']);
            }

        }
        return redirect()->back()->with(['msg' => __('Order Cancel'), 'type' => 'warning']);
    }
}
