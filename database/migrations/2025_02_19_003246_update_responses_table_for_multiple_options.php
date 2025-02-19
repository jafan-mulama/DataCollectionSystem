<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('responses', function (Blueprint $table) {
            // First, delete all existing responses as we're changing the structure
            DB::table('responses')->delete();
            
            // Remove the old option_id column
            $table->dropForeign(['option_id']);
            $table->dropColumn('option_id');
            
            // Add the new selected_options column
            $table->json('selected_options')->after('question_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('responses', function (Blueprint $table) {
            // Remove the new column
            $table->dropColumn('selected_options');
            
            // Add back the old column
            $table->foreignId('option_id')->constrained()->onDelete('cascade');
        });
    }
};
