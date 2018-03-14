<?php
/**
 * All Wet
 * 2018
 * 
 * Class
 * Employee
 */

namespace AllWet;

class Employee {

    // Properties
    private $mysqli;

    private $employee_array;

    private $employee_id;
    private $employee_name;
    private $employee_username;
    private $employee_password;
    private $employee_image;

    // Methods

    /**
     * __construct
     * @param: 
     * @return: void
     */
    function __construct($mysqli){
        $this->mysqli = $mysqli;
    }

    /**
     * usernameExists
     * 
     * @param: $employee_username
     * @return: Bool
     */
    private function usernameExists($employee_username){
        // Handle Param
        $this->employee_username = $employee_username;

        // Query in DB
        $stmt = $this->mysqli->prepare("SELECT `employee_id` FROM `employee` WHERE `employee_username` = ?");
        $stmt->bind_param("s", $this->employee_username);
        $stmt->execute();

        $result = $stmt->get_result();
        if($result->fetch_assoc()){
            return True;
        } else {
            return False;
        }
    }

    /**
     * getAll
     * 
     * @param: none
     * @return: Array
     */
    private function getAll(){
        // Query in DB
        $stmt = $this->mysqli->prepare("SELECT `employee_id`, `employee_name`, `employee_username`, `employee_image` FROM `employee`");
        $stmt->execute();
        $result = $stmt->get_result();                                          
        
        // Create Empty Placeholder
        $this->employee_array = array();

        // Loop along data
        while($emp = $result->fetch_array()){
            $employee_id = $emp['employee_id'];
            $employee_name = $emp['employee_name'];
            $employee_username = $emp['employee_username'];
            $employee_image = $emp['employee_image'];

            $prep_arr = array(
                "employee_id" => $this->employee_id,
                "employee_name" => $this->employee_name,
                "employee_username" => $this->employee_username,
                "employee_image" => $this->employee_image
            );


            array_push($this->employee_array, $prep_arr);
        }

        // Return array
        return $this->employee_array;
    }

    /**
     * get
     * 
     * @param: $employee_id
     * @return: Array
     */
    private function get($employee_id){
        // Handle Param
        $this->employee_id = $employee_id;

        // Query in DB
        $stmt = $this->mysqli->prepare("SELECT `employee_name`, `employee_username`, `employee_image` FROM `employee` WHERE `employee_id` = ?");
        $stmt->bind_param("s", $this->employee_id);
        $stmt->execute();

        $result = $stmt->get_result();

        // Return Result
        return $result->fetch_assoc();
    }

    /**
     * getByUsername
     * 
     * @param: $employee_username
     * @return: Array
     */
    private function getByUsername(String $employee_username){
        // Handle Param
        $this->employee_username = $employee_username;

        // Query in DB
        $stmt = $this->mysqli->prepare("SELECT `employee_id`, `employee_name`, `employee_username`, `employee_image` FROM `employee` WHERE `employee_username` = ?");
        $stmt->bind_param("s", $this->employee_username);
        $stmt->execute();

        $result = $stmt->get_result();

        // Return result
        return $result->fetch_assoc();
    }

    /**
     * delete
     * 
     * @param: $employee_id
     * @return: Bool
     */
    private function delete($employee_id){
        // Handle Param
        $this->employee_id = $employee_id;

        // Delete in DB
        $stmt = $this->mysqli->prepare("DELETE FROM `employee` WHERE `employee_id` = ?");
        $stmt->bind_param("s", $this->employee_id);
        $stmt->execute();

        // Check if Delete was successful
        $stmt = $this->mysqli->prepare("SELECT `employee_id` FROM `employee` WHERE `employee_id` = ? LIMIT 1");
        $stmt->bind_param("s", $this->employee_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $result = $result->fetch_assoc();

        if(empty($result)){
            return True;
        } else {
            return False;
        }
    }

    /**
     * add
     * 
     * @param: $employee_name, $employee_username, $employee_password, $employee_image
     * @return: Bool
     */
    private function add(String $employee_name, String $employee_username, String $employee_password, String $employee_image){
        // Handle Params
        $this->employee_name = $employee_name;
        $this->employee_username = $employee_username;
        $this->employee_password = $employee_password;
        $this->employee_image = $employee_image;

        // Check if username already exists
        if($this->usernameExists($this->employee_username)){
            return "Username already exist";
        } else {
            // Check if password length is okay
            if(strlen($this->employee_password) < 8){
                return "Password less than 8 characters";
            } else {

                // Hash password
                $this->employee_password = password_hash($this->employee_password, PASSWORD_DEFAULT);

                // Insert into DB
                $stmt = $this->mysqli->prepare("INSERT INTO `employee` (`employee_name`, `employee_username`, `employee_password`, `employee_image`) VALUES (?,?,?,?)");
                $stmt->bind_param("ssss", $this->employee_name, $this->employee_username, $this->employee_password, $this->employee_image);
                $stmt->execute();

                // Return true
                return True;
            }
        }

    }
  
  private function updateInfo($employee_id, String $employee_name, String $employee_image){
    $this->employee_id = $employee_id;
    $this->employee_name = $employee_name;
    $this->employee_image = $employee_image;
    
    if($this->get($this->employee_id)){
      $stmt = $this->mysqli->prepare("UPDATE `employee_name`=?, `employee_image`=? FROM `employee` WHERE `employee_id`=?");
      $stmt->bind_param("sss", $this->employee_name, $this->employee_image, $this->employee_id);
      $stmt->execute();
      
      $inf = $this->get($this->employee_id);
      if($inf['employee_name'] === $this->employee_name){
        return True;
      } else {
        return False;
      }
    } else {
      return False;
    }
  }

  private function updateUsername($employee_id, String $employee_username){
    $this->employee_id = $employee_id;
    $this->employee_username = $employee_username;
    
    $inf = $this->get($this->employee_username);
    $current_username = $inf['employee_username'];
    if($current_username == $this->employee_username){
       return False;
    } else {
      $stmt = $this->mysqli->prepare("SELECT employee_id FROM `employee` WHERE employee_username = ? EXCEPT employee_id = `?` LIMIT 1");
      $stmt->bind_param("ss", $this->employee_username, $this->employee_id);
      $stmt->execute();
      $result = $stmt->get_result();
      $result = $stmt->fetch_assoc();
      if($result){
        return "Username already in use";
      } else {
        $stmt = $this->mysqli->prepare("UPDATE `employee_username`=? FROM `employee` WHERE `employee_id`=?");
        $stmt->bind_param("ss", $this->employee_username, $this->employee_id);
        $stmt->execute();
        return True;
      }
    }
  }
  
  private function updatePassword($employee_id, String $employee_password, String $employee_new_password){
    $this->employee_id = $employee_id;
    $this->
  }

}
?>