<?php

namespace App\Service\HttpClient;

enum RequestMethodEnum: string
{
    case Post = 'post';
    case Get = 'get';
}
