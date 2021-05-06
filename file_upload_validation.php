    public function validateMassUpload ()
    {
        if ($this->getRequest()->isMethod(sfWebRequest::POST)) {
            if ($_SERVER['CONTENT_LENGTH'] > Util::convertPHPSizeToBytes(ini_get('post_max_size'))) {
                cgMessager::getInstance()->addError('The data exceeds the maximum size.');
                return false;
            } elseif (!$this->csrfTokenIsValid()) {
                cgMessager::getInstance()->addError('Invalid CSRF Token');
                return false;
            } else {
                for ($i = 1; $i <= sfConfig::get('app_sfAssetsLibrary_mass_upload_size', 5); $i++) {
                    $filename = $this->getRequest()->getFileName('files[' . $i . ']');
                    $filesize = $this->getRequest()->getFileSize('files[' . $i . ']');
                    if ($filesize > Util::getMaximumFileUploadSize() || !empty($filename) && $filesize === 0) {
                        cgMessager::getInstance()->addError('The uploaded file exceeds the maximum size.');
                        return false;
                    }
                }
            }
        }
        return true;
    }
