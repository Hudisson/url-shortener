<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('short_urls', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable();
        });

        DB::table('short_urls')
            ->select(['id', 'created_at'])
            ->orderBy('id')
            ->chunkById(100, function ($shortUrls): void {
                foreach ($shortUrls as $shortUrl) {
                    DB::table('short_urls')
                        ->where('id', $shortUrl->id)
                        ->update([
                            'expires_at' => Carbon::parse($shortUrl->created_at)
                                ->addYear()
                                ->toDateTimeString(),
                        ]);
                }
            });

        Schema::table('short_urls', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('short_urls', function (Blueprint $table) {
            $table->dropColumn('expires_at');
        });
    }
};
