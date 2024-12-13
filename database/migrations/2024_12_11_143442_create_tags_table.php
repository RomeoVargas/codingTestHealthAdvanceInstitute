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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        foreach ($this->getShards() as $shard) {
            Schema::connection($shard)->create('post_tags', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('post_id');
                $table->unsignedBigInteger('tag_id');

                // This pivot table is sharded along with the post
                $table->foreign('post_id')->references('id')->on('posts');

                // Foreign key for tag cannot be enforced because tags table is in another db
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->getShards() as $shard) {
            Schema::connection($shard)->dropIfExists('post_tags');
        }

        Schema::dropIfExists('tags');
    }
};
