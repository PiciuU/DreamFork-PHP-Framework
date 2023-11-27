<?php

namespace Framework\View\Compilers\Traits;

/**
 * Trait CompileIfs
 *
 * This trait provides methods for compiling if statements, elseif statements, else statements in templates.
 *
 * @package Framework\View\Compilers\Traits
 */
trait CompileIfs
{
    /**
     * Compile if statements, elseif statements, else statements in the given content.
     *
     * @param string $content The content to be compiled.
     * @return string The compiled content.
     */
    protected function compileIfs($content)
    {
        $patterns = [
            '/@if\s?\(\s*(.+?)\s*\)/' => '<?php if(%s): ?>',
            '/@elseif\s?\(\s*(.+?)\s*\)/' => '<?php elseif(%s): ?>',
            '/@else/' => '<?php else: ?>',
            '/@endif/' => '<?php endif; ?>',
        ];

        foreach ($patterns as $pattern => $replacement) {
            $callback = function ($matches) use ($replacement) {
                $whitespace = empty($matches[3]) ? '' : $matches[3] . $matches[3];
                $wrapped = $matches[1] ?? '';
                return sprintf($replacement, $wrapped) . $whitespace;
            };

            $content = preg_replace_callback($pattern, $callback, $content);
        }

        return $content;
    }
}