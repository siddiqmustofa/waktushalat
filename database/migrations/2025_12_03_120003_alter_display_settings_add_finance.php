<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('display_settings', function (Blueprint $table) {
            $table->decimal('infaq_jumat', 14, 2)->default(0)->after('tarawih_duration_minutes');
            $table->decimal('infaq_langsung', 14, 2)->default(0)->after('infaq_jumat');
            $table->decimal('saldo_kas', 14, 2)->default(0)->after('infaq_langsung');
            $table->decimal('saldo_bank_syariah', 14, 2)->default(0)->after('saldo_kas');
        });
    }

    public function down(): void
    {
        Schema::table('display_settings', function (Blueprint $table) {
            $table->dropColumn([
                'infaq_jumat',
                'infaq_langsung',
                'saldo_kas',
                'saldo_bank_syariah',
            ]);
        });
    }
};

