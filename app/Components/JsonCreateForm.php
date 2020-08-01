<?php


namespace App\Components;


use Nette\Application\UI\Control;

class JsonCreateForm extends Control
{
    public function render() {
        $this->template->setFile(__DIR__ . '/templates/JsonCreateForm.latte');
        $this->template->render();
    }
}