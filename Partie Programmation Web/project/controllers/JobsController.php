<?php
require_once "models/Job.php";

class JobsController {
    private $jobModel;

    public function __construct() {
        $this->jobModel = new Job();
    }

    public function index() {
        $q = $_GET['q'] ?? '';
        if(!empty($q)){
            $jobs = $this->jobModel->search($q);
        } else {
            $jobs = $this->jobModel->getAll();
        }
        include "views/jobs/index.php";
    }

    public function show() {
        $id = $_GET['id'] ?? null;
        if(!$id){
            echo "ID introuvable";
            return;
        }
        $job = $this->jobModel->getById($id);
        include "views/jobs/show.php";
    }
}
