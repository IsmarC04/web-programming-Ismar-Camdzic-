<?php
/**
 * @OA\Tag(
 *     name="users",
 *     description="Operacije vezane za korisnike"
 * )
 */

/**
 * @OA\Get(
 *     path="/admin/user",
 *     tags={"users"},
 *     summary="Dohvatanje svih korisnika",
 *     description="Vraća listu svih korisnika u sistemu. Samo za admina.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Lista korisnika uspješno dohvaćena"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Nedozvoljen pristup"
 *     )
 * )
 */
Flight::route('GET /admin/user', function(){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    
    Flight::json(Flight::userService()->getAllUsers());
});

/**
 * @OA\Get(
 *     path="/admin/user/{id}",
 *     tags={"users"},
 *     summary="Dohvatanje korisnika po ID-u",
 *     description="Vraća detalje jednog korisnika po ID-u. Samo za admina.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID korisnika",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Korisnik uspješno dohvaćen"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Korisnik nije pronađen"
 *     )
 * )
 */
Flight::route('GET /admin/user/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::userService()->get_user_by_id($id));
});

/**
 * @OA\Delete(
 *     path="/admin/user/{id}",
 *     tags={"users"},
 *     summary="Brisanje korisnika po ID-u",
 *     description="Briše korisnika iz sistema. Samo za admina.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID korisnika koji se briše",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Korisnik uspješno obrisan",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="User deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Korisnik nije pronađen",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="User not found")
 *         )
 *     )
 * )
 */
Flight::route('DELETE /admin/user/@id', function($id){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $result = Flight::userService()->delete_user_by_id($id);
    Flight::json($result);
});

/**
 * @OA\Get(
 *     path="/admin/appointments",
 *     tags={"appointments"},
 *     summary="Dohvatanje svih termina",
 *     description="Vraća punu listu svih termina u sistemu. Samo za admina.",
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
Flight::route('GET /admin/appointments', function(){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $appointments = Flight::appointmentService()->getAllAppointmentsForAdmin();
    Flight::json($appointments);
});
