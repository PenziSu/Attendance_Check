<?PHP
class Car {
     // Declare variables
     // This is Value (資料)
     var $tireColor;

function showTireColor( ) {
     //Use $this to point to the current object. 
     //i.e. $this -> tireColor gets data from the variable $tireColor in the object. 
     echo "The color of the tires are: " . $this->tireColor;
}


}

?>