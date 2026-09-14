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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('birth_name')->nullable();
            $table->string('matric_number')->unique();
            $table->string('passport_photo')->nullable();
            $table->string('school')->nullable();
            $table->string('faculty')->nullable();
            $table->string('department')->nullable();
            $table->string('programme')->nullable();
            $table->string('qualification')->nullable();
            $table->string('award')->nullable();
            $table->string('class_of_award')->nullable();
            $table->date('graduation_date')->nullable();
            $table->integer('graduation_year')->nullable();
            $table->string('academic_session')->nullable();
            $table->string('certificate_number')->unique();
            $table->string('verification_token')->unique();
            $table->enum('status', ['VALID', 'REVOKED', 'PENDING', 'ARCHIVED'])->default('VALID');
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
