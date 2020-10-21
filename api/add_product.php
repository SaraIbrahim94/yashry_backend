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
            try{
           
                if (!array_key_exists("name",$input))
                throw new Exception("<span style='color:black; font-size:2rem'>Note:</span><p> please enter all input fields, name as follow:</p><li>name=pijama <p>Full example: http://localhost/yashry_backend/api/add_product.php?name=pjama</p></li>");
                else if($input['name']=='')
                throw new Exception("<span style='color:black; font-size:2rem'>Note:</span> <p><strong> Please make sure all input has values </strong></p><p> please enter all input fields, name as follow:</p><li>name=pijama <p>Full example: http://localhost/yashry_backend/api/add_product.php?name=pjama</p></li>");

                $sql="INSERT INTO products (name) VALUES('$input[name]')";
                $r = $conn->query($sql);
                
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