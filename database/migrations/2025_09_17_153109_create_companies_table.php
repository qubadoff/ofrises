<?php

use App\Enum\Company\CompanyStatusEnum;
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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('name');
            $table->unsignedBigInteger('work_area_id');
            $table->date('created_date');
            $table->string('location');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('phone');
            $table->string('email');
            $table->integer('employee_count')->default(0);
            $table->text('profile_photo');
            $table->longText('media')->nullable();
            $table->integer('status')->default(CompanyStatusEnum::PENDING->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
