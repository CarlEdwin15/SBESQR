<?php

// namespace App\Actions\Fortify;

// use App\Models\User;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;
// use Laravel\Fortify\Contracts\CreatesNewUsers;
// use Laravel\Jetstream\Jetstream;

// class CreateNewUser implements CreatesNewUsers
// {
//     use PasswordValidationRules;

//     /**
//      * Validate and create a newly registered user.
//      *
//      * @param  array<string, string>  $input
//      */
//     public function create(array $input): User
//     {
//         Validator::make($input, [
//             'firstName' => ['required', 'string', 'max:255'],
//             // 'lastName' => ['required', 'string', 'max:255'],
//             // 'middleName' => ['required', 'string', 'max:2'],
//             'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//             // 'phone' => ['required', 'string', 'max:20'], // Add this
//             // 'address' => ['required', 'string', 'max:255'], // Add this
//             'password' => $this->passwordRules(),
//         ])->validate();



//         return User::create([
//             'firstName' => $input['firstName'],
//             'lastName' => $input['lastName'],
//             'middleName' => $input['middleName'],
//             'email' => $input['email'],
//             'phone' => $input['phone'],
//             'address' => $input['address'],
//             'password' => Hash::make($input['password']),
//             'role' => 'teacher', // Ensure user is registered as a teacher
//         ]);

//     }
// }
