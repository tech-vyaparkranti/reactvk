<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;

    protected $table = "contact_us";

    const ID = "id";
    const NAME = "name";
    const EMAIL = "email";
    const PHONE_NUMBER = "phone";
    const MESSAGE = "message";
    const SUBJECT = 'subject';
    const IP_ADDRESS = "ip_address";
    const CREATED_AT = 'created_at';
}
