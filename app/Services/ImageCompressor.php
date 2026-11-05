<?php

namespace App\Services;

use Tinify\Exception as TinifyException;

class ImageCompressor
{
    public function __construct()
    {
        \Tinify\setKey(config('services.tinify.key'));
    }

    /**
     * Compress image and fallback to original if fails
     *
     * @param string $sourcePath - caminho temporário da imagem
     * @param string $destinationPath - onde salvar a imagem final
     * @param int|null $maxDimension - se informado, reduz a imagem (mantendo a proporção) para que
     *                                 largura e altura fiquem no máximo com esse valor
     * @return bool
     */
    public function compressOrFallback(string $sourcePath, string $destinationPath, ?int $maxDimension = null): bool
    {
        $resizedPath = $maxDimension ? $this->resizeToFit($sourcePath, $maxDimension) : null;
        $inputPath = $resizedPath ?? $sourcePath;

        try {
            $source = \Tinify\fromFile($inputPath);
            $source->toFile($destinationPath);

            return true;

        } catch (TinifyException $e) {

            copy($inputPath, $destinationPath);

            \Log::warning("Tinify falhou: " . $e->getMessage());

            return false;

        } finally {
            if ($resizedPath && file_exists($resizedPath)) {
                @unlink($resizedPath);
            }
        }
    }

    /**
     * Reduz a imagem para que o maior lado tenha no máximo $maxDimension px, mantendo a proporção.
     * Retorna o caminho de um arquivo temporário com a imagem reduzida, ou null se não precisar
     * reduzir (ou se não for possível processar).
     */
    private function resizeToFit(string $sourcePath, int $maxDimension): ?string
    {
        $info = @getimagesize($sourcePath);
        if (!$info) {
            return null;
        }

        [$width, $height, $type] = $info;

        $orientation = 1;
        if ($type === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
            $exif = @exif_read_data($sourcePath);
            $orientation = (int) ($exif['Orientation'] ?? 1);
        }

        // Orientações 5-8 giram 90°, então largura e altura visíveis ficam trocadas
        $rotated = in_array($orientation, [5, 6, 7, 8], true);
        $visibleWidth = $rotated ? $height : $width;
        $visibleHeight = $rotated ? $width : $height;

        if (max($visibleWidth, $visibleHeight) <= $maxDimension && $orientation === 1) {
            return null;
        }

        $previousMemoryLimit = ini_get('memory_limit');
        @ini_set('memory_limit', '1024M');

        try {
            $image = match ($type) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
                IMAGETYPE_PNG => @imagecreatefrompng($sourcePath),
                IMAGETYPE_WEBP => @imagecreatefromwebp($sourcePath),
                default => false,
            };

            if (!$image) {
                return null;
            }

            $image = $this->applyExifOrientation($image, $orientation);

            $width = imagesx($image);
            $height = imagesy($image);
            $scale = min(1, $maxDimension / max($width, $height));
            $newWidth = (int) round($width * $scale);
            $newHeight = (int) round($height * $scale);

            if ($scale < 1) {
                $resized = imagecreatetruecolor($newWidth, $newHeight);

                if ($type !== IMAGETYPE_JPEG) {
                    imagealphablending($resized, false);
                    imagesavealpha($resized, true);
                }

                imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($image);
                $image = $resized;
            }

            $tempPath = tempnam(sys_get_temp_dir(), 'img');

            $saved = match ($type) {
                IMAGETYPE_JPEG => imagejpeg($image, $tempPath, 92),
                IMAGETYPE_PNG => imagepng($image, $tempPath),
                IMAGETYPE_WEBP => imagewebp($image, $tempPath, 92),
            };

            imagedestroy($image);

            if (!$saved) {
                @unlink($tempPath);
                return null;
            }

            return $tempPath;

        } catch (\Throwable $e) {
            \Log::warning("Falha ao redimensionar imagem: " . $e->getMessage());
            return null;

        } finally {
            @ini_set('memory_limit', $previousMemoryLimit);
        }
    }

    private function applyExifOrientation(\GdImage $image, int $orientation): \GdImage
    {
        if (in_array($orientation, [2, 4, 5, 7], true)) {
            imageflip($image, IMG_FLIP_HORIZONTAL);
        }

        $angle = match ($orientation) {
            3, 4 => 180,
            6, 7 => 270,
            5, 8 => 90,
            default => 0,
        };

        if ($angle !== 0) {
            $rotated = imagerotate($image, $angle, 0);
            imagedestroy($image);
            $image = $rotated;
        }

        return $image;
    }

    /**
     * Get Tinify compression count (opcional)
     */
    public function getCompressionCount(): ?int
    {
        try {
            return \Tinify\compressionCount();
        } catch (\Exception $e) {
            return null;
        }
    }
}