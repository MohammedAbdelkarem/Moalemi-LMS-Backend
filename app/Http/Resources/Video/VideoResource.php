<?php

namespace App\Http\Resources\Video;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get the resolution from request, default to 720p
        $resolution = $request->get('resolution', '720p');
        
        // Get the media object (assuming this resource is used with Media model)
        $media = $this->resource;
        
        // Base video data
        $data = [
            'id' => $media->id,
            'url' => $this->getVideoUrl($media, $resolution),
            'type' => getMediaType($media->mime_type),
            'resolution' => $resolution,
            'available_resolutions' => $this->getAvailableResolutions(),
            'thumbnail' => $this->getThumbnailUrl($media),
            'file_size' => $media->size,
        ];

        return $data;
    }

    /**
     * Get video URL for the specified resolution
     *
     * @param Media $media
     * @param string $resolution
     * @return string|null
     */
    private function getVideoUrl(Media $media, string $resolution): ?string
    {
        switch($resolution) {
            case '720p':
                return $media->getUrl('720p');
            case '480p':
                return $media->getUrl('480p');
            case '360p':
                return $media->getUrl('360p');
            case 'thumbnail':
                return $media->getUrl('thumbnail');
            case 'original':
                return $media->getUrl();
            default:
                return $media->getUrl('720p');
        }
    }

    /**
     * Get thumbnail URL
     *
     * @param Media $media
     * @return string|null
     */
    private function getThumbnailUrl(Media $media): ?string
    {
        return $media->getUrl('thumbnail');
    }

    /**
     * Get list of available resolutions
     *
     * @return array
     */
    private function getAvailableResolutions(): array
    {
        return [
            'original' => 'Original Quality',
            '720p' => 'HD (1280x720)',
            '480p' => 'SD (854x480)',
            '360p' => 'Mobile (640x360)',
            'thumbnail' => 'Thumbnail (320x180)'
        ];
    }
}
