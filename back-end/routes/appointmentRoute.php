<?php 
/**
 * @OA\Post(
 *     path="/appointments",
 *     tags={"appointments"},
 *     summary="Kreiranje novog termina",
 *     description="Kreira novi termin. Sva polja su obavezna.",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "service_id", "stylist_id", "admin_id", "date"},
 *             @OA\Property(property="user_id", type="integer", example=5),
 *             @OA\Property(property="service_id", type="integer", example=2),
 *             @OA\Property(property="stylist_id", type="integer", example=3),
 *             @OA\Property(property="admin_id", type="integer", example=1),
 *             @OA\Property(property="date", type="string", example="2025-12-17 14:00:00")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Termin uspješno kreiran"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Nevalidna polja ili nedostaju podaci"
 *     )
 * )
 */
Flight::route('POST /appointments', function(){
    Flight::auth_middleware()->verifyToken(Flight::request()->headers['Authorization'] ?? null);
    Flight::auth_middleware()->authorizeRole(Roles::USER);
    $data = Flight::request()->data->getData();
    $result = Flight::appointmentsService()->createAppointment($data);
    Flight::json($result);
});


/**
 * @OA\Get(
 *     path="/admin/appointments",
 *     tags={"appointments"},
 *     summary="Dohvaćanje svih termina (samo za admina)",
 *     description="Vraća punu listu svih termina u sistemu.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Lista termina uspješno dohvaćena"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Nedozvoljen pristup"
 *     )
 * )
 */
Flight::route('GET /admin/appointments', function() {
    Flight::auth_middleware()->verifyToken(Flight::request()->headers['Authorization'] ?? null);
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $appointments = Flight::appointmentsService()->getAllAppointmentsForAdmin();
    Flight::json($appointments);
});
?>
