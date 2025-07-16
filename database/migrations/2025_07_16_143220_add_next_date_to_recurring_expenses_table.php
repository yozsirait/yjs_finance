<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recurring_expenses', function (Blueprint $table) {
            $table->date('next_date')->nullable()->after('interval');
        });
    }

    public function down(): void
    {
        Schema::table('recurring_expenses', function (Blueprint $table) {
            $table->dropColumn('next_date');
        });
    }

};
