<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'iuhm';

    public function up(): void
    {
        Schema::connection('iuhm')->create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('iuhm')->dropIfExists('newsletter_subscribers');
    }
};
