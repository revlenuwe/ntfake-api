<?php


namespace App\Modules\Base\Presenters;


use App\Components\JsonCreateForm;
use Nette\Application\UI\Presenter;

class AppPresenter extends Presenter
{
    public function createComponentJsonCreateForm() {
        return new JsonCreateForm();
    }
}