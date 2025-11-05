<?php 

abstract class Operation {
  protected $operand_1;
  protected $operand_2;
  public function __construct($o1, $o2) {
    // Make sure we're working with numbers...
    if (!is_numeric($o1) || !is_numeric($o2)) {
      throw new Exception('Non-numeric operand.');
    }
    
    // Assign passed values to member variables
    $this->operand_1 = $o1;
    $this->operand_2 = $o2;
  }
  public abstract function operate();
  public abstract function getEquation(); 
}

// Addition subclass inherits from Operation
class Addition extends Operation {
  public function operate() {
    return $this->operand_1 + $this->operand_2;
  }
  public function getEquation() {
    return $this->operand_1 . ' + ' . $this->operand_2 . ' = ' . $this->operate();
  }
}


// Part 1 - Add subclasses for Subtraction, Multiplication and Division here
class Subtraction extends Operation {
  public function operate() {
    return $this->operand_1 - $this->operand_2;
  }
  public function getEquation() {
    return $this->operand_1 . ' - ' . $this->operand_2 . ' = ' . $this->operate();
  }
}

class Multiplication extends Operation {
  public function operate() {
    return $this->operand_1 * $this->operand_2;
  }
  public function getEquation() {
    return $this->operand_1 . ' * ' . $this->operand_2 . ' = ' . $this->operate();
  }
}

class Division extends Operation {
  public function operate() {
    return $this->operand_1 / $this->operand_2;
  }
  public function getEquation() {
    return $this->operand_1 . ' / ' . $this->operand_2 . ' = ' . $this->operate();
  }
}
// End Part 1







// Some debugs - un comment them to see what is happening...
// echo '$_POST print_r=>',print_r($_POST);
// echo "<br>",'$_POST vardump=>',var_dump($_POST);
// echo '<br/>$_POST is ', (isset($_POST) ? 'set' : 'NOT set'), "<br/>";
// echo "<br/>---";




// Check to make sure that POST was received 
// upon initial load, the page will be sent back via the initial GET at which time
// the $_POST array will not have values - trying to access it will give undefined message

  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $o1 = $_POST['op1'];
    $o2 = $_POST['op2'];
  }
  $err = Array();


// Part 2 - Instantiate an object for each operation based on the values returned on the form
//          For example, check to make sure that $_POST is set and then check its value and 
//          instantiate its object
// 
// The Add is done below.  Go ahead and finish the remiannig functions.  
// Then tell me if there is a way to do this without the ifs

  try {
    if (isset($_POST['add']) && $_POST['add'] == 'Add') {
      $op = new Addition($o1, $o2);
    }
// Put the code for Part 2 here  \/
    if (isset($_POST['sub']) && $_POST['sub'] == 'Subtract') {
      $op = new Subtraction($o1, $o2);
    }
    if (isset($_POST['mult']) && $_POST['mult'] == 'Multiplication') {
      $op = new Multiplication($o1, $o2);
    }
    if (isset($_POST['div']) && $_POST['div'] == 'Division') {
      $op = new Division($o1, $o2);
    }

// End of Part 2   /\

  }
  catch (Exception $e) {
    $err[] = $e->getMessage();
  }


/*
************************************************************ QUESTIONS *****************************************************
0) Then tell me if there is a way to do this without the ifs

  Could use a case switch statement or a key value pair map that associates each input 
  submission with their respective subclass of Operation and dynamicallly generate 
  the instance of that object at runtime to call the get equation function.


1) Explain what each of your classes and methods does, the order in which methods are invoked, 
and the flow of execution after one of the operation buttons has been clicked.

  Classes: 
    Operation - Abstract class that defines the framework of an operation for use by subclasses
    Addition - Subclass representing the addition operation of two variables
    Subtraction - Subclass representing the subtraction operation of two variables
    Multiplication - Subclass representing the multiplication operation of two variables
    Division - Subclass representing the division operation of two variables

  Methods: 
    public function operate() - performs the actual operation specified by the class 
          Ex: Division returns numerical value of division of the two operands
    public function getEquation() - returns the string necessary to display the total 
          operation to the user, selecting an operator depending on class (+*-/) and calling the operate function 

  Clicked -> HTML form -> POST request -> PHP receives input -> instantiates respective Operation subclass 
  -> getEquation() -> operate() -> output displayed


2) Also explain how the application would differ if you were to use $_GET, and why this may or may not be preferable.

  - Good for a situation like this one where we don't eed to store the calculations -> no data modification on the server.
  - Useful for debugging since you can see all inputs in the address bar

  Disadvantages

  - Less secure so values like user inputs are visible in the URL and stored in browser history.
  - worse at handling large amounts of data
  - clutters the address bar

3) Finally, please explain whether or not there might be another (better +/-) way 
to determine which button has been pressed and take the appropriate action

  Like the idea from earlier, could be more efficient to just use a map to check all the possible operations without using any ifs
  Or could try changing the name of all operations to some common name like "operation" and use it's 
  value to pick which class to instantiate.

******************************************************************************************************************************
*/
?>

<!doctype html>
<html>
<head>
<title>Lab 6</title>
</head>
<body>
  <pre id="result">
  <?php 
    if (isset($op)) {
      try {
        echo $op->getEquation();
      }
      catch (Exception $e) { 
        $err[] = $e->getMessage();
      }
    }
      
    foreach($err as $error) {
        echo $error . "\n";
    } 
  ?>
  </pre>
  <form method="post" action="lab6start.php">
    <input type="text" name="op1" id="name" value="" />
    <input type="text" name="op2" id="name" value="" />
    <br/>
    <!-- Only one of these will be set with their respective value at a time -->
    <input type="submit" name="add" value="Add" />  
    <input type="submit" name="sub" value="Subtract" />  
    <input type="submit" name="mult" value="Multiply" />  
    <input type="submit" name="div" value="Divide" />  
  </form>
</body>
</html>

