<?php

use lsolesen\pel\PelJpeg;

class KutybaIt_GdPreserveExif_Model_Adapter_Gd2 extends Varien_Image_Adapter_Gd2
{
    public function save($destination = null, $newName = null)
    {
        if ($this->_fileType == IMAGETYPE_JPEG) {
            imageinterlace($this->_imageHandler, 1);
        }

        parent::save($destination, $newName);

        if (isset($destination) && isset($newName)) {
            $fileName = $destination . "/" . $newName;
        } elseif (isset($destination) && !isset($newName)) {
            $info = pathinfo($destination);
            $fileName = $destination;
            $destination = $info['dirname'];
        } elseif (!isset($destination) && isset($newName)) {
            $fileName = $this->_fileSrcPath . "/" . $newName;
        } else {
            $fileName = $this->_fileSrcPath . $this->_fileSrcName;
        }

        if ($this->_fileType == IMAGETYPE_JPEG) {
            try {
                $jpeg = new PelJpeg($this->_fileName);

                if ($exif = $jpeg->getExif()) {
                    $jpeg = new PelJpeg($fileName);
                    $jpeg->setExif($exif);
                    $jpeg->saveFile($fileName);
                }
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
    }
}
