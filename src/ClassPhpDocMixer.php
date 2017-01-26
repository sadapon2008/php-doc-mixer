<?php

namespace PhpDocMixer;

use PhpDocMixer\PhpDocMixer;
use PhpDocMixer\ClassPhpDocMixerVisitor;
use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

class ClassPhpDocMixer
{
    public static function mix($code1, $code2, $options = [])
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        try {
            $stmts1 = $parser->parse($code1);
        } catch (Error $e) {
            return false;
        }

        try {
            $stmts2 = $parser->parse($code2);
        } catch (Error $e) {
            return false;
        }

        $traverser1 = new NodeTraverser();
        $visitor1 = new ClassPhpDocMixerVisitor();
        $traverser1->addVisitor($visitor1);
        try {
            $traverser1->traverse($stmts1);
        } catch (Error $e) {
             false;
        }
        if (!isset($visitor1->comment) || (strlen($visitor1->comment) == 0)) {
            return false;
        }

        $traverser2 = new NodeTraverser();
        $visitor2 = new ClassPhpDocMixerVisitor();
        $traverser2->addVisitor($visitor2);
        try {
            $traverser2->traverse($stmts2);
        } catch (Error $e) {
            return false;
        }
        if (!isset($visitor2->comment) || (strlen($visitor2->comment) == 0)) {
            return false;
        }

        $out = PhpDocMixer::mix($visitor1->comment, $visitor2->comment);
        $res = str_replace($visitor1->comment, $out, $code1);
        return $res;
    }
}
