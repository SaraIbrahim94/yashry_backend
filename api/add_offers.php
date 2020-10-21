<title>Add offer</title>
<?php
include 'database.php';
class add_offer extends database{


    public function add($conn)
    {
        $input = $_GET;
        if(count($input) != 3)
        {
            echo '<h4>please enter all input fields, offer_type and discount. <p>Example: plese note that enter offer_type as</p><li> 1->for percentage discount</li><li> 2->for amount discount</li><li> 3->for buy 2 get 1 50% off</li></h4>';
            echo '<h4><p>Enter discount as</p><li> for type 1 (%): discount=10</li><li> for type 1 (money): discount=10</li><li> for type 3 (product name) : discount=jacket</li></h4>';
            echo '<h4><p>Enter product name for the one that has the offer as</p><li> name=jacket</li></h4>';
            die;
        }
      
        try {
            if (!array_key_exists("offer_type",$input) ||  !array_key_exists("discount",$input) || !array_key_exists("name",$input))
            throw new Exception("<span style='color:red'>Error:</span>  <p>please enter input fields as specfied below.<p><ul><li>product name that has the offer as name=t-shirt</li> <li>offer type  with offer_type as key</li><li> discount with discount as a key</li>  <p> ex:</p> <li> offer_type=3 & discount=jacket  </li><li> offer_type=1 & discount=20  </li> <p> type 1->for percentage discount, 2->for amount, 3->for buy 2 get 1 50% off (discount is name of the prodcut to apply 50% off ) </p></ul>");
            $discount = $input['discount'];
            if($input['offer_type'] == 3)
            {
                $sql="SELECT * from products where name = '$input[discount]'";
                $r = $conn->query($sql);
                if(!$r)
                throw new Exception("<p style='color:red'>Error:</p>please add product first using following api: http://localhost/yashry_backend/api/add_product.php");
                if ($r->num_rows > 0) {
                    $discount = $r->fetch_assoc()["id"];
                }
            }
                $sql="SELECT * from products where name = '$input[name]'";
                $r = $conn->query($sql);
                if(!$r)
                throw new Exception("<p style='color:red'>Error:</p>please add product first using following api: http://localhost/yashry_backend/api/add_product.php");
                if ($r->num_rows > 0) {
                    $product_id = $r->fetch_assoc()["id"];
                }
          }
          
          catch(Exception $e) {
              echo $e->getMessage();
           
        die;  
        }
        
        $sql="INSERT INTO offers (offer_type, discount, product_id) VALUES('$input[offer_type]','$discount','$product_id')";
        $r = $conn->query($sql);
        echo 'added successfully..!';


    }
}
$instance = new add_offer();
$conn = $instance->prepare();
$instance->add($conn);
?>