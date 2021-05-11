
    public function validateMassUpload ()
    {
        if ($this->getRequest()->isMethod(sfWebRequest::POST)) {
            $post_max_size = ini_get('post_max_size');
            if ($_SERVER['CONTENT_LENGTH'] > Util::convertPHPSizeToBytes($post_max_size)) {
                cgMessager::getInstance()->addError("The data exceeds the maximum size of $post_max_size.");
                return false;
            } elseif (!$this->csrfTokenIsValid()) {
                cgMessager::getInstance()->addError('Invalid CSRF Token');
                return false;
            } else {
                for ($i = 1; $i <= sfConfig::get('app_sfAssetsLibrary_mass_upload_size', 5); $i++) {
                    $filename = $this->getRequest()->getFileName('files[' . $i . ']');
                    $filesize = $this->getRequest()->getFileSize('files[' . $i . ']');
                    $upload_max_filesize = ini_get('upload_max_filesize');
                    if ($filesize > Util::getMaximumFileUploadSize() || !empty($filename) && $filesize === 0) {
                        cgMessager::getInstance()->addError("The uploaded file exceeds the maximum size of $upload_max_filesize.");
                        return false;
                    }
                }
            }
        }
        return true;
    }
