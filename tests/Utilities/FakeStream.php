<?php

namespace Tests\Utilities;

/**
 * This class is inspired to the VariableStream provided in PHP examples about streamWrapper
 */

class FakeStream
{
    public int $position;
    public mixed $content;
    public mixed $context;

    public function stream_open($path, $mode, $options, &$opened_path)
    {
        $this->content = '';
        $this->position = 0;

        return true;
    }

    public function stream_read($count)
    {
        $ret = substr($this->content, $this->position, $count);
        $this->position += strlen($ret);

        return $ret;
    }

    public function stream_write($data)
    {
        $left = substr($this->content, 0, $this->position);
        $right = substr($this->content, $this->position + strlen($data));

        $this->content = $left . $data . $right;
        $this->position += strlen($data);

        return strlen($data);
    }

    public function stream_tell()
    {
        return $this->position;
    }

    public function stream_eof()
    {
        return $this->position >= strlen($this->content);
    }

    public function stream_seek($offset, $whence)
    {
        switch ($whence) {
            case SEEK_SET:
                if ($offset >= strlen($this->content) && $offset < 0) {
                    return false;
                }

                $this->position = $offset;
                return true;
                break;

            case SEEK_CUR:
                if ($offset < 0) {
                    return false;
                }

                $this->position += $offset;
                return true;
                break;

            case SEEK_END:
                if (strlen($this->content) + $offset < 0) {
                    return false;
                }

                $this->position = strlen($this->content) + $offset;
                return true;
                break;

            default:
                return false;
        }
    }

    public function stream_metadata($path, $option, $var)
    {
        if ($option == STREAM_META_TOUCH) {
            if (!isset($this->content)) {
                $this->content = '';
            }

            return true;
        }

        return false;
    }

    public function stream_stat()
    {
        return [];
    }
}
