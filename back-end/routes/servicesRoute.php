<?php

/**
 * @OA\Get(
 *     path="/services",
 *     tags={"services"},
 *     summary="Dohvatanje svih usluga",
 *     description="Vraća listu svih usluga dostupnih u sistemu. Nije potrebna autentikacija.",
 *     @OA\Response(
 *         response=200,
 *         description="Lista usluga",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="success",
 *                 type="boolean",
 *                 example=true
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Haircut"),
 *                     @OA\Property(property="description", type="string", example="Standard haircut service"),
 *                     @OA\Property(property="price", type="number", format="float", example=15.00)
 *                 )
 *             )
 *         )
 *     )
 * )
 */
Flight::route('GET /services', function(){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $result = Flight::servicesService()->getAllServices();
    Flight::json($result);
});

/**
 * @OA\Get(
 *     path="/admin/services/{id}",
 *     tags={"services"},
 *     summary="Dohvatanje usluge po ID-u",
 *     description="Vraća detalje određene usluge po ID-u. Samo za admina.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID usluge",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detalji usluge",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Haircut"),
 *                 @OA\Property(property="description", type="string", example="Standard haircut service"),
 *                 @OA\Property(property="price", type="number", format="float", example=15.00)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Usluga nije pronađena",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Service not found")
 *         )
 *     )
 * )
 */
Flight::route('GET /admin/services/@id', function($id){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $result = Flight::servicesService()->getServiceById($id);
    Flight::json($result);
});

/**
 * @OA\Delete(
 *     path="/admin/services/{id}",
 *     tags={"services"},
 *     summary="Brisanje usluge po ID-u",
 *     description="Briše određenu uslugu iz sistema. Samo za admina.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID usluge koja se briše",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Usluga uspješno obrisana",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Service deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Usluga nije pronađena",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Service not found")
 *         )
 *     )
 * )
 */
Flight::route('DELETE /admin/services/@id', function($id){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    
    $success = Flight::servicesService()->delete($id);

    if($success){
        Flight::json(['success'=>true,'message'=>'Service deleted successfully']);
    } else {
        Flight::json(['success'=>false,'message'=>'Service not found']);
    }
});

/**
 * @OA\Put(
 *     path="/admin/services/{id}",
 *     tags={"services"},
 *     summary="Ažuriranje usluge po ID-u",
 *     description="Ažurira naziv i status usluge. Samo za admina.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID usluge koja se ažurira",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","status"},
 *             @OA\Property(property="name", type="string", example="Haircut"),
 *             @OA\Property(property="status", type="string", example="active")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Usluga uspješno ažurirana",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Service updated successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Usluga nije pronađena",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Service not found")
 *         )
 *     )
 * )
 */
Flight::route('PUT /admin/services/@id', function($id){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData(); // očekuje name i status iz frontend-a

    $success = Flight::servicesService()->update($id, $data);

    if($success){
        Flight::json(['success'=>true,'message'=>'Service updated successfully']);
    } else {
        Flight::json(['success'=>false,'message'=>'Service not found']);
    }
});

/**
 * @OA\Post(
 *     path="/admin/services",
 *     tags={"services"},
 *     summary="Kreiranje nove usluge",
 *     description="Dodaje novu uslugu u sistem. Samo za admina.",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","description","price"},
 *             @OA\Property(property="name", type="string", example="Haircut"),
 *             @OA\Property(property="description", type="string", example="Standard haircut service"),
 *             @OA\Property(property="price", type="number", format="float", example=15.00)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Usluga uspješno kreirana",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Service created successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Neispravni podaci",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Invalid input")
 *         )
 *     )
 * )
 */
Flight::route('POST /admin/services', function(){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData(); // očekuje name, description i price
    
    $success = Flight::servicesService()->createService($data['name'], $data['description'], $data['price']);

    if($success){
        Flight::json(['success'=>true,'message'=>'Service created successfully']);
    } else {
        Flight::json(['success'=>false,'message'=>'Service not found']);
    }
});


