<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add indexes for searchable columns
            $table->index('sku');
            $table->rawIndex("(JSON_EXTRACT(name, '$.en'))", 'products_name_en_index');
            $table->rawIndex("(JSON_EXTRACT(description, '$.en'))", 'products_description_en_index');

            // Add composite index for price and stock status
            $table->index(['price', 'is_in_stock']);

            // Add index for category
            $table->index('category_id');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_sku_index');
            $table->dropIndex('products_name_en_index');
            $table->dropIndex('products_description_en_index');
            $table->dropIndex('products_price_is_in_stock_index');
            $table->dropIndex('products_category_id_index');
        });
    }
};
