<?php
class PortalModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllMahasiswa() {
        $stmt = $this->conn->prepare("SELECT * FROM mahasiswa ORDER BY poin_softskill DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllTugas() {
        $stmt = $this->conn->prepare("SELECT * FROM tugas ORDER BY deadline ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function tambahTugas($mk, $judul, $deadline, $kategori) {
        $sql = "INSERT INTO tugas (mata_kuliah, judul_tugas, deadline, kategori) VALUES (:mk, :judul, :deadline, :kategori)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':mk' => htmlspecialchars(strip_tags($mk)),
            ':judul' => htmlspecialchars(strip_tags($judul)),
            ':deadline' => $deadline,
            ':kategori' => $kategori
        ]);
    }
}
?>