<?php
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
?>
