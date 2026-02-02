<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPerformanceIndexesToAdminTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Indexes for vaccinations table - frequently queried by vaccine_id and dose_number
        Schema::table('vaccinations', function (Blueprint $table) {
            $table->index('vaccine_id', 'idx_vaccinations_vaccine_id');
            $table->index('dose_number', 'idx_vaccinations_dose_number');
            $table->index(['vaccine_id', 'dose_number'], 'idx_vaccinations_vaccine_dose');
            $table->index('client_id', 'idx_vaccinations_client_id');
            $table->index('facility_id', 'idx_vaccinations_facility_id');
            $table->index('certificate_id', 'idx_vaccinations_certificate_id');
            $table->index('date', 'idx_vaccinations_date');
        });

        // Indexes for certificates table - frequently joined with clients
        Schema::table('certificates', function (Blueprint $table) {
            $table->index('client_id', 'idx_certificates_client_id');
            $table->index('certificate_uuid', 'idx_certificates_uuid');
            $table->index('certificate_status', 'idx_certificates_status');
            $table->index('created_at', 'idx_certificates_created_at');
        });

        // Indexes for clients table - frequently queried by identification numbers
        Schema::table('clients', function (Blueprint $table) {
            $table->index('NRC', 'idx_clients_nrc');
            $table->index('passport_number', 'idx_clients_passport');
            $table->index('drivers_license', 'idx_clients_drivers_license');
            $table->index('contact_email_address', 'idx_clients_email');
            $table->index(['last_name', 'first_name'], 'idx_clients_name');
        });

        // Indexes for users table
        Schema::table('users', function (Blueprint $table) {
            $table->index('role_id', 'idx_users_role_id');
            $table->index('client_id', 'idx_users_client_id');
            $table->index('created_at', 'idx_users_created_at');
        });

        // Indexes for facilities, districts, provinces for joins
        Schema::table('facilities', function (Blueprint $table) {
            $table->index('district_id', 'idx_facilities_district_id');
            $table->index('name', 'idx_facilities_name');
        });

        Schema::table('districts', function (Blueprint $table) {
            $table->index('province_id', 'idx_districts_province_id');
            $table->index('name', 'idx_districts_name');
        });

        Schema::table('provinces', function (Blueprint $table) {
            $table->index('name', 'idx_provinces_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop indexes from vaccinations
        Schema::table('vaccinations', function (Blueprint $table) {
            $table->dropIndex('idx_vaccinations_vaccine_id');
            $table->dropIndex('idx_vaccinations_dose_number');
            $table->dropIndex('idx_vaccinations_vaccine_dose');
            $table->dropIndex('idx_vaccinations_client_id');
            $table->dropIndex('idx_vaccinations_facility_id');
            $table->dropIndex('idx_vaccinations_certificate_id');
            $table->dropIndex('idx_vaccinations_date');
        });

        // Drop indexes from certificates
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropIndex('idx_certificates_client_id');
            $table->dropIndex('idx_certificates_uuid');
            $table->dropIndex('idx_certificates_status');
            $table->dropIndex('idx_certificates_created_at');
        });

        // Drop indexes from clients
        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex('idx_clients_nrc');
            $table->dropIndex('idx_clients_passport');
            $table->dropIndex('idx_clients_drivers_license');
            $table->dropIndex('idx_clients_email');
            $table->dropIndex('idx_clients_name');
        });

        // Drop indexes from users
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_role_id');
            $table->dropIndex('idx_users_client_id');
            $table->dropIndex('idx_users_created_at');
        });

        // Drop indexes from facilities, districts, provinces
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropIndex('idx_facilities_district_id');
            $table->dropIndex('idx_facilities_name');
        });

        Schema::table('districts', function (Blueprint $table) {
            $table->dropIndex('idx_districts_province_id');
            $table->dropIndex('idx_districts_name');
        });

        Schema::table('provinces', function (Blueprint $table) {
            $table->dropIndex('idx_provinces_name');
        });
    }
}
