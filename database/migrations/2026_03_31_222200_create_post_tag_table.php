<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * Tabel pivot untuk relasi Many-to-Many antara Post dan Tag.
     * - cascadeOnDelete() memastikan data pivot otomatis terhapus
     *   jika Post atau Tag yang terkait dihapus.
     * - primary(['post_id', 'tag_id']) mencegah duplikasi relasi
     *   (satu post tidak bisa punya tag yang sama dua kali).
     */
    public function up(): void
    {
        Schema::create('post_tag', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['post_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_tag');
    }
};
