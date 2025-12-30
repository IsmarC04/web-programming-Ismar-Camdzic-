<?php
/**
 * @OA\Get(
 *     path="/stylists",
 *     tags={"stylists"},
 *     summary="Dohvati sve stiliste",
 *     description="Vraća listu svih stilista",
 *     @OA\Response(
 *         response=200,
 *         description="Lista stilista",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="John Doe"),
 *                     @OA\Property(property="bio", type="string", example="Experienced barber")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
Flight::route('GET /stylists', function(){
    $result = Flight::stylistService()->getAllStylists();
    Flight::json($result);
});

/**
 * @OA\Get(
 *     path="/admin/stylists/{id}",
 *     tags={"stylists"},
 *     summary="Dohvati stilistu po ID-u",
 *     description="Vraća jednog stilistu po ID-u. Samo za admina.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID stiliste",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Stilist pronađen",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="John Doe"),
 *                 @OA\Property(property="bio", type="string", example="Experienced barber")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Stilist nije pronađen",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Stylist not found")
 *         )
 *     )
 * )
 */
Flight::route('GET /admin/stylists/@id', function($id){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $result = Flight::stylistService()->getStylistById($id);
    Flight::json($result);
});

/**
 * @OA\Delete(
 *     path="/admin/stylists/{id}",
 *     tags={"stylists"},
 *     summary="Brisanje stiliste po ID-u",
 *     description="Briše stilistu iz sistema. Samo za admina.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID stiliste koji se briše",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Stilist uspješno obrisan",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Stylist deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Stilist nije pronađen",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Stylist not found")
 *         )
 *     )
 * )
 */
Flight::route('DELETE /admin/stylists/@id', function($id){

    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $success = Flight::stylistService()->delete($id);
    
    if($success){
        Flight::json(['success'=>true,'message'=>'Stylist deleted successfully']);
    } else {
        Flight::json(['success'=>false,'message'=>'Stylist not found']);
    }
});


/**
 * @OA\Put(
 *     path="/admin/stylists/{id}",
 *     tags={"stylists"},
 *     summary="Ažuriranje stiliste po ID-u",
 *     description="Ažurira ime i status stiliste. Samo za admina.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID stiliste koji se ažurira",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","status"},
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="status", type="string", example="active")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Stilist uspješno ažuriran",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Stylist updated successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Stilist nije pronađen",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Stylist not found")
 *         )
 *     )
 * )
 */
Flight::route('PUT /admin/stylists/@id', function($id){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData(); // očekuje name i status iz frontend-a

    $success = Flight::stylistService()->update($id, $data);

    if($success){
        Flight::json(['success'=>true,'message'=>'Stylist updated successfully']);
    } else {
        Flight::json(['success'=>false,'message'=>'Stylist not found']);
    }
});


/**
 * @OA\Post(
 *     path="/admin/stylists",
 *     tags={"stylists"},
 *     summary="Kreira novog stilistu",
 *     description="Dodaje novog stilistu u sistem. Samo za admina.",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","bio"},
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="bio", type="string", example="Experienced barber")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Stilist uspješno kreiran",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Stylist created successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Greška pri kreiranju stiliste",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Failed to create stylist")
 *         )
 *     )
 * )
 */
Flight::route('POST /admin/stylists', function(){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData(); // očekuje name i bio iz frontend-a
    
    $success = Flight::stylistService()->createStylist($data['name'], $data['bio']);

    if($success){
        Flight::json(['success'=>true,'message'=>'Stylist created successfully']);
    } else {
        Flight::json(['success'=>false,'message'=>'Stylist not found']);
    }
});


?>
