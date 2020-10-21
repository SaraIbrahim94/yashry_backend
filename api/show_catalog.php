<title>Catalog</title>
<?php
include 'database.php';
class catalog extends database{
  
    private $input;

    public function show($conn)
    {
        
        $input = $_GET;
       //   print_r($input['name']);die;
       
        try {
        //    if(!array_key_exists("currancy",$input) || !array_key_exists("name",$input))
          //  throw new Exception("<span style='color:red'>Error:</span>  <p>please enter input fields as specfied below.</p><ul><li>product name with name as key</li> <li>currancy with currancy as key</li><p>ex: currancy=$&name=jacket</p></ul>");

            $sql="SELECT * from currancy";
            $currancy = $conn->query($sql);

            if(!$currancy)
            throw new Exception("<p style='color:red'>Error:</p>please add price first using following api: http://localhost/yashry_backend/api/add_currancy.php");
            $sql="SELECT * from products";
            $products = $conn->query($sql);
            if(!$products)
            throw new Exception("<p style='color:red'>Error:</p>please add product first using following api: http://localhost/yashry_backend/api/add_product.php");

          }
          
          catch(Exception $e) {
            echo $e->getMessage();
        die;  
        }
        try {
                if ($products) {
                if ($products->num_rows > 0) {
                    echo '<ul>';
                    while($row = $products->fetch_assoc()) {
                        $data['price'] = '';
                        $data['name'] = $row['name'];
                        $sql="SELECT * from currancy where product_id = '$row[id]' and currancy_type = 'EGP'";
                            $currancy = $conn->query($sql);
                            if ($currancy) {
                                if ($currancy->num_rows > 0) {
                                    $data['price'] = $currancy->fetch_assoc()['price'];
                                }
                                else
                                $data["price"] = '<span style="color:red"> Please add price value to this product from this api: http://localhost/yashry_backend/api/add_currancy.php</span>';
            
                            }
                            echo '
                                    <li>
                                        '.$data["name"].' : '.$data["price"].' EGP
                                    </li>
                        ';
                        }
                        echo  '</ul>';
            
            //  echo 'added successfully..!';
                }
                else
                throw new Exception("<p style='color:red'>Note:</p>please add product first using following api: http://localhost/yashry_backend/api/add_product.php");
    
            }
            else
            throw new Exception("<p style='color:red'>Note:</p>please add product first using following api: http://localhost/yashry_backend/api/add_product.php");
        }
        catch(Exception $e) 
            {
                echo $e->getMessage();
            }

    }
}
$instance = new catalog();
$conn = $instance->prepare();
$instance->show($conn);
?>