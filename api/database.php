<?php

class database
{
    private $host = "localhost";
    private $db_name = "cart_db";
    private $username = "root";
    private $password = "";
    public $conn;

    public function prepare()
    {
        $input = $_GET;
        $serv="localhost";
        $user="root";
        $passw="";
        $db="cart_db";
      //  echo $input['name'];
        $conn=mysqli_connect($serv,$user,$passw,$db);

        if(!$conn)
            die("Error".mysqli_connection_error);
           
        $this->create_tables($conn);
        return $conn;
        mysqli_close($conn);

    }

    public function create_tables($conn)
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `products` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(32)  NULL,
            `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            CONSTRAINT name_unique UNIQUE (name)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1';
        $conn->query($sql);

         //offer type if 1->amount, 2->percentage, 3->buy 1 get 50% off, if type 3
        // then add product id in discount column to apply discount on
       //if type is 2 then add amount in discount colmun
       $sql = 'CREATE TABLE IF NOT EXISTS `offers` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `offer_type` int(11) NOT NULL,
        `discount` int(11) NOT NULL,
        `product_id` int(11) NOT NULL,
        `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1';
      $conn->query($sql);

      $sql = 'CREATE TABLE IF NOT EXISTS `currancy` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `product_id` int(11) NOT NULL,
        `price` float(11) NOT NULL,
        `currancy_type` varchar(19) NOT NULL,
        `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1';
      $conn->query($sql);
    }
}

?>