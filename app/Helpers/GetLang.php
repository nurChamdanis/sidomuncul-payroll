<?php

namespace App\Helpers;

use CodeIgniter\Language\Language;

class GetLang extends Language {
    public function getLanguage(){
        return $this->language;
    }
    public function getLoadedFiles(){
        return $this->loadedFiles;
    }
}