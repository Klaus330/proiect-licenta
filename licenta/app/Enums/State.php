<?php
namespace App\Enums;

enum State : string
{
    case SUCCESS = 'success';
    case FAILED = 'failure';
    case PENDING = 'pending';
}