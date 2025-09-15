<?php
class Job {
    private $db;

    public function __construct() {
        $this->db = new mysqli("localhost", "root", "", "JobMatchingDB");
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    public function getAll() {
        $result = $this->db->query("SELECT * FROM jobs ORDER BY id DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM jobs WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function search($keyword) {
        $keyword = "%$keyword%";
        $stmt = $this->db->prepare("SELECT * FROM jobs WHERE titre LIKE ? OR entreprise LIKE ? ORDER BY id DESC");
        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
