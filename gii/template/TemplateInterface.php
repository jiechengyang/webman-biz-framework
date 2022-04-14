<?php

namespace gii\template;

interface TemplateInterface
{
    /**
     * @param array $args
     * @return string
     */
    public function getContext(array $args = []);
}