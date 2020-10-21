<title>Add product</title>
<?php
include 'database.php';
    class add_product extends database
    {

        public function insert_query($conn)
        {
            $input = $_GET;
            $len = count($input);
            if($len != 1)
            {
                echo '<span style="color:black; font-size:2rem">Note:</span><p> please enter all input fields, name as follow:</p><li>name=pijama <p>Full example: http://localhost/yashry_backend/api/add_product.php?name=pjama</p></li>';
                die;
                //header("location:api.php");
            }
          //  print_r($input);
            $sql="INSERT INTO products (name) VALUES('$input[name]')";
            $r = $conn->query($sql);
            try{
                if(!$r)
                throw new Exception("<p style='color:red'>Error:</p>".$conn->error);
    
            }
            catch(Exception $e) {
                echo $e->getMessage();
            die;  
            }
            echo 'added successfully..!';

            //header("location:api.php");
        }
    }

    $instance = new add_product();
    $conn = $instance->prepare();
    $instance->insert_query($conn);
?>