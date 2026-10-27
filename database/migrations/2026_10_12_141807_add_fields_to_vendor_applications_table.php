<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_applications', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('business_name');
            $table->string('business_type')->nullable();
            $table->string('contact_name');
            $table->string('contact_phone', 20);
            $table->string('contact_email');
            $table->text('business_address');
            $table->string('city')->default('Lagos');
            $table->text('business_description')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
            ])->default('pending');

            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('vendor_applications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['status', 'created_at']);

            $table->dropColumn([
                'user_id',
                'business_name',
                'business_type',
                'contact_name',
                'contact_phone',
                'contact_email',
                'business_address',
                'city',
                'business_description',
                'status',
                'admin_notes',
                'reviewed_at',
            ]);
        });
    }
};