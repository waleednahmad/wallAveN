<?php

namespace App\Console\Commands;

use App\Support\ImageOptimizer;
use Illuminate\Console\Command;
use Throwable;

/**
 * One-off (and safe to re-run) image clean-up for public/storage:
 *   php artisan images:optimize --dry-run        report only, change nothing
 *   php artisan images:optimize                   create missing 600px thumbnails, delete orphaned ones
 *   php artisan images:optimize --shrink          also re-encode big .webp originals in place
 *                                                 (longest side 1600px, quality 78; kept only if smaller)
 * Originals keep their file names, so database paths don't change. Take a backup before --shrink.
 */
class OptimizeImages extends Command
{
    protected $signature = 'images:optimize
        {--dry-run : Only report what would change}
        {--shrink : Also shrink big .webp originals in place}
        {--min-kb=250 : With --shrink, only touch originals bigger than this (KB) or longer than 1600px}
        {--path= : Only this sub folder of public/storage, e.g. products}';

    protected $description = 'Create thumbnails for existing images and optionally shrink oversized originals';

    /** Folders that hold documents, not storefront images. */
    private const SKIP_DIRS = ['resale_certificates', 'catalogs', 'livewire-tmp'];

    public function handle(): int
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $root = realpath(public_path('storage'));
        if (!$root) {
            $this->error('public/storage not found');
            return self::FAILURE;
        }
        $base = $this->option('path') ? realpath($root . '/' . trim($this->option('path'), '/')) : $root;
        if (!$base || !str_starts_with($base, $root)) {
            $this->error('Folder not found inside public/storage');
            return self::FAILURE;
        }

        $dry = (bool) $this->option('dry-run');
        $shrink = (bool) $this->option('shrink');
        $minBytes = max(0, (int) $this->option('min-kb')) * 1024;

        $sources = [];
        $thumbs = [];
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($base, \FilesystemIterator::SKIP_DOTS));
        foreach ($it as $file) {
            if (!$file->isFile()) {
                continue;
            }
            $rel = substr($file->getPathname(), strlen($root) + 1);
            if (in_array(strtok($rel, '/'), self::SKIP_DIRS, true)) {
                continue;
            }
            $path = $file->getPathname();
            if (ImageOptimizer::isThumb($path)) {
                $thumbs[] = $path;
            } elseif (preg_match('/\.(webp|jpe?g|png|gif|bmp)$/i', $path)) {
                $sources[] = $path;
            }
        }

        $this->info(sprintf('%d images, %d existing thumbnails in %s', count($sources), count($thumbs), $base));

        $made = $shrunk = $failed = $pruned = 0;
        $savedBytes = 0;
        $bar = $this->output->createProgressBar(count($sources));
        $bar->start();

        foreach ($sources as $path) {
            $bar->advance();
            try {
                $image = null;

                if ($shrink && str_ends_with(strtolower($path), '.webp')) {
                    $size = filesize($path);
                    $dims = @getimagesize($path);
                    $tooLong = $dims && max($dims[0], $dims[1]) > ImageOptimizer::MAIN_MAX;
                    if ($tooLong || $size > $minBytes) {
                        $image = ImageOptimizer::load($path);
                        $fitted = ImageOptimizer::fit($image, ImageOptimizer::MAIN_MAX);
                        $tmp = $path . '.tmp-' . getmypid() . '.webp';
                        ImageOptimizer::saveWebp($fitted, $tmp, ImageOptimizer::MAIN_QUALITY);
                        $newSize = filesize($tmp);
                        if ($newSize < $size) {
                            if (!$dry) {
                                rename($tmp, $path);
                                @chmod($path, 0644);
                            }
                            $savedBytes += $size - $newSize;
                            $shrunk++;
                            if ($fitted !== $image) {
                                imagedestroy($image);
                                $image = $fitted;
                            }
                            // The thumbnail is rebuilt from the new original below.
                            if (!$dry) {
                                @unlink(ImageOptimizer::thumbPathFor($path));
                            }
                        } elseif ($fitted !== $image) {
                            imagedestroy($fitted);
                        }
                        @unlink($tmp);
                    }
                }

                $thumbPath = ImageOptimizer::thumbPathFor($path);
                if (!is_file($thumbPath)) {
                    if (!$dry) {
                        ImageOptimizer::makeThumb($path, $image);
                        @chmod($thumbPath, 0644);
                    }
                    $made++;
                }

                if ($image) {
                    imagedestroy($image);
                }
            } catch (Throwable $e) {
                $failed++;
                $this->newLine();
                $this->warn('Skipped ' . substr($path, strlen($root) + 1) . ': ' . $e->getMessage());
            }
        }
        $bar->finish();
        $this->newLine(2);

        // Thumbnails whose original was deleted from the admin.
        foreach ($thumbs as $thumbPath) {
            $stem = substr($thumbPath, 0, -strlen(ImageOptimizer::THUMB_SUFFIX));
            if (!glob($stem . '.*')) {
                if (!$dry) {
                    @unlink($thumbPath);
                }
                $pruned++;
            }
        }

        $prefix = $dry ? '[dry run] would have: ' : '';
        $this->info($prefix . "created {$made} thumbnails, deleted {$pruned} orphaned thumbnails, failed {$failed}.");
        if ($shrink) {
            $this->info($prefix . sprintf('shrunk %d originals, saving %.1f MB.', $shrunk, $savedBytes / 1048576));
        }

        return self::SUCCESS;
    }
}
