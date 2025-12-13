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
    Flight::json($result);
});

/**
 * @OA\Put(
 * path="/users/{id}/password",
 * tags={"users"},
 * summary="Promjena lozinke korisnika po ID-u",
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
    $data = Flight::request()->data->getData();
    $result = Flight::userService()->changePassword($id, $data['new_password'] ?? '');
    Flight::json($result);
});

/**
 * @OA\Get(
 * path="/users/{id}",
 * tags={"users"},
 * summary="Dohvaćanje korisnika po ID-u",
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * description="ID korisnika",
 * @OA\Schema(type="integer", example=1)
 * ),
 * @OA\Response(
 * response=200,
 * description="Podaci korisnika"
 * ),
 * @OA\Response(
 * response=404,
 * description="Korisnik nije pronađen"
 * )
 * )
 */
Flight::route('GET /users/@id', function($id){
    $result = Flight::userService()->getUserById($id);
    Flight::json($result);
});
?>