<?php
/**
 * @author <akartis-dev>
 */

namespace App\Services\FileHandler;


use App\Exceptions\Youtube\NoFileDownloadedException;
use App\Services\Application\ShellService;

class FileHandlerService
{
    private string $filename;
    private string $basePath;
    private string $newFilePath;

    /**
     * Check if we must split file for upload
     * Max file for not split is 22Mo
     *
     * @param string $filename
     * @param string $basePath
     * @return array
     * @throws NoFileDownloadedException
     */
    public function checkFileAndSplit(string $filename, string $basePath): array
    {
        $this->filename = $filename;
        $this->basePath = $basePath;

        $uniqId = uniqid('', false);
        $workPath = sprintf("%s%s", $basePath, $uniqId);
        $oldFilePath = sprintf("%s%s", $basePath, $filename);

        if (!mkdir($workPath) && !is_dir($workPath)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $workPath));
        }

        $newFilePath = sprintf("%s%s%s", $workPath, DIRECTORY_SEPARATOR, $filename);
        rename($oldFilePath, $newFilePath);

        if (!is_file($newFilePath)) {
            throw new NoFileDownloadedException(sprintf('File is not moved %s', $workPath));
        }

        $this->newFilePath = $newFilePath;
        $fileSizeKo = filesize($newFilePath) / 1000;

        if ($fileSizeKo <= 24000) {

            return ['path' => $workPath, 'data' => [$newFilePath], 'size' => $fileSizeKo];
        }

        $splitFile = $this->splitFile(20);

        return ['path' => $workPath, 'data' => $splitFile, 'size' => $fileSizeKo];
    }

    /**
     * Split file and return splited file path
     * User split cmd to split file
     */
    private function splitFile(int $size = 5): array
    {
        $zipFilePath = $this->zipFile();
        $outFilename = sprintf("%s.split.zip", $this->newFilePath);

        $options = [
            "-s" => sprintf("%sm", $size),
            $zipFilePath,
            '--out' => $outFilename,
        ];

        ShellService::executeShell("zip", $options);
        unlink($zipFilePath);

        rename($outFilename, sprintf("%s.fafana", $outFilename));

        $workPath = dirname($this->newFilePath);

        $listDir = scandir($workPath);
        $ignoredItem = [".", ".."];
        $splitedPath = [];

        foreach ($listDir as $item) {
            if (!in_array($item, $ignoredItem, true)) {
                $splitedPath[] = sprintf("%s%s%s", $workPath, DIRECTORY_SEPARATOR, $item);
            }
        }

        return $splitedPath;
    }

    /**
     * Zip video file
     * @return string
     * @throws NoFileDownloadedException
     */
    private function zipFile(): string
    {
        $zipFile = sprintf("%s.zip", $this->newFilePath);

        $options = [
            '-j',
            $zipFile, //Filename with zip, absolute path,
            $this->newFilePath
        ];

        ShellService::executeShell("zip", $options);

        if (!is_file($zipFile)) {
            throw new NoFileDownloadedException(sprintf('File is not zip and doesn\'t exist %s', $zipFile));
        }

        unlink($this->newFilePath); //Remove original file

        return $zipFile;
    }
}
