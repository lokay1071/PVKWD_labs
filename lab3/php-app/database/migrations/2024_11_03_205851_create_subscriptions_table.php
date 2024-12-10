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
		Schema::create('subscriptions', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('subscriber_id');
			$table->string('service');
			$table->string('topic');
			$table->json('payload')->nullable();
			$table->timestamp('expired_at')->nullable();
			$table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade');
			$table->timestamps();
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
