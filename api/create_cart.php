<title>create cart</title>
<?php
include 'database.php';
class cart extends database{

    private $input;

    public function create($conn)
    {
        $input = $_GET;
        $output = [];
        if(!array_key_exists("currancy",$input) || !array_key_exists("name",$input))
        {
        echo "<span style='color:black; font-size:2rem'>Note:</span>  <p>please enter input fields as specfied below.</p>";
        echo "<ul><li>product name with name as key</li> <li>currancy with currancy as key</li><p>ex: currancy=$&name[]=jacket&name[]=t-shirt</p>Full example: http://localhost/yashry_backend/api/create_cart.php?currancy=$&name[]=jacket&name[]=t-shirt<p></p></ul>";
        die;
        }
        try {

            $sql="SELECT * from currancy where currancy_type = '$input[currancy]'";
            $currancy = $conn->query($sql);
            $obj = $currancy->fetch_assoc();
            if(!$currancy || empty($obj))
            throw new Exception("<p style='color:red'>Error:</p>please add price first using following api: http://localhost/yashry_backend/api/add_currancy.php");
            
            $subtotal = 0;
            $discount_txt = [];
            $discount_total = 0;
            $tax = 0;
            $disc_product_id;
            $disc_rel_product;
            $disc_amout = 0;
            $product_name = '';
            if(is_array($input['name']))
            {
                foreach($input['name'] as $one)
                {
                   
                    $sql="SELECT * from products where name  = '$one'";
                    $products = $conn->query($sql);
                    if(!$products)
                    throw new Exception("<p style='color:red'>Error:</p>please add product'.$input[name].' first using following api: http://localhost/yashry_backend/api/add_product.php");
                  
                    if($products){
                    if ($products->num_rows > 0) {
                        echo '<ul>';
                        while($row = $products->fetch_assoc()) {
                            $data['price'] = '';
                            $data['name'] = $row['name'];
                            $sql="SELECT * from currancy where product_id = '$row[id]' and currancy_type = '$input[currancy]'";
                                $currancy = $conn->query($sql);
                                if ($currancy) {
                                    if ($currancy->num_rows > 0) {
                                        $data['price'] = $currancy->fetch_assoc()['price'];
                                    }
                                    else
                                    $data["price"] = '<span style="color:red"> Please add price value to this product from this api: http://localhost/yashry_backend/api/add_currancy.php</span>';
                
                                }
                                if($product_name == $one)
                                {
                                    $product_name = '';
                                    $sql="SELECT * from products where id = '$disc_product_id'";
                                    $product = $conn->query($sql);    
                                    if ($product) {
                                        if ($product->num_rows > 0) {
                                            $name = $product->fetch_assoc()['name'];
                                        }                    
                                    }
                                    $sql="SELECT * from currancy where product_id = '$disc_product_id' and currancy_type = '$input[currancy]'";
                                    $currancy = $conn->query($sql);
                                    if ($currancy) {
                                        if ($currancy->num_rows > 0) {
                                            $disc_price = $currancy->fetch_assoc()['price'];
                                        }                    
                                    }
            
                                    if(in_array ( $name, $input['name']))//check if this product in the cart to make discount on
                                    {
                                    $amount = $disc_price/2;
                                    $discount_txt[] = '50%   off '.$name. ': -'. $amount .' '.$input["currancy"];
                                    $disc_amout = 0;
                                    $discount_total += $amount;
                                    }
                                }
                                $tax = (($data['price']*14)/100);
                                $subtotal += $data['price'];
                                $price = $data['price'];
                                $data['price'] = $data['price'] + $tax;
                                //get offer if exist
                                $sql="SELECT * from offers where product_id = '$row[id]' LIMIT 0, 1";
                                $offer = $conn->query($sql);
                                $discount = '';
                                if ($offer) {
        
                                    if ($offer->num_rows > 0) {
                                        while($row_2 = $offer->fetch_assoc()) {
                                            if($row_2['offer_type'] == 1)//percentage type
                                            {
                                                $amount = ($data['price'] * $row_2['discount'])/100;
                                                $discount = $data['price'] - $amount;
                                                $discount_txt[] = $row_2['discount'].'%  off '.$data['name']. ': -'. $amount .' '.$input["currancy"];
                                                $discount_total += $amount;
                                            }
                                            else if($row_2['offer_type'] == 2)//amount type
                                            {
                                                $discount = $data['price'] - $row_2['discount'];
                                                $discount_txt[] = $row_2['discount'].' '.$input["currancy"].' off'.$data['name']. ': -'. $row_2['discount'] .' '.$input["currancy"];
                                                $discount_total += $amount;
                                            }
                                            else
                                            {
                                                $disc_product_id = $row_2['discount'];
                                                $disc_rel_product = $row_2['product_id'];
                                                $product_name = $one;
                                                
                                            }
                                        
                                           
                                        }
                                    }
                                   // else
                                    //$data["price"] = '<span style="color:red"> Please add price value to this product from this api: http://localhost/yashry_backend/api/add_currancy.php</span>';
                
                                }
                                echo '
                                        <li>
                                            '.$data["name"].' : '.$price.' '.$input["currancy"].';
                                        </li>
                               ';
                            }
                            echo  '</ul>';
                           
                  
                    }
                
                }
                
                }
                $tax = ($subtotal*14)/100;
                $span = '';
                $total = ($subtotal+$tax)-$discount_total;
                if(!empty($discount_txt))
                {
                    $span = '<p>Dicounts : </p>';
                    foreach($discount_txt as $d)
                     $span ='<p style="margin-left:20px"> '.$d.' </p>';
                }
                echo '<p>Subtotal : '.$subtotal.'</p>
                        <p>Taxes : '.$tax.'</p>
                        '.$span.'
                        <p>Total : '.$total.' </p>
                        
                ';
                die;
            }
            else
            {
            $sql="SELECT * from products where name = '$input[name]' ";
            $products = $conn->query($sql);
            }
            //echo $conn->error;
            if(!$products || empty($products->fetch_assoc()))
            throw new Exception("<p style='color:red'>Error:</p>please add product (".$input['name'].") first using following api: http://localhost/yashry_backend/api/add_product.php");
          }
          
          catch(Exception $e) {
            echo $e->getMessage();
        die;  
        }
       // echo $conn->error;
       $sql="SELECT * from products where name = '$input[name]' ";
       $products = $conn->query($sql);
       $subtotal = 0;
       $discount_txt = [];
       $discount_total = 0;
       $tax = 0;
      if($products)
      {
            if ($products->num_rows > 0) {

                echo '<ul>';
                while($row = $products->fetch_assoc()) {
                    $data['price'] = '';
                    $data['name'] = $row['name'];
                    $sql="SELECT * from currancy where product_id = '$row[id]' and currancy_type = '$input[currancy]'";
                        $currancy = $conn->query($sql);
                        if ($currancy) {
                            if ($currancy->num_rows > 0) {
                                $data['price'] = $currancy->fetch_assoc()['price'];
                            }
                            else
                            $data["price"] = '<span style="color:red"> Please add price value to this product from this api: http://localhost/yashry_backend/api/add_currancy.php</span>';
        
                        }
                        $tax = (($data['price']*14)/100);
                        $subtotal += $data['price'];
                        $price = $data['price'];
                        $data['price'] = $data['price'] + $tax;
                        //get offer if exist
                        $sql="SELECT * from offers where product_id = '$row[id]' LIMIT 0, 1";
                        $offer = $conn->query($sql);
                        $discount = '';
                        if ($offer) {

                            if ($offer->num_rows > 0) {
                                while($row_2 = $offer->fetch_assoc()) {
                                    if($row_2['offer_type'] == 1)//percentage type
                                    {
                                        $amount = ($data['price'] * $row_2['discount'])/100;
                                        $discount = $data['price'] - $amount;
                                        $discount_txt[] = $row_2['discount'].'%  off '.$data['name']. ': -'. $amount .' '.$input["currancy"];
                                        $discount_total += $amount;
                                    }
                                    else if($row_2['offer_type'] == 2)//amount type
                                    {
                                        $discount = $data['price'] - $row_2['discount'];
                                        $discount_txt[] = $row_2['discount'].' '.$input["currancy"].' off'.$data['name']. ': -'. $row_2['discount'] .' '.$input["currancy"];
                                        $discount_total += $amount;
                                    }
                                
                                   
                                }
                            }
                            else
                            $data["price"] = '<span style="color:red"> Please add price value to this product from this api: http://localhost/yashry_backend/api/add_currancy.php</span>';
        
                        }
                      
                        echo '
                                <li>
                                    '.$data["name"].' : '.$price.' '.$input["currancy"].'
                                </li>
                       ';
                    }
                    echo  '</ul>';
                    $tax = ($subtotal*14)/100;
                    $span = '';
                    $total = ($subtotal+$tax)-$discount_total;
                    if(!empty($discount_txt))
                    {
                        $span = '<p>Dicounts : </p>';
                        foreach($discount_txt as $d)
                         $span ='<p style="margin-left:20px"> '.$d.' </p>';
                    }
                    echo '<p>Subtotal : '.$subtotal.'</p>
                            <p>Taxes : '.$tax.'</p>
                            '.$span.'
                            <p>Total : '.$total.' </p>
                            
                    ';
          //  echo 'added successfully..!';
            }
        
      }

    }
}
$instance = new cart();
$conn = $instance->prepare();
$instance->create($conn);
?>