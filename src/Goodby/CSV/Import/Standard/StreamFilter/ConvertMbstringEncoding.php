<?php

namespace Goodby\CSV\Import\Standard\StreamFilter;

use php_user_filter;

class ConvertMbstringEncoding extends php_user_filter
{
    /**
     * @var string
     */
    const FILTER_NAMESPACE = 'convert.mbstring.encoding.';

    /**
     * @var string
     */
    private $fromCharset;

    /**
     * @var string
     */
    private $toCharset;

    /**
     * @return string
     */
    public static function getFilterName()
    {
        return self::FILTER_NAMESPACE.'*';
    }

    /**
     * @return bool
     */
    public function onCreate()
    {
        if ( strpos($this->filtername, self::FILTER_NAMESPACE) !== 0 ) {
            return false;
        }

        $parameterString = substr($this->filtername, strlen(self::FILTER_NAMESPACE));

        if ( ! preg_match('/^(?P<from>[-\w]+)(:(?P<to>[-\w]+))?$/', $parameterString, $matches) ) {
            return false;
        }

        $this->fromCharset = isset($matches['from']) ? $matches['from'] : 'auto';
        $this->toCharset   = isset($matches['to'])   ? $matches['to']   : mb_internal_encoding();

        return true;
    }

    /**
     * @param string $in
     * @param string $out
     * @param string $consumed
     * @param $closing
     * @return int
     */
    public function filter($in, $out, &$consumed, $closing)
    {
        while ( $bucket = stream_bucket_make_writeable($in) ) {
            $bucket->data = mb_convert_encoding($bucket->data, $this->toCharset, $this->fromCharset);
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }

        return PSFS_PASS_ON;
    }
}
