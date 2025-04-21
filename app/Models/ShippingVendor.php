<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;
use TomatoPHP\FilamentEcommerce\Models\ShippingVendor as ShippingVendorBase;

class ShippingVendor extends ShippingVendorBase
{
    use HasCompany;
}
