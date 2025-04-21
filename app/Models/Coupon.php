<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;
use TomatoPHP\FilamentEcommerce\Models\Coupon as CouponBase;

class Coupon extends CouponBase
{
    use HasCompany;

    //
}
