<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('track123_trackings', function (Blueprint $table) {
            $table->string('order_no')->nullable()->after('nextUpdateTime');
            $table->string('custom1')->nullable()->after('delivered_days');
            $table->string('custom2')->nullable()->after('custom1');
            $table->text('remark')->nullable()->after('custom2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('track123_trackings', function (Blueprint $table) {
            $table->dropColumn('order_no');
            $table->dropColumn('custom1');
            $table->dropColumn('custom2');
            $table->dropColumn('remark');

        });
    }
};