<?php

return [
    // authentication exceptions
    '10000' => [
        'message' => 'User is already exists or have been registered.',
        'description' => 'User is registered, create a new unique user credential. Try different email or reset your password',
    ],
    '10001' => [
        'message' => 'Invalid User credentials',
        'description' => 'Wrong password or email credential entered.', 
    ],
    '10002' => [
        'message' => 'Email is already taken',
        'description' => 'Email has been taken or registered.', 
    ],
    '10003' => [
        'message' => 'Incorrect password',
        'description' => 'Password is incorrect or try resetting password.', 
    ],
    '10004' => [
        'message' => 'Retype again password',
        'description' => 'Wrong retyped password.', 
    ],
    '10005' => [
        'message' => 'User registration failed',
        'description' => 'Something went wrong or try again later.', 
    ],
    '10006' => [
        'message' => 'User signing in failed',
        'description' => 'Something went wrong or try again later.', 
    ],
    '10007' => [
        'message' => 'User not found',
        'description' => 'It seems that this account does not exists.', 
    ],
    '10008' => [
        'message' => 'User is not authenticated',
        'description' => 'User logout not allowed.', 
    ],
    '10009' => [
        'message' => 'Signing out failed',
        'description' => 'Something went wrong or try again later.', 
    ],
    '10010' => [
        'message' => 'Signing In Failed',
        'description' => 'Your account had been Disabled.', 
    ],

    // products
    '12000' => [
        'message' => 'Product is not found',
        'description' => 'Product does not exists or not found.',
    ],
    '12001' => [
        'message' => 'Product is already added',
        'description' => 'Product already exists or created.',
    ],
    '12002' => [
        'message' => 'Product cannot be added',
        'description' => 'Product cant be added or there is something wrong with the syntax.',
    ],
    '12003' => [
        'message' => 'Product creation failed',
        'description' => 'Something went wrong creating the product.',
    ],
    '12004' => [
        'message' => 'Product validation failed',
        'description' => 'Product incorrect inputs, try changing some inputs.',
    ],
    '12005' => [
        'message' => 'Product update failed',
        'description' => 'Something went wrong while updating the product.',
    ],
    '12006' => [
        'message' => 'Product deletion failed',
        'description' => 'Something went wrong while deleting the product.',
    ],

    // products
    '13000' => [
        'message' => 'Garment is not found',
        'description' => 'Garment does not exists or not found.',
    ],
    '13001' => [
        'message' => 'Garment is already added',
        'description' => 'Garment already exists or created.',
    ],
    '13002' => [
        'message' => 'Garment cannot be added',
        'description' => 'Garment cant be added or there is something wrong with the syntax.',
    ],
    '13003' => [
        'message' => 'Garment creation failed',
        'description' => 'Something went wrong creating the Garment.',
    ],
    '13004' => [
        'message' => 'Garment validation failed',
        'description' => 'Garment incorrect inputs, try changing some inputs.',
    ],
    '13005' => [
        'message' => 'Garment update failed',
        'description' => 'Something went wrong while updating the Garment.',
    ],
    '13006' => [
        'message' => 'Garment deletion failed',
        'description' => 'Something went wrong while deleting the Garment.',
    ],
]; 