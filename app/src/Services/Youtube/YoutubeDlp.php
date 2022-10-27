<?php
/**
 * @author <Akartis>
 */

namespace App\Services\Youtube;

use App\Exceptions\Youtube\NoDownloadPathException;
use App\Exceptions\Youtube\NoFileDownloadedException;
use App\Services\Application\ShellService;

class YoutubeDlp
{
    private string $format = "%(title)s.%(ext)s";
    private string $extension = "mp3";
    private int $quality = 4;
    private string $downloadPath;

    /**
     * Download mp3 from url
     *
     * @return string
     * @throws NoFileDownloadedException
     * @throws NoDownloadPathException
     */
    public function downloadMp3FromUrl(string $url): array
    {
        if ($this->downloadPath === "") {
            throw new NoDownloadPathException("Download path is required");
        }
        $fileName = $this->getVideoFileName($url);

        $this->executeShell([
            '-x',
            '--audio-format' => $this->extension,
            $url,
            '-o' => $this->getFormat(true),
            '--audio-quality' => $this->quality,
            '--no-keep-video',
            '--restrict-filenames',
            '--max-filesize 17m'
        ]);

        $filePath = sprintf("%s%s.%s", $this->downloadPath, $fileName, $this->extension);

        if (!is_file($filePath)) {
            throw new NoFileDownloadedException("File is not downloaded");
        }

        return [$filePath, $fileName];
    }

    /**
     * Download mp3 from url
     * yt-dlp -f 'bv[height=360][ext=mp4]+ba[ext=m4a]' --merge-output-format mp4 https://www.youtube.com/watch?v=i5FtET6x9Fs&list=RDeSdjGImvZhQ&index=3
     * @return string
     * @throws NoFileDownloadedException
     * @throws NoDownloadPathException
     */
    public function downloadMp4FromUrl(string $url): array
    {
        if ($this->downloadPath === "") {
            throw new NoDownloadPathException("Download path is required");
        }

        $fileName = $this->getVideoFileName($url);

        $this->executeShell([
            '-f' => sprintf("'bv[height<=%s][ext=mp4]+ba[ext=m4a]'", $this->quality),
            '--merge-output-format' => "mp4",
            $url,
            '-o' => $this->getFormat(true),
            '--restrict-filenames',
        ]);

        $filePath = sprintf("%s%s.%s", $this->downloadPath, $fileName, $this->extension);

        if (!is_file($filePath)) {
            throw new NoFileDownloadedException("File is not downloaded");
        }

        return [$filePath, $fileName];
    }

    /**
     * Get video file name without extension
     *
     * @param string $url
     * @param string $format
     * @return mixed|string
     */
    public function getVideoFileName(string $url, string $format = '"%(title)s"'): string
    {
        $result = $this->executeShell([$url, "--get-filename", '-o' => $format, '--restrict-filenames']);

        return $result[0] ?? "Title not found";
    }

    /**
     * Execute command shell in yt-dlp
     *
     * @param array $options
     */
    public function executeShell(array $options)
    {
        return ShellService::executeShell("/usr/local/bin/yt-dlp", $options);
    }

    /**
     * Set another extension for download
     * @param string $extension
     * @return YoutubeDlp
     */
    public function setExtension(string $extension): YoutubeDlp
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Set another format
     *
     * @param string $format
     * @return YoutubeDlp
     */
    public function setFormat(string $format): YoutubeDlp
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get format with extension
     * Add download path before format
     *
     * @return string
     */
    public function getFormat(bool $option = false): string
    {
        $result = "%(title)s.%(ext)s";

        switch ($this->extension) {
            case "mp3":
                $result = $this->getDownloadPath() . "%(title)s.mp3";
                break;
            case "mp4":
                $result = $this->getDownloadPath() . "%(title)s.mp4";
                break;
        }

        if ($option) {
            return sprintf("'%s'", $result);
        }

        return $result;
    }

    /**
     * Set Post-processing audio quality
     *
     * @param int $quality
     * @return YoutubeDlp
     */
    public function setQuality(int $quality): YoutubeDlp
    {
        $this->quality = $quality;

        return $this;
    }

    /**
     * @return string
     */
    public function getDownloadPath(): string
    {
        return $this->downloadPath;
    }

    /**
     * @param string $downloadPath
     * @return YoutubeDlp
     */
    public function setDownloadPath(string $downloadPath): YoutubeDlp
    {
        $this->downloadPath = $downloadPath;

        return $this;
    }
}
