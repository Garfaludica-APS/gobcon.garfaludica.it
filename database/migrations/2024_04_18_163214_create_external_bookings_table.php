<?php

declare(strict_types=1);

/*
 * Copyright © 2024 - Garfaludica APS - MIT License
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('external_bookings', static function(Blueprint $table): void {
			$table->id();
			$table->foreignId('room_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
			$table->dateTime('checkin');
			$table->dateTime('checkout');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('external_bookings');
	}
};
