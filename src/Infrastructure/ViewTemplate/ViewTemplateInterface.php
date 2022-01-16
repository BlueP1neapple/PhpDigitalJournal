<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\ViewTemplate;

interface ViewTemplateInterface
{
    /**
     * Рендерит данные
     *
     * @param string $template - путь до шаблона
     * @param array $context - данные для рендеринга
     *
     * @return string - результаты рендеринга
     */
    public function render(string $template, array $context):string;

}