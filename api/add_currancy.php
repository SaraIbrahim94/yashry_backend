<title>Add currancy</title>
<?php
include 'database.php';
class add_currancy extends database{
  
    private $input;

    public function add($conn)
    {
        $input = $_GET;
        if(count($input) != 3)
        {
            echo '<h4>please enter all input fields, <ul><li>product name with name as key</li><li>price with price as a key</li><li> currancy type with currancy_type as a key</li><p> ex: name=shoe & price=18 & currancy_type=$</p> with the same key name</h4>';
            echo '<p> Full Example: </p><p>http://localhost/yashry_backend/api/add_currancy.php?name=shoe&price=18&currancy_type=$ </p>';
            die;
        }
      
        try {
            if (!array_key_exists("name",$input) || !array_key_exists("price",$input) || !array_key_exists("currancy_type",$input))
            throw new Exception("<span style='color:red'>Error:</span>  please enter input fields as specfied below and make sure you enterd existing product name <ul> <li>product name with name as key</li><li> price with price as a key</li> <li> currancy type with currancy_type as a key</li> <p> ex:</p> <li> name=shoe & price=18 & currancy_type=$ </li> with the same name key</ul>");

            $sql="SELECT * from products where name = '$input[name]'";
            $r = $conn->query($sql);
            if ($r->num_rows > 0) {
                $product_id = $r->fetch_assoc()["id"];
              }
              else
              {
                throw new Exception("please enter existing product name");
              }
          }
          
          catch(Exception $e) {
            echo $e->getMessage();

        die;  
        }
       
       

        $sql="INSERT INTO currancy (product_id, price, currancy_type) VALUES('$product_id','$input[price]', '$input[currancy_type]')";
        $r = $conn->query($sql);
        echo 'added successfully..!';


    }
}
$instance = new add_currancy();
$conn = $instance->prepare();
$instance->add($conn);
?>