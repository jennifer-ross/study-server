<?php

namespace App\Http\Controllers\Api;

use App\Classes\Learners\Learners;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LearnersController extends Controller
{
    private $request;
    private $params;

    public function __construct()
    {
        $this->request = \Illuminate\Support\Facades\Request::instance();
        $this->params = $this->request->query->all();
    }
    //

    public function LearnersGetHandler() {
        if (isset($this->params['limit']) && isset($this->params['page'])) {
            return Learners::getLearners($this->params['limit'], $this->params['page'], $this->params['user_token']);
        }else {
            return Learners::getLearners(null, null, $this->params['user_token']);
        }
    }

    public function LearnerCreateHandler() {
        if (isset($this->params['firstName']) && isset($this->params['secondName']) && isset($this->params['dateBorn']) && isset($this->params['fullName'])) {
            return Learners::createLearner($this->params['firstName'],$this->params['secondName'], $this->params['dateBorn'], $this->params['user_token'], $this->params['fullName'], $this->request->getContent());
        }else {
            return Learners::createLearner(null, null,null, $this->params['user_token'], null);
        }
    }

    public function LearnersMaxCountHandler() {
        return Learners::getMaxCount($this->params['user_token']);
    }

    public function LearnerSearchHandler() {
        if (isset($this->params['searchBy']) && isset($this->params['s'])){
            return Learners::Search($this->params['searchBy'], $this->params['s'], $this->params['user_token']);
        }else {
            return Learners::Search(null,null, $this->params['user_token']);
        }
    }

    public function LearnerRemoveHandler() {
        if (isset($this->params['fields'])) {
            return Learners::Remove($this->params['fields'],$this->params['user_token']);
        }else {
            return Learners::Remove(null, $this->params['user_token']);
        }
    }

    public function LearnerUpdateFieldsHandler() {
        if (isset($this->params['fields']) && isset($this->params['fields'])) {
            return Learners::updateFields('learners', $this->params['fields'], $this->params['user_token'], ['image' => $this->request->getContent(), 'id' => $this->params['id']]);
        }else {
            return Learners::updateFields(null,null, null);
        }
    }

}
