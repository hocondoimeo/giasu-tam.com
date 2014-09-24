<?php
class Common_FileUploader_qqFileUploader {

    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760) {
        $allowedExtensions = array_map("strtolower", $allowedExtensions);

        $this->allowedExtensions = $allowedExtensions;
        $this->sizeLimit = $sizeLimit;

        $this->checkServerSettings();

        if (isset($_GET['qqfile'])) {
            $this->file = new Common_FileUploader_qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new Common_FileUploader_qqUploadedFileForm();
        } else {
            $this->file = false;
        }
    }

    private function checkServerSettings() {
        ini_set('post_max_size', '8M');
        ini_set('upload_max_filesize', '2M');
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));
        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit) {
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            return "{'error':'increase post_max_size and upload_max_filesize to $size'}";
        }
    }

    private function toBytes($str) {
        $val = trim($str);
        $last = strtolower($str[strlen($str) - 1]);
        switch ($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE) {
        $this->_createFolders($uploadDirectory);

        if (!is_writable($uploadDirectory)) {
            return array('error' => "Server error. Upload directory isn't writable.");
        }

        if (!$this->file) {
            return array('error' => 'Không file được upload.');
        }

        $size = $this->file->getSize();

        if ($size == 0) {
            return array('error' => 'File trống rỗng');
        }

        if ($size > $this->sizeLimit) {
            return array('error' => 'Kích thước file quá lớn');
        }

        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];
        //$filename = md5(uniqid());
        $ext = isset($pathinfo['extension']) ? $pathinfo['extension'] : NULL;

        if ($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)) {
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File không phù hợp, đuôi mở rộng được chấp nhận: ' . $these . '.');
        }

        if (!$replaceOldFile) {
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 99);
                return array('error' => 'File '+$filename+' đã tồn tại. Xin vui lòng đổi tên file !');
            }
        }

        if ($this->file->save($uploadDirectory. $filename . '.' . $ext)) {
            return array('success' => $filename . '.' . $ext);
        } else {
            return array('error' => 'Không thể upload file.' .
                'Hệ thống đang bận. Xin vui lòng thử lại');
        }
    }

    /**
     * Create folders
     * @param string $folderPath
     */
    private function _createFolders($folderPath) {
        if (! is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
    }
}

