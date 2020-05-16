<?php

    return [
        '~^hello/(.*)$~' => [MyProject\Controllers\MainController::class, 'sayHello'],
        '~^$~' => [MyProject\Controllers\MainController::class, 'main'],
        '~^products/(\d+)$~' => [\MyProject\Controllers\ProductsController::class, 'view'], //\d означает любой цифровой символ, а '+' означает 1 или более раз
    ];