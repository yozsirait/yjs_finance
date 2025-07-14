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
        Schema::create('recurring_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type')->default('pengeluaran'); // fix untuk saat ini
            $table->string('category');
            $table->foreignId('account_id')->nullable();
            $table->foreignId('member_id')->nullable();
            $table->bigInteger('amount');
            $table->string('description')->nullable();
            $table->date('start_date');
            $table->enum('interval', ['harian', 'mingguan', 'bulanan', 'tahunan']);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_expenses');
    }
};
