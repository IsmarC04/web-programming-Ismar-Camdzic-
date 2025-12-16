<?php
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
?>
