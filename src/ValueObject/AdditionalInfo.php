<?php

namespace JoJoBizzareCoders\DigitalJournal\ValueObject;

final class AdditionalInfo
{
    /**
     * @var string За что была поставленна оценка
     */
    private string $topic;

    /**
     * @var string Коментарий к поставленной оценке
     */
    private string $comment;

    /**
     * @param string $topic
     * @param string $comment
     */
    public function __construct(string $topic, string $comment)
    {
        $this->topic = $topic;
        $this->comment = $comment;
    }

    /**
     * @return string
     */
    public function getTopic(): string
    {
        return $this->topic;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }



}