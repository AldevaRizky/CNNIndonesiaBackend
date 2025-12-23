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
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_photo_path')->nullable()->after('remember_token');
            $table->string('jabatan')->nullable()->after('profile_photo_path');
            $table->string('phone')->nullable()->after('jabatan');
            $table->string('alamat')->nullable()->after('phone');
            $table->text('bio')->nullable()->after('alamat');
            $table->string('website')->nullable()->after('bio');
            $table->string('twitter')->nullable()->after('website');
            $table->string('facebook')->nullable()->after('twitter');
            $table->string('instagram')->nullable()->after('facebook');
            $table->date('dob')->nullable()->after('instagram');
            $table->string('gender')->nullable()->after('dob');
            $table->string('country')->nullable()->after('gender');
            $table->string('city')->nullable()->after('country');
            $table->string('state')->nullable()->after('city');
            $table->string('zip')->nullable()->after('state');
            $table->string('address_line')->nullable()->after('zip');
            $table->string('role')->nullable()->after('address_line');
            $table->boolean('is_active')->default(true)->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profile_photo_path', 'jabatan', 'phone', 'alamat', 'bio', 'website',
                'twitter', 'facebook', 'instagram', 'dob', 'gender', 'country', 'city',
                'state', 'zip', 'address_line', 'role', 'is_active'
            ]);
        });
    }
};
