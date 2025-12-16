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
        Schema::create('track123_couriers', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name_cn')->nullable();
            $table->string('name_en')->nullable();
            $table->string('home_page')->nullable();
            $table->timestamps();
        });

        Schema::create('track123_trackings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tracking_id')->nullable();
            $table->string('track_no');
            $table->string('courider_code')->nullable();
            $table->dateTime('create_time')->nullable();
            $table->dateTime('nextUpdateTime')->nullable();
            $table->string('ship_from')->nullable();
            $table->string('ship_to')->nullable();
            $table->string('tracking_status')->nullable();
            $table->string('transit_status')->nullable();
            $table->string('transit_sub_status')->nullable();
            $table->dateTime('order_time')->nullable();
            $table->dateTime('receipt_time')->nullable();
            $table->dateTime('delivered_time')->nullable();
            $table->dateTime('last_tracking_time')->nullable();
            $table->integer('delivered_days')->nullable();
            $table->integer('transit_days')->nullable();
            $table->integer('stay_days')->nullable();
            $table->string('shipment_type')->nullable();
            $table->text('exception')->nullable();
            $table->timestamps();
        });

        Schema::create('track123_local_logistics',function(Blueprint $table){
            $table->id();
            $table->foreignId('track123_tracking_id')->constrained('track123_trackings')->cascadeOnDelete();
            $table->string('courier_code')->nullable();
            $table->string('courier_name_cn')->nullable();
            $table->string('courier_name_en')->nullable();
            $table->string('courier_home_page')->nullable();
            $table->string('courier_tracking_link')->nullable();
            $table->timestamps();
        });

        Schema::create('track123_tracking_details',function(Blueprint $table){
            $table->id();
            $table->foreignId('track123_tracking_id')->constrained('track123_trackings')->cascadeOnDelete();
            $table->string('address')->nullable();
            $table->dateTime('event_time')->nullable();
            $table->dateTime('event_time_zero_utc')->nullable();
            $table->string('timezone')->nullable();
            $table->string('event_detail')->nullable();
            $table->string('transit_sub_status')->nullable();
            $table->timestamps();
        });

        Schema::create('track123_tracking_extra_info',function(Blueprint $table){
            $table->id();
            $table->foreignId('track123_tracking_id')->constrained('track123_trackings')->cascadeOnDelete();
            $table->smallInteger('pieces')->default(1);
            $table->string('signed_by')->nullable();
            $table->string('service_code') -> nullable();
            $table->json('dimensions')->nullable();
            $table->json('weight')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('track123_tracking_extra_info');
        Schema::dropIfExists('track123_tracking_details');
        Schema::dropIfExists('track123_local_logistics');
        Schema::dropIfExists('track123_trackings');
        Schema::dropIfExists('track123_couriers');
    }
};