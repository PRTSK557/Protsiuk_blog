<?php

namespace App\Jobs\GenerateCatalog;

class GeneratePricesFileChunkJob extends AbstractJob
{
    protected $chunk;
    protected $fileNum;

    public function __construct($chunk, $fileNum)
    {
        parent::__construct();

        $this->chunk = $chunk;
        $this->fileNum = $fileNum;
    }

    public function handle()
    {
        // Тут може бути логіка обробки chunk

        $this->debug("Processed chunk {$this->fileNum}");
    }
}
