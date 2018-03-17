<?php
/**
 * All Wet
 * 2018
 * 
 * Class
 * Transaction
 */

namespace AllWet;

class Transaction {
    
    // Properties
    private $mysqli;

    private $transaction_array;

    private $transaction_id = 0;
    private $transaction_date;
    private $transaction_time;
    private $customer_id;
    private $transaction_items;
    private $transaction_count;
    private $transaction_price;
    private $transaction_status;
    private $transaction_longitude;
    private $transaction_latitude;
    private $transaction_address;
    
    // Methods

    /**
     * __construct
     * @param: 
     * @return: void
     */
    function __construct($mysqli){
        $this->mysqli = $mysqli;
    }

    final private function getAll(){
        $stmt = $this->mysqli->prepare("SELECT * FROM `transaction`");
        $stmt->execute();
        $result = $stmt->get_result();        

        $this->transaction_array = array();

        while($trans = $result->fetch_array()){
            array_push($this->transaction_array, $trans);
        }

        return $this->transaction_array;
    }

    final private function get($transaction_id){
        $this->transaction_id = $transaction_id;

        $stmt = $this->mysqli->prepare("SELECT * FROM `transaction` WHERE `transaction_id` = ? LIMIT 1");
        $stmt->bind_param("s", $this->transaction_id);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();

    }

    final private function getAllByCustomerId($customer_id){
        $this->customer_id = $customer_id;

        $stmt = $this->mysqli->prepare("SELECT * FROM `transaction` WHERE `customer_id` = ?");
        $stmt->bind_param("s", $this->customer_id);
        $stmt->exec();

        $result = $stmt->get_result();
        return $result->fetch_array();
    }

    final private function delete($transaction_id){
        $this->transaction_id = $transaction_id;

        $stmt = $this->mysqli->prepare("DELETE FROM `transaction` WHERE `transaction_id` = ?");
        $stmt->bind_param("s", $this->transaction_id);
        $stmt->execute();
    
        if($this->get($this->transaction_id)){
            return False;
        } else {
            return True;
        }
    }

    final private function add($t_array){
        if($t_array['transaction_date']) $this->transaction_date = $t_array['transaction_date'];
        if($t_array['transaction_time']) $this->transaction_ = $t_array['transaction_time'];
        if($t_array['customer_id']) $this->customer_id = $t_array['customer_id'];
        if($t_array['transaction_items']) $this->transaction_items = $t_array['transaction_items'];
        if($t_array['transaction_count']) $this->transaction_count = $t_array['transaction_count'];
        if($t_array['transaction_price']) $this->transaction_price = $t_array['transaction_price'];
        if($t_array['transaction_status']) $this->transaction_status = $t_array['transaction_status'];
        if($t_array['transaction_longitude']) $this->transaction_longitude = $t_array['transaction_longitude'];
        if($t_array['transaction_latitude']) $this->transaction_latitude = $t_array['transaction_latitude'];
        if($t_array['transaction_address']) $this->transaction_address = $t_array['transaction_address'];

        $stmt = $this->mysqli->prepare("INSERT INTO `transaction` (transaction_date, transaction_time, customer_id, transaction_items, transaction_count, transaction_price, transaction_status, transaction_longitude, transaction_latitude, transaction_address)");
        $stmt->bind_param("ssssssssss", $this->transaction_date, $this->transaction_time, $this->customer_id, $this->transaction_items, $this->transaction_count, $this->transaction_price, $this->transaction_status, $this->transaction_longitude, $this->transaction_latitude, $this->transaction_address);
        $stmt->execute();

        return True;
    }

    final private function update($t_array){
        if($t_array['transaction_id']) $this->transaction_id = $t_array['transaction_id'];
        if($t_array['transaction_date']) $this->transaction_date = $t_array['transaction_date'];
        if($t_array['transaction_time']) $this->transaction_ = $t_array['transaction_time'];
        if($t_array['customer_id']) $this->customer_id = $t_array['customer_id'];
        if($t_array['transaction_items']) $this->transaction_items = $t_array['transaction_items'];
        if($t_array['transaction_count']) $this->transaction_count = $t_array['transaction_count'];
        if($t_array['transaction_price']) $this->transaction_price = $t_array['transaction_price'];
        if($t_array['transaction_status']) $this->transaction_status = $t_array['transaction_status'];
        if($t_array['transaction_longitude']) $this->transaction_longitude = $t_array['transaction_longitude'];
        if($t_array['transaction_latitude']) $this->transaction_latitude = $t_array['transaction_latitude'];
        if($t_array['transaction_address']) $this->transaction_address = $t_array['transaction_address'];

        $stmt = $this->mysqli->prepare("UPDATE `transaction_` = ?, `transaction_` = ?,`transaction_` = ?,`transaction_` = ?,`transaction_` = ?,`transaction_` = ?,`transaction_` = ?,`transaction_` = ?,`transaction_` = ?,`transaction_` = ? FROM `transaction` WHERE `transaction_id` = ?");
        $stmt->bind_param("sssssssssss", $this->transaction_date, $this->transaction_time, $this->customer_id, $this->transaction_items, $this->transaction_count, $this->transaction_price, $this->transaction_status, $this->transaction_longitude, $this->transaction_latitude, $this->transaction_address,$this->transaction_id);
        $stmt->execute();

        return True;
    }

    final private function updateStatus($transaction_id, $transaction_status){
        $this->transaction_id = $transaction_id;
        $this->transaction_status = $transaction_status;
        
        $stmt = $this->mysqli->prepare("UPDATE `transaction_status` = ? FROM `transaction` WHERE `transaction_id` =?");
        $stmt->bind_param("ss", $this->transaction_status, $this->transaction_id);
        $stmt->execute();

        return True;
    }
    
}
?>