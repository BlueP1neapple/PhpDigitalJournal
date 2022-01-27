<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\ViewTemplate;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Exception;

class PhtmlTemplate implements ViewTemplateInterface
{

    /**
     *
     *
     * @param string $template
     * @param array $context
     * @return string
     */
    public function render(string $template, array $context): string
    {
        if (false === file_exists($template)) {
            throw new Exception\RuntimeException("Не корректрый путь до шаблона '$template'");
        }

        extract($context, EXTR_SKIP);
        unset($viewData);

        ob_start();

        require $template;

        return ob_get_clean();
    }


}