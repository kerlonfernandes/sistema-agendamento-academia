<?php

class NotFoundController extends Base
{
    public function index()
    {
        $this->helpers->redirect(SITE);
    }
}
