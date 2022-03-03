<?php

namespace Checkout\Files;

use Checkout\CheckoutApiException;
use Checkout\Client;

class FilesClient extends Client
{
    private const FILES_PATH = "files";

    private const ALLOWED_CONTENTS = [
        "image/jpeg",
        "image/png",
        "application/pdf"
    ];

    /**
     * @param FileRequest $fileRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function uploadFile(FileRequest $fileRequest)
    {
        $this->validateContents($fileRequest->file);
        return $this->apiClient->submitFile(self::FILES_PATH, $fileRequest, $this->sdkAuthorization());
    }

    /**
     * @param string $fileId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function getFileDetails(string $fileId)
    {
        return $this->apiClient->get($this->buildPath(self::FILES_PATH, $fileId), $this->sdkAuthorization());
    }

    /**
     * @param string $file
     * @throws CheckoutApiException
     */
    private function validateContents(string $file): void
    {
        if (!in_array(mime_content_type($file), self::ALLOWED_CONTENTS)) {
            throw new CheckoutApiException("The file type is not supported.\n Supported file types: JPG/JPEG, PNG and PDF.");
        }
    }
}
