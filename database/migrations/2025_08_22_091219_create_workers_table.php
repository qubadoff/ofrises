<?php

use App\Enum\Worker\WorkerStatusEnum;
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
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            {
                $table->string('work_type_id')->nullable();
            }
            {
                $table->text('location')->nullable();
                $table->text('latitude')->nullable();
                $table->text('longitude')->nullable();
            }
            {
                $table->bigInteger('salary_min')->nullable();
                $table->bigInteger('salary_max')->nullable();
                $table->integer('currency_id')->nullable();
                $table->integer('salary_type_id')->nullable();
            }
            {
                $table->string('work_expectation_id')->nullable();
            }
            {
                $table->string('citizenship_id')->nullable();
            }
            {
                $table->text('birth_place')->nullable();
            }
            {
                $table->integer('marital_status_id')->nullable();
            }
            {
                $table->integer('height')->nullable();
                $table->integer('weight')->nullable();
            }
            {
                $table->string('military_status_id')->nullable();
            }
            {
                $table->boolean('have_a_child')->nullable();
            }
            {
                $table->string('driver_license_id')->nullable();
                $table->string('car_model_id')->nullable();
            }
            {
                $table->text('description')->nullable();
            }
            {
                $table->string('hobby_id')->nullable();
            }
            {
                $table->string('hard_skill_id')->nullable();
                $table->string('soft_skill_id')->nullable();
            }
            $table->integer('status')->default(WorkerStatusEnum::PENDING->value);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worker_requests');
    }
};
