<?php
require_once 'servicesService.php';


$service = new servicesService();


$result = $service->createService(
    'BuzzCut',                 
    'A clean, short, low-maintenance haircut for a neat and sharp look',   
    20.5                      
);


print_r($result);


$resultInvalidName = $service->createService(
    '',                       
    'Some description',        
    15
);
print_r($resultInvalidName);


$resultLongDesc = $service->createService(
    'classic fade ',
    'A stylish haircut with a gradual fade from short to longer hair for a clean, modern look.',     
    15
);
print_r($resultLongDesc);


$resultNegativePrice = $service->createService(
    'slick back',
    'Hair is combed straight back for a sleek, polished, and timeless style.',
    -10
);
print_r($resultNegativePrice);
?>
