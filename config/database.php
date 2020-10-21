<?php
class Database{
  
    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "cart_db";
    private $username = "root";
    private $password = "";
    public $conn;
  
    public function create_tables()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `products` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(32) NOT NULL,
            `description` text NOT NULL,
            `price` decimal(10,0) NOT NULL,
            `category_id` int(11) NOT NULL,
            `created` datetime NOT NULL,
            `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=65';
          $conn->query($sql);
    }
    // get the database connection
    public function getConnection(){
        
        $this->conn = null;
  
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
        create_tables();
  
        return $this->conn;
    }
}
$this->getConnection();
?>