<?php

namespace PhpDocMixer;

class PhpDocMixer
{
    public static function mix($doc1, $doc2, $options = [])
    {
        $pos = strrpos($doc1, '*/');
        if ($pos === false) {
            return false;
        }
        $comments1 = explode("\n", $doc1);
        $currentTags = [];
        $newTags = [];
        if (!empty($comments1)) {
            foreach ($comments1 as $comment) {
                if (!preg_match('/^\s*\*\s+@/u', $comment)) {
                    continue;
                }
                $key = preg_replace('/\s+/u', ' ', $comment);
                $currentTags[$key] = true;
            }
        }
        $comments2 = explode("\n", $doc2);
        if (!empty($comments2)) {
            foreach ($comments2 as $comment) {
                if (!preg_match('/^\s*\*\s+@/u', $comment)) {
                    continue;
                }
                $key = preg_replace('/\s+/u', ' ', $comment);
                if (array_key_exists($key, $currentTags)) {
                    continue;
                }
                $newTags[] = $comment;
            }
        }
        $outputs = array();
        $reverseComments1 = array_reverse($comments1);
        $reverseNewTags = array_reverse($newTags);
        $endFlag = false;
        foreach ($reverseComments1 as $comment) {
            $outputs[] = $comment;
            if (!$endFlag && preg_match('/^\s*\*/u', $comment)) {
                $endFlag = true;
                foreach ($reverseNewTags as $tag) {
                    $outputs[] = $tag;
                }
            }
        }
        $out = implode("\n", array_reverse($outputs));
        return $out;
    }
}
