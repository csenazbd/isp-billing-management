<?php
include("../include/db_connect.php");
include ("../include/functions.php");
session_start();
/*install Process*/
$callbackURL = 'http://103.146.16.154/branch/index.php'; 
$app_key = 'OEOyCIxZuEtF76cS5RzVaaWstc'; 
$app_secret = 'h2EXSWTQ6iTQIjEwz83vnT80wzd7k8wJ4YBMKhvXhJnVG6Cm7hPX'; 
$username = '01831550088'; 
$password = 'Q4gp%tVJ-#%'; 
$base_url = 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout';
// $callbackURL = 'http://103.146.16.154/branch/index.php'; 
// $app_key = '0vWQuCRGiUX7EPVjQDr0EUAYtc'; 
// $app_secret = 'jcUNPBgbcqEDedNKdvE4G1cAK7D3hCjmJccNPZZBq96QIxxwAMEx'; 
// $username = '01770618567'; 
// $password = 'D7DaC<*E*eG'; 
// $base_url = 'https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout';
/* Grant Token*/
function getGrantToken($base_url, $username, $password, $app_key, $app_secret) {
    
    $post_token = array(
        'app_key' => $app_key,
        'app_secret' => $app_secret
    );

    $url = curl_init("$base_url/token/grant");
    $post_token = json_encode($post_token);
    $header = array(
        'Content-Type: application/json',
        'Accept: application/json',
        "password: $password",
        "username: $username"
    );

    curl_setopt($url, CURLOPT_HTTPHEADER, $header);
    curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($url, CURLOPT_POSTFIELDS, $post_token);
    curl_setopt($url, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

    $result_data = curl_exec($url);
    
    if (curl_errno($url)) {
        echo 'Curl error: ' . curl_error($url);
        curl_close($url);
        return null;
    }

    curl_close($url);

    $response_data = json_decode($result_data, true);
   
    return $response_data['id_token'] ?? null;
}

/*Create Payment*/ 
function createPayment($base_url, $id_token, $app_key, $callbackURL, $amount, $pop_id) {
    $request_data = [
        'callbackURL' => $callbackURL,
        'payerReference' => $pop_id,
        'mode' => '0011',
        'amount' => $amount,
        'intent' => 'sale',
        'currency' => 'BDT',
        'merchantInvoiceNumber' =>"TRANSID-".strtoupper(uniqid()),
    ];

    $url = curl_init("$base_url/create");
    $request_data_json = json_encode($request_data);

    $header = [
        'Content-Type: application/json',
        "Authorization: Bearer $id_token", 
        "X-APP-Key: $app_key"
    ];

    curl_setopt($url, CURLOPT_HTTPHEADER, $header);
    curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($url, CURLOPT_POSTFIELDS, $request_data_json);
    curl_setopt($url, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

    $result_data = curl_exec($url);

    if (curl_errno($url)) {
        echo 'Curl error: ' . curl_error($url);
        curl_close($url);
        return null;
    }

    curl_close($url);

    $response = json_decode($result_data, true);
    // if (isset($response['statusCode']) && $response['statusMessage'] === 'Successful') {
    //     date_default_timezone_set('Asia/Dhaka');
    //     $todayDate = date('H:i A, d-M-Y');
    //     executePayment($base_url,$id_token,$app_key,$response['paymentID']);
    //     $con->query("INSERT INTO pop_transaction(pop_id, amount, paid_amount, action, transaction_type, recharge_by, date)
    //             VALUES ('$pop_id', '$amount', '$amount', 'Recharge', '1', '$recharge_by', '$todayDate')");

    // } 
    $_SESSION['id_token'] = $id_token;
    $_SESSION['app_key'] =$app_key;
    $_SESSION['final_amount']=$amount;
    $_SESSION['pop_id']=$pop_id;
    return $response;
}

function executePayment($base_url,$id_token,$app_key,$paymentID)
{
        
        $post_paymentID = array(
        'paymentID' => $paymentID
        );
        
            $posttoken = json_encode($post_paymentID);

    $url = curl_init("$base_url/execute");
    $header = array(
        'Content-Type:application/json',
        
        "authorization:$id_token",
        "x-app-key:$app_key"
    );

    curl_setopt($url, CURLOPT_HTTPHEADER, $header);
    curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($url, CURLOPT_POSTFIELDS, $posttoken);
    curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    $result_data = curl_exec($url);
    curl_close($url);

    return $result_data;
}

 function queryPayment($base_url, $id_token, $app_key, $paymentID)
{
        $url = curl_init("$base_url/payment/query/" . $paymentID);
        $header = array(
            'Content-Type:application/json',
            "authorization:$id_token",
            "x-app-key:$app_key"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        $result_data = curl_exec($url);
        curl_close($url);

        return $result_data;
}



/*Payment Process*/
if (isset($_GET['submit_payment']) && !empty($_GET['submit_payment'])) {
    $amount = $_GET['amount']; 
    $pop_id = $_GET['pop_id']; 
    $result = $con->query("SELECT `fullname` FROM add_pop WHERE id='$pop_id'");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $recharge_by = $row['fullname']; 
    } 

    /*Start Grant Token*/ 
    $id_token = getGrantToken($base_url, $username, $password, $app_key, $app_secret);
 
    if ($id_token) {
        $paymentResponse = createPayment($base_url, $id_token, $app_key, $callbackURL, $amount,$pop_id,$recharge_by,$con);
        // echo $paymentResponse['paymentID']; 
        // echo '<pre>';
        // print_r($paymentResponse['paymentID']); 
        // echo '</pre>';
        // exit; 
        if (!empty($paymentResponse['paymentID']) && !empty($paymentResponse['statusMessage']) && $paymentResponse['statusMessage'] === 'Successful') {
            if (isset($paymentResponse['bkashURL'])) {      
                header("Location: " . $paymentResponse['bkashURL']);
                exit;
            } else {
                echo "Error creating payment.";
            }
        }

      

        
    } else {
        echo "Error generating token.";
    }
}


?>