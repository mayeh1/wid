<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gallery_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_album_id')->constrained()->cascadeOnDelete();
            $table->string('caption')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        // Migrate existing media items from the old flat "photos" collection on
        // GalleryAlbum into individual GalleryPhoto records, so no images are lost.
        $mediaItems = DB::table('media')
            ->where('model_type', 'App\\Models\\GalleryAlbum')
            ->where('collection_name', 'photos')
            ->orderBy('order_column')
            ->get();

        foreach ($mediaItems as $index => $media) {
            $photoId = DB::table('gallery_photos')->insertGetId([
                'gallery_album_id' => $media->model_id,
                'caption' => null,
                'order' => $index,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('media')->where('id', $media->id)->update([
                'model_type' => 'App\\Models\\GalleryPhoto',
                'model_id' => $photoId,
                'collection_name' => 'photo',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $mediaItems = DB::table('media')
            ->where('model_type', 'App\\Models\\GalleryPhoto')
            ->where('collection_name', 'photo')
            ->get();

        foreach ($mediaItems as $media) {
            $photo = DB::table('gallery_photos')->find($media->model_id);

            if ($photo) {
                DB::table('media')->where('id', $media->id)->update([
                    'model_type' => 'App\\Models\\GalleryAlbum',
                    'model_id' => $photo->gallery_album_id,
                    'collection_name' => 'photos',
                ]);
            }
        }

        Schema::dropIfExists('gallery_photos');
    }
};
