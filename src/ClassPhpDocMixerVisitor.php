<?php

namespace PhpDocMixer;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node\Stmt\Class_;

class ClassPhpDocMixerVisitor extends NodeVisitorAbstract
{
    public $comment = null;
        
    public function leaveNode(Node $node) {
        if ($node instanceof Class_) {
            $c = $node->getDocComment();
            if (!empty($c)) {
                $this->comment = $node->getDocComment()->getText();
            }
        }
    }
}
