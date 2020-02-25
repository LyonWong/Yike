<?php

namespace qcloudcos;

/**
 * A simple wrapper for SHA-1 implementation.
 */
class Sha1Digest {
    private $state_0; // state (ABCD)
    private $state_1;
    private $state_2;
    private $state_3;
    private $state_4;

    private $bit_count; // Bit count of input.

    private $buffer; // input buffer

    /**
     * Initialize an incremental SHA-1 context.
     */
    public function initSha1() {
        $this->bit_count = 0;

        // Load magic initialization constants.
        $this->state_0 = 0x67452301;
        $this->state_1 = 0xefcdab89;
        $this->state_2 = 0x98badcfe;
        $this->state_3 = 0x10325476;
        $this->state_4 = 0xc3d2e1f0;
    }

    /**
     * Pump data into an active SHA-1 context.
     */
    public function updateSha1($input) {
        $partLength = 64 - (($this->bit_count / 8) % 64);
        $inputLength = strlen($input);
        $this->bit_count += $inputLength * 8;

        $i = 0;
        if ($inputLength >= $partLength) {
            $this->buffer .= substr($input, 0, $partLength);
            $this->transformSha1($this->buffer);
            $this->buffer = '';

            for ($i = $partLength; $i + 63 < $inputLength; $i += 64) {
                $this->transformSHA1(substr($input, $i, 64));
            }
        }

        $this->buffer .= substr($input, $i);

        return sprintf('%02x', $this->state_0 & 0x000000FF) .
               sprintf('%02x', ($this->state_0 & 0x0000FF00) >> 8) .
               sprintf('%02x', ($this->state_0 & 0x00FF0000) >> 16) .
               sprintf('%02x', ($this->state_0 & 0xFF000000) >> 24) .
               sprintf('%02x', $this->state_1 & 0x000000FF) .
               sprintf('%02x', ($this->state_1 & 0x0000FF00) >> 8) .
               sprintf('%02x', ($this->state_1 & 0x00FF0000) >> 16) .
               sprintf('%02x', ($this->state_1 & 0xFF000000) >> 24) .
               sprintf('%02x', $this->state_2 & 0x000000FF) .
               sprintf('%02x', ($this->state_2 & 0x0000FF00) >> 8) .
               sprintf('%02x', ($this->state_2 & 0x00FF0000) >> 16) .
               sprintf('%02x', ($this->state_2 & 0xFF000000) >> 24) .
               sprintf('%02x', $this->state_3 & 0x000000FF) .
               sprintf('%02x', ($this->state_3 & 0x0000FF00) >> 8) .
               sprintf('%02x', ($this->state_3 & 0x00FF0000) >> 16) .
               sprintf('%02x', ($this->state_3 & 0xFF000000) >> 24) .
               sprintf('%02x', $this->state_4 & 0x000000FF) .
               sprintf('%02x', ($this->state_4 & 0x0000FF00) >> 8) .
               sprintf('%02x', ($this->state_4 & 0x00FF0000) >> 16) .
               sprintf('%02x', ($this->state_4 & 0xFF000000) >> 24);
    }

    /**
     * Finalize an incremental SHA-1 and return resulting digest.
     */
    public function finalSha1() {
        $message = $this->buffer . chr(128);
        while (((strlen($message) + 8) % 64) !== 0) {
            $message .= chr(0);
        }
        foreach (str_split(sprintf('%064b', $this->bit_count), 8) as $bin) {
            $message .= chr(bindec($bin));
        }

        $this->bit_count = ($this->bit_count - strlen($this->buffer) * 8);
        $this->buffer = '';

        $this->updateSha1($message);

        return sprintf('%08x%08x%08x%08x%08x',
                $this->state_0, $this->state_1, $this->state_2, $this->state_3, $this->state_4);
    }

    private function transformSHA1($chunk) {
        $K = array(0x5a827999, 0x6ed9eba1, 0x8f1bbcdc, 0xca62c1d6);

        // break chunk into sixteen 32-bit big-endian words w[i], 0 ≤ i ≤ 15
        $words = str_split($chunk, 4);
        foreach ($words as $i => $chrs) {
            $chrs = str_split($chrs);
            $word = '';
            foreach ($chrs as $chr) {
                $word .= sprintf('%08b', ord($chr));
            }
            $words[$i] = bindec($word);
        }

        // Extend the sixteen 32-bit words into eighty 32-bit words:
        for ($i = 16; $i < 80; $i++) {
            $words[$i] = $this->rotl(
                    $words[$i-3] ^ $words[$i-8] ^ $words[$i-14] ^ $words[$i-16], 1) & 0xffffffff;
        }

        // Initialize hash value for this chunk:
        $a = $this->state_0;
        $b = $this->state_1;
        $c = $this->state_2;
        $d = $this->state_3;
        $e = $this->state_4;

        // Main loop:[39]
        foreach ($words as $i => $word) {
            $s = floor($i / 20);
            $f = $this->SHAfunction($s, $b, $c, $d);
            $temp = $this->rotl($a, 5) + $f + $e + $K[$s] + ($word) & 0xffffffff;
            $e = $d;
            $d = $c;
            $c = $this->rotl($b, 30);
            $b = $a;
            $a = $temp;
        }

        // Add this chunk's hash to result so far:
        $this->state_0 = ($this->state_0 + $a) & 0xffffffff;
        $this->state_1 = ($this->state_1 + $b) & 0xffffffff;
        $this->state_2 = ($this->state_2 + $c) & 0xffffffff;
        $this->state_3 = ($this->state_3 + $d) & 0xffffffff;
        $this->state_4 = ($this->state_4 + $e) & 0xffffffff;
    }

    private function rotl($x, $n) {
        return ($x << $n) | ($x >> (32 - $n));
    }

    private function SHAfunction($step, $b, $c, $d) {
        switch ($step) {
            case 0;
                return ($b & $c) ^ (~$b & $d);
            case 1;
                case 3;
            return $b ^ $c ^ $d;
                case 2;
            return ($b & $c) ^ ($b & $d) ^ ($c & $d);
        }
    }
}

/*
$s = new Sha1Digest();
$s->initSha1();
echo $s->updateSha1(''), PHP_EOL;
echo $s->finalSha1(), PHP_EOL;
echo sha1(''), PHP_EOL, PHP_EOL;

$s = new Sha1Digest();
$s->initSha1();
echo $s->updateSha1(file_get_contents(__FILE__)), PHP_EOL;
echo $s->finalSha1(), PHP_EOL;
echo sha1_file(__FILE__), PHP_EOL, PHP_EOL;
*/
