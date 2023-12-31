<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\BaseController;
use App\Models\AccountPayment;
use App\Models\PaymentHistoryEmployer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentForEmployerController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $vnp_HashSecret = 'KNAREAARTPBAELKXTPLZKBUMSTCJHIYE';
    public function index()
    {
        return view('employer.payment.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'price' => 'required|integer',
        ]);
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('employer.payment-for-emplyer.vnpayReturn');
        $vnp_TmnCode = "S50PEHFY"; //Mã website tại VNPAY 
        $vnp_HashSecret = $this->vnp_HashSecret; //Chuỗi bí mật
        $vnp_TxnRef = rand(0000, 9999);
        $vnp_OrderInfo = $request->desceibe;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount =  $request->pay_member * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = '192.168.1.6';
        $vnp_BankCode = 'NCB';

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_BankCode" => $vnp_BankCode,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }


        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00', 'message' => 'success', 'data' => $vnp_Url
        );
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
    }
    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }
    public function vnpayReturn(Request $request)
    {
        $vnp_SecureHash = $_GET['vnp_SecureHash'];
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        // $url_ipn = $request->getRequestUri();
        // dd($request->getRequestUri());
        // $url_ipn = route('employer.package.payment.output', $_GET);
        // dd($url_ipn);
        // dd($request->all());
        $secureHash = hash_hmac('sha512', $hashData,  $this->vnp_HashSecret);
        if ($secureHash == $vnp_SecureHash) {
            if ($_GET['vnp_ResponseCode'] == '00') {

                $mony = AccountPayment::where('user_id', Auth::guard('user')->user()->id)->first();
                if ($mony) {
                    $total = $mony;
                    $total->surplus += $request->vnp_Amount / 100;
                } else {
                    $total = new AccountPayment();
                    $total->surplus = $request->vnp_Amount / 100;
                }
                $total->user_id = Auth::guard('user')->user()->id;
                $total->save();
                $this->setFlash(__('Giao dịch thành công'));
            } else {
                $this->setFlash(__('Giao dịch bị hủy bỏ'), 'error');
            }
        } else {
            $this->setFlash(__('chu ky khong hop le'), 'error');
        }
        if ($_GET['vnp_ResponseCode'] == '00') {
            $status = 1;
        } else {
            $status = 0;
        }
        $payment = new PaymentHistoryEmployer();
        $payment->user_id = Auth::guard('user')->user()->id;
        $payment->price = $request->vnp_Amount / 100;
        $payment->desceibe = $request->vnp_OrderInfo;
        $payment->status = $status;
        $payment->form = $request->vnp_PayDate;
        $payment->save();
        return redirect()->back();
    }
}
