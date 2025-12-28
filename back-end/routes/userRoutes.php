<?php 
/**
 * @OA\Post(
 * path="/register",
 * tags={"auth"},
 * summary="Registracija novog korisnika",
 * @OA\RequestBody(
 * required=true,
 * @OA\JsonContent(
 * required={"first_name", "last_name", "email", "password"},
 * @OA\Property(property="first_name", type="string", example="Ismar"),
 * @OA\Property(property="last_name", type="string", example="Camdzic"),
 * @OA\Property(property="email", type="string", format="email", example="ismar@example.com"),
 * @OA\Property(property="password", type="string", format="password", example="tajnalozinka123")
 * )
 * ),
 * @OA\Response(
 * response=200,
 * description="Uspješna registracija (vraća UserResponse model)"
 * ),
 * @OA\Response(
 * response=400,
 * description="Nisu dostavljena sva potrebna polja ili email već postoji"
 * )
 * )
 */
Flight::route('POST /register', function(){
    $data = Flight::request()->data->getData();
    $result = Flight::userService()->register($data);
    Flight::json($result);
});

/**
 * @OA\Post(
 * path="/login",
 * tags={"auth"},
 * summary="Prijava korisnika",
 * @OA\RequestBody(
 * required=true,
 * @OA\JsonContent(
 * required={"email", "password"},
 * @OA\Property(property="email", type="string", format="email", example="ismar@example.com"),
 * @OA\Property(property="password", type="string", format="password", example="tajnalozinka123")
 * )
 * ),
 * @OA\Response(
 * response=200,
 * description="Uspješna prijava (vraća token)"
 * ),
 * @OA\Response(
 * response=401,
 * description="Pogrešan email ili lozinka"
 * )
 * )
 */
Flight::route('POST /login', function(){
    $data = Flight::request()->data->getData();
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;
    $result = Flight::userService()->login($email, $password);
    Flight::json($result, $result['success'] ? 200 : 401);
});

/**
 * @OA\Put(
 * path="/users/{id}/password",
 * tags={"users"},
 * summary="Promjena lozinke korisnika po ID-u",
 * security={{"bearerAuth":{}}},
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * description="ID korisnika",
 * @OA\Schema(type="integer", example=1)
 * ),
 * @OA\RequestBody(
 * required=true,
 * @OA\JsonContent(
 * required={"new_password"},
 * @OA\Property(property="new_password", type="string", format="password", example="nova_tajna_lozinka")
 * )
 * ),
 * @OA\Response(
 * response=200,
 * description="Lozinka uspješno promijenjena"
 * )
 * )
 */
Flight::route('PUT /users/@id/password', function($id){
     Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);

    Flight::auth_middleware()->authorizeCurrentUserOrAdmin($id);

    $data = Flight::request()->data->getData();
    $result = Flight::userService()->changePassword($id, $data['new_password'] ?? '');
    Flight::json($result);
});



/**
 * @OA\Put(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Ažuriranje korisnika po ID-u",
 *     description="Omogućava korisniku ili adminu da ažurira korisničke podatke.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID korisnika koji se ažurira",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","status"},
 *             @OA\Property(property="name", type="string", example="Jane Doe"),
 *             @OA\Property(property="status", type="string", example="active")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Korisnik uspješno ažuriran",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="User updated successfully")
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
Flight::route('PUT /users/@id', function($id){
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::auth_middleware()->authorizeCurrentUserOrAdmin($id);
    $data = Flight::request()->data->getData(); // očekuje name i status iz frontend-a

    $success = Flight::userService()->update($id, $data);

    if($success){
        Flight::json(['success'=>true,'message'=>'User updated successfully']);
    } else {
        Flight::json(['success'=>false,'message'=>'User not found']);
    }
});

/**
 * @OA\Get(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Dohvati korisnika po ID-u",
 *     description="Omogućava korisniku ili adminu da dohvatiti podatke korisnika po ID-u.",
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
 *         description="Korisnik pronađen",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Jane Doe"),
 *                 @OA\Property(property="status", type="string", example="active")
 *             )
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
Flight::route('GET /users/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::auth_middleware()->authorizeCurrentUserOrAdmin($id);
    Flight::json(Flight::userService()->get_user_by_id($id));
});

// Health check for root so https://your-app.onrender.com returns 200
Flight::route('GET /', function(){
    Flight::json(['success' => true, 'message' => 'API is running']);
});

// Optional health check if someone pings /back-end/
Flight::route('GET /back-end/', function(){
    Flight::json(['success' => true, 'message' => 'API is running (back-end path)']);
});

?>