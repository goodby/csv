<?php

namespace Goodby\CSV\Export\Protocol;

use Iterator;
use Goodby\CSV\Export\Protocol\Exception\IOException;

/**
 * Interface of the Exporter
 */
interface ExporterInterface
{
    /**
     * Export data as CSV file
     * @param string $filename
     * @param array|Iterator $rows
     * @throws IOException
     * @return void
     */
    public function export($filename, $rows);
}
