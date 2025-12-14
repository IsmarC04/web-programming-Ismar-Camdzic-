<?php
/**
 * @OA\Info(
 * title="API",
 * description="Web programming API",
 * version="1.0",
 * @OA\Contact(
 * email="web2001programming@gmail.com",
 * name="Web Programming"
 * )
 * ),
 * @OA\Server(
 * url=BASE_URL,
 * description="Lokalni API server"
 * )
 */

/**
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     in="header",
 *     name="Authorization",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 * )
 */

// --- START: DEFINICIJE MODELA PODATAKA (KOMPONENTE/ŠEME) ---

/**
 * @OA\Schema(
 * schema="UserResponse",
 * title="User Response Data",
 * description="Model podataka korisnika nakon logina/registracije",
 * @OA\Property(property="id", type="integer", example=1, description="ID korisnika"),
 * @OA\Property(property="first_name", type="string", example="Marko", description="Ime korisnika"),
 * @OA\Property(property="last_name", type="string", example="Marković", description="Prezime korisnika"),
 * @OA\Property(property="email", type="string", format="email", example="marko@example.com", description="Email adresa"),
 * @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiI...", description="Auth token (ako se šalje nakon logina)")
 * )
 */
/**
 * @OA\Schema(
 * schema="LoginRequest",
 * title="Login Request Data",
 * required={"email", "password"},
 * @OA\Property(property="email", type="string", format="email", example="marko@example.com", description="Email adresa"),
 * @OA\Property(property="password", type="string", format="password", example="tajnalozinka", description="Lozinka")
 * )
 */
/**
 * @OA\Schema(
 * schema="ChangePasswordRequest",
 * title="Change Password Request Data",
 * required={"new_password"},
 * @OA\Property(property="new_password", type="string", format="password", example="novalozinka123", description="Nova lozinka (minimalno 8 karaktera)")
 * )
 */

// --- END: DEFINICIJE MODELA PODATAKA ---

