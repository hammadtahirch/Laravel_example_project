<?php

namespace App\Jobs;

use App\Services\AppServices\UploadService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateResizedImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $imagePayload;
    protected $imageSizesPayload;

    /**
     * Create a new job instance.
     *
     * @param $payload
     * @param $sizePayload
     * @return void
     */
    public function __construct($payload, $sizePayload)
    {
        $this->imagePayload = $payload;
        $this->imageSizesPayload = $sizePayload;
    }

    /**
     * Execute the job.
     *
     * @param UploadService $uploadService
     * @return void
     */
    public function handle(UploadService $uploadService)
    {
        $uploadService->resizeDifferentResolution($this->imagePayload, $this->imageSizesPayload);
    }
}
