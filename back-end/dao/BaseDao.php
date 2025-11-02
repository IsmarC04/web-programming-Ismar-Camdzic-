<?php 
require_once '../config.php';

class BaseDao {
    protected $table;
    protected $connection;


    public function __construct($table){
       $this->table = $table;
        try {
            $this->connection = new PDO(
                "mysql:host=" . Config::DB_HOST() . ";dbname=" . Config::DB_NAME() . ";port=" . Config::DB_PORT(),
                Config::DB_USER(),
                Config::DB_PASSWORD(),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            throw $e;
        }

    }


    public function getAll(){
        $stm = $this->connection->prepare(" SELECT * FROM " . $this->table);
        $stm->execute();
        return $stm->FetchAll();
    }


    public function getById($id){
        $stm = $this->connection->prepare (" SELECT * FROM " . $this->table . " WHERE id=:id");
        $stm->execute();
        return $stm -> fetch();
    }

    public function insert($data){
        $columns = implode(",", array_keys($data));
        $placeholders = ":" . implode(", :" , array_keys($data));
        $sql = " INSERT INTO " . $this->table ."($columns) VALUES ($placeholders)";
        $stm = $this->connection->prepare($sql);
        return $stm->execute($data);


    }

    public function update($id, $data){
      

        $fields = "";
        
        foreach( $data as $key => $value){
            $fields .= "$key = :$key, ";
            

        }

        $fields = rtrim($fields, ", ");
        
        $sql = "UPDATE " . $this->table . " SET $fields WHERE id= :id";
        $stm = $this->connection->prepare($sql);
        $data['id'] = $id;
        return $stm -> execute($data);
    }


    public function delete($id){
        $stm  = $this->connection->prepare (" DELETE FROM " . $this->table . "WHERE id=:id");
        $stm->bindParm(':id', $id);
        return $stm->execute();
    }
}

?>