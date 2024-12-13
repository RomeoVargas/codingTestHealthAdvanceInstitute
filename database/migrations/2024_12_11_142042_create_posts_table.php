<?php

use App\Traits\Sharding;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use Sharding;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->getShards() as $shard) {
            Schema::connection($shard)->create('posts', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('description')->nullable()->default(null);
                $table->unsignedBigInteger('user_id');
                $table->timestamps();

                // Foreign key for user cannot be enforced because users table is in another db
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->getShards() as $shard) {
            Schema::connection($shard)->dropIfExists('posts');
        }
    }
};
