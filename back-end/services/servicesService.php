<?php 
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/servicesDao.php';

class servicesService extends BaseService{
    public function __construct(){
        $dao = new servicesDao;
        parent::__construct($dao);
    }

    public function createService($name, $description, $price){
        if (empty($name) || strlen($name) > 100){
            return[
                'success' => false,
                'message' => 'Invalid name'
            
            ];
        }


        if(!empty($description) && strlen($description) > 500){
            return[
                'success' => false,
                'message' => 'Description too long'
            ];
        }


        if(!is_numeric($price) || $price<0){
            return [
                'success' => false,
                'message' => 'Price must be positive number'
            ];
        }

        $price = (float)$price;
        


        $this->create([
            'name' => $name,
            'description' => $description,
            'price' => $price
        ]);

        return[
            'success' => true, 
            'message'=> 'New service registred successfully!'
        ];


    }
}

?>
