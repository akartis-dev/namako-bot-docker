<?php
/**
 * @author <akartis-dev>
 */

namespace App\Messenger\UploadFile;


use App\Entity\Models\FileDataDto;

class UploadFile
{
    public function __construct(private FileDataDto $fileDataDto)
    {
    }

    /**
     * @return FileDataDto
     */
    public function getFileDataDto(): FileDataDto
    {
        return $this->fileDataDto;
    }
}
