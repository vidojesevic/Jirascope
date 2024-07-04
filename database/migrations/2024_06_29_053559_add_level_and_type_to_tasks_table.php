<?php

use App\Enums\Level;
use App\Enums\Type;
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
        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('level', array_column(Level::cases(), 'value'))->default(Level::Low->value);
            $table->enum('type', array_column(Type::cases(), 'value'))->default(Type::Task->value);
            $table->unsignedBigInteger('parent_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('level');
            $table->dropColumn('status');
            $table->dropColumn('parent_id');
        });
    }
};
