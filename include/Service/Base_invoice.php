<?php

class Base_invoivce{
    protected static $con; 
    public function __construct($con)
    {
        self::$con=$con; 
    }

    public function request_validation($data){
        return [
            'usr_id' => isset($_SESSION["uid"]) ? intval($_SESSION["uid"]) : 0,
            'client_id' => $data['client_id'] ?? null,
            'date' =>$data['date']?? date('Y-m-d'),
            'sub_total' => $data['table_total_amount'] ?? null,
            'discount' => $data['table_discount_amount'] ?? 0,
            'grand_total' => $data['table_total_amount'] - ($data['table_discount_amount'] ?? 0),
            'total_due' => $data['table_due_amount'] ?? null,
            'total_paid' => $data['table_paid_amount'] ?? null,
            'note' => $data['note'] ?? '',
            'status' => $data['table_status'] ?? '0',
            // 'sub_ledger' => $data['sub_ledger'] ?? '0',

            'product_ids' => $data['table_product_id'] ?? [],
            'qtys' => $data['table_qty'] ?? [],
            'prices' => $data['table_price'] ?? [],
            'total_prices' => $data['table_total_price'] ?? []
        ]; 
    }
    protected function insert_invoice($table,$validator){
        $transaction_number=self::get_transaction_number();
         $sql = "INSERT INTO $table (transaction_number,usr_id, client_id, date, sub_total, discount, grand_total, total_due, total_paid, note, status) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = self::$con->prepare($sql);

        if (!$stmt) {
            throw new Exception('Prepare statement failed: ' . self::$con->error);
        }

        $stmt->bind_param("siisssssiss", 
           $transaction_number,
            $validator['usr_id'], 
            $validator['client_id'], 
            $validator['date'], 
            $validator['sub_total'], 
            $validator['discount'], 
            $validator['grand_total'], 
            $validator['total_due'], 
            $validator['total_paid'], 
            $validator['note'], 
            $validator['status']
        );
        
        if (!$stmt->execute()) {
            throw new Exception('Execute statement failed: ' . $stmt->error);
        }

        return [
            'invoice_id' => self::$con->insert_id,
            'transaction_number' => $transaction_number
        ];
    }
    protected function insert_invoice_details($table, $invoice_id, $validator,$transaction_number=NULL){
        
        /********************Get Transaction Number ***********************/
        if (is_null($transaction_number)) {
            $transaction_table = $table === "purchase_details" ? "purchase" : "sales";
            
            $get_transaction_number = self::$con->query("SELECT transaction_number FROM `$transaction_table` WHERE id = $invoice_id");
            if ($get_transaction_number && $row = $get_transaction_number->fetch_assoc()) {
                $transaction_number = $row['transaction_number'];
            } else {
                throw new Exception("Transaction number not found for invoice ID: $invoice_id");
            }
        }
        
        /******************** Insert Details ***********************/
        $details_sql = "INSERT INTO $table (transaction_number,invoice_id, product_id, qty, value, total) VALUES (?,?, ?, ?, ?, ?)";
        $details_stmt =self::$con->prepare($details_sql);

        if (!$details_stmt) {
            throw new Exception('Prepare statement for details failed: ' . self::$con->error);
        }
       
        foreach ($validator['product_ids'] as $index => $product_id) {
            $qty = $validator['qtys'][$index];
            $price = $validator['prices'][$index];
            $total_price = $validator['total_prices'][$index];


            if ($table=="purchase_details" &&  $validator['status']=='1') {
               $_all=self::$con->query("SELECT * FROM products WHERE id = $product_id");
               while($rows=$_all->fetch_array()){
                   $sub_ledger=$rows['purchase_ac'];
                   if ($allSubLedger=self::$con->query("SELECT * FROM legder_sub WHERE id=$sub_ledger")) {
                        while ($rwos=$allSubLedger->fetch_array()) {
                            $ledger_ID=$rwos['ledger_id'];
                        }
                    }
                    if ($getMasterLdg=self::$con->query("SELECT * FROM ledger WHERE id=$ledger_ID")) {
                        while ($rwos=$getMasterLdg->fetch_array()) {
                            $mstr_ledger_id=$rwos['mstr_ledger_id'];
                        }
                    }
                    self::$con->query("INSERT INTO ledger_transactions (transaction_number,user_id, mstr_ledger_id, ledger_id, sub_ledger_id, qty, value, total, status, note, date) 
                    VALUES ('".$transaction_number."','".$validator['usr_id']."', '".$mstr_ledger_id."', '".$ledger_ID."', '".$sub_ledger."', '".$qty."', '".$price."', '".$total_price."', '1', '".$validator['note']."', '".$validator['date']."')");
               }
            } 
            if ($table == "sales_details" &&  $validator['status']=='1') {
                $_all=self::$con->query("SELECT * FROM products WHERE id = $product_id");
                while($rows=$_all->fetch_array()){
                   $sub_ledger=$rows['sales_ac'];
                   if ($allSubLedger=self::$con->query("SELECT * FROM legder_sub WHERE id=$sub_ledger")) {
                        while ($rwos=$allSubLedger->fetch_array()) {
                            $ledger_ID=$rwos['ledger_id'];
                        }
                    }
                    if ($getMasterLdg=self::$con->query("SELECT * FROM ledger WHERE id=$ledger_ID")) {
                        while ($rwos=$getMasterLdg->fetch_array()) {
                            $mstr_ledger_id=$rwos['mstr_ledger_id'];
                        }
                    }
                    self::$con->query("INSERT INTO ledger_transactions (transaction_number,user_id, mstr_ledger_id, ledger_id, sub_ledger_id, qty, value, total, status, note, date) 
                    VALUES ('".$transaction_number."','".$validator['usr_id']."', '".$mstr_ledger_id."', '".$ledger_ID."', '".$sub_ledger."', '".$qty."', '".$price."', '".$total_price."', '1', '".$validator['note']."', '".$validator['date']."')");
               }
            }
            
            $details_stmt->bind_param("siiiii", $transaction_number,$invoice_id, $product_id, $qty, $price, $total_price);
            
            if (!$details_stmt->execute()) {
                throw new Exception('Execute statement for details failed: ' . $details_stmt->error);
            }
        }
    }
    public function request_update_invoice($table,$invoice_id,$validator){
         /* Update data in `sales` table */
         $sql = "UPDATE $table SET usr_id = ?, client_id = ?, date = ?, sub_total = ?, discount = ?, grand_total = ?, total_due = ?, total_paid = ?, note = ?, status = ? WHERE id = ?";
         $stmt = self::$con->prepare($sql);
         $stmt->bind_param("iisssssissi", 
             $validator['usr_id'], 
             $validator['client_id'], 
             $validator['date'], 
             $validator['sub_total'], 
             $validator['discount'], 
             $validator['grand_total'], 
             $validator['total_due'], 
             $validator['total_paid'], 
             $validator['note'], 
             $validator['status'], 
             $invoice_id,
         );
         if (!$stmt->execute()) {
            throw new Exception('Error updating  data.');
         }
         
    }
    protected function  request_delete_invoice_details($table,$invoice_id){

        $get_transaction_number=self::$con->query("SELECT transaction_number FROM $table WHERE invoice_id = $invoice_id");
        while($rows=$get_transaction_number->fetch_array()){
            $transaction_number=$rows['transaction_number'];
        }
        if (!empty($transaction_number)) {
            self::$con->query("DELETE FROM ledger_transactions WHERE transaction_number = '$transaction_number'");
        }
        self::$con->query("DELETE FROM $table WHERE invoice_id = $invoice_id");
    }

    public static function get_transaction_number(){
        /*Implementation for get transaction number*/
        $transaction_number= "TRANSID-".strtoupper(uniqid());
        return $transaction_number;
    }
    public static function delete($table, $invoice_id) {
        if (!empty($invoice_id) && !empty($table) && is_numeric($invoice_id)) {
            if ($table == "sales") {
                $get_transaction_number_result = self::$con->query("SELECT transaction_number FROM sales WHERE id = $invoice_id");
                if ($get_transaction_number_result && $row = $get_transaction_number_result->fetch_assoc()) {
                    $transaction_number = $row['transaction_number'];
                    self::$con->query("DELETE FROM ledger_transactions WHERE transaction_number = '$transaction_number'");
                    self::$con->query("DELETE FROM sales_details WHERE transaction_number = '$transaction_number'");
                    self::$con->query("DELETE FROM sales WHERE id = $invoice_id");
                }
            } elseif ($table == "purchase") {
                $get_transaction_number_result = self::$con->query("SELECT transaction_number FROM purchase WHERE id = $invoice_id");
                if ($get_transaction_number_result && $row = $get_transaction_number_result->fetch_assoc()) {
                    $transaction_number = $row['transaction_number'];
                    self::$con->query("DELETE FROM ledger_transactions WHERE transaction_number = '$transaction_number'");
                    self::$con->query("DELETE FROM purchase_details WHERE transaction_number = '$transaction_number'");
                    self::$con->query("DELETE FROM purchase WHERE id = $invoice_id");
                }
            }
        }
    }
    
}




?>