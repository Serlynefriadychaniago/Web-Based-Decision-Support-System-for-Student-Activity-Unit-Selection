
CREATE DATABASE IF NOT EXISTS db_spk_ukm;
USE db_spk_ukm;

CREATE TABLE users (
 id BIGINT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 username VARCHAR(50) NOT NULL UNIQUE,
 email VARCHAR(100) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 role ENUM('admin','mahasiswa') NOT NULL,
 remember_token VARCHAR(100) NULL,
 created_at TIMESTAMP NULL,
 updated_at TIMESTAMP NULL
);

CREATE TABLE mahasiswa (
 id BIGINT AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT NOT NULL UNIQUE,
 nim VARCHAR(20) NOT NULL UNIQUE,
 nama VARCHAR(100) NOT NULL,
 prodi VARCHAR(100),
 fakultas VARCHAR(100),
 angkatan YEAR,
 jenis_kelamin ENUM('L','P'),
 no_hp VARCHAR(20),
 foto VARCHAR(255),
 created_at TIMESTAMP NULL,
 updated_at TIMESTAMP NULL,
 CONSTRAINT fk_mhs_user FOREIGN KEY(user_id) REFERENCES users(id)
);

CREATE TABLE ukm(
 id BIGINT AUTO_INCREMENT PRIMARY KEY,
 nama_ukm VARCHAR(100) NOT NULL,
 deskripsi TEXT,
 visi TEXT,
 misi TEXT,
 logo VARCHAR(255),
 pembina VARCHAR(100),
 ketua VARCHAR(100),
 kontak VARCHAR(30),
 email VARCHAR(100),
 instagram VARCHAR(100),
 lokasi_sekretariat VARCHAR(150),
 hari_latihan VARCHAR(100),
 status ENUM('aktif','nonaktif') DEFAULT 'aktif',
 created_at TIMESTAMP NULL,
 updated_at TIMESTAMP NULL
);

CREATE TABLE kriteria(
 id BIGINT AUTO_INCREMENT PRIMARY KEY,
 kode VARCHAR(10) UNIQUE,
 nama_kriteria VARCHAR(50),
 tipe ENUM('benefit','cost') NOT NULL,
 created_at TIMESTAMP NULL,
 updated_at TIMESTAMP NULL
);

CREATE TABLE sub_kriteria(
 id BIGINT AUTO_INCREMENT PRIMARY KEY,
 kriteria_id BIGINT NOT NULL,
 nama_subkriteria VARCHAR(150) NOT NULL,
 urutan INT,
 status ENUM('aktif','nonaktif') DEFAULT 'aktif',
 created_at TIMESTAMP NULL,
 updated_at TIMESTAMP NULL,
 FOREIGN KEY(kriteria_id) REFERENCES kriteria(id)
);

CREATE TABLE ukm_kriteria_profile(
 id BIGINT AUTO_INCREMENT PRIMARY KEY,
 ukm_id BIGINT NOT NULL,
 kriteria_id BIGINT NOT NULL,
 sub_kriteria_id BIGINT NOT NULL,
 nilai DECIMAL(10,2) NOT NULL,
 FOREIGN KEY(ukm_id) REFERENCES ukm(id),
 FOREIGN KEY(kriteria_id) REFERENCES kriteria(id),
 FOREIGN KEY(sub_kriteria_id) REFERENCES sub_kriteria(id)
);

CREATE TABLE ahp_runs(
 id BIGINT AUTO_INCREMENT PRIMARY KEY,
 created_by BIGINT NOT NULL,
 lambda_max DECIMAL(10,6),
 ci DECIMAL(10,6),
 ri DECIMAL(10,6),
 cr DECIMAL(10,6),
 status ENUM('valid','invalid'),
 created_at TIMESTAMP NULL,
 FOREIGN KEY(created_by) REFERENCES users(id)
);

CREATE TABLE ahp_pairwise(
 id BIGINT AUTO_INCREMENT PRIMARY KEY,
 ahp_run_id BIGINT NOT NULL,
 kriteria_row_id BIGINT NOT NULL,
 kriteria_col_id BIGINT NOT NULL,
 nilai DECIMAL(10,6) NOT NULL,
 FOREIGN KEY(ahp_run_id) REFERENCES ahp_runs(id),
 FOREIGN KEY(kriteria_row_id) REFERENCES kriteria(id),
 FOREIGN KEY(kriteria_col_id) REFERENCES kriteria(id)
);

CREATE TABLE ahp_weights(
 id BIGINT AUTO_INCREMENT PRIMARY KEY,
 ahp_run_id BIGINT NOT NULL,
 kriteria_id BIGINT NOT NULL,
 bobot DECIMAL(10,6) NOT NULL,
 FOREIGN KEY(ahp_run_id) REFERENCES ahp_runs(id),
 FOREIGN KEY(kriteria_id) REFERENCES kriteria(id)
);

CREATE TABLE questionnaire_sessions(
 id BIGINT AUTO_INCREMENT PRIMARY KEY,
 mahasiswa_id BIGINT NOT NULL,
 status ENUM('draft','completed') DEFAULT 'draft',
 started_at DATETIME,
 completed_at DATETIME,
 FOREIGN KEY(mahasiswa_id) REFERENCES mahasiswa(id)
);

CREATE TABLE questionnaire_answers(
 id BIGINT AUTO_INCREMENT PRIMARY KEY,
 session_id BIGINT NOT NULL,
 kriteria_id BIGINT NOT NULL,
 sub_kriteria_id BIGINT NOT NULL,
 ranking INT NULL,
 nilai DECIMAL(10,2) NULL,
 FOREIGN KEY(session_id) REFERENCES questionnaire_sessions(id),
 FOREIGN KEY(kriteria_id) REFERENCES kriteria(id),
 FOREIGN KEY(sub_kriteria_id) REFERENCES sub_kriteria(id)
);

CREATE TABLE topsis_runs(
 id BIGINT AUTO_INCREMENT PRIMARY KEY,
 mahasiswa_id BIGINT NOT NULL,
 questionnaire_session_id BIGINT NOT NULL,
 ahp_run_id BIGINT NOT NULL,
 created_at TIMESTAMP NULL,
 FOREIGN KEY(mahasiswa_id) REFERENCES mahasiswa(id),
 FOREIGN KEY(questionnaire_session_id) REFERENCES questionnaire_sessions(id),
 FOREIGN KEY(ahp_run_id) REFERENCES ahp_runs(id)
);

CREATE TABLE topsis_values(
 id BIGINT AUTO_INCREMENT PRIMARY KEY,
 topsis_run_id BIGINT NOT NULL,
 ukm_id BIGINT NOT NULL,
 kriteria_id BIGINT NOT NULL,
 nilai_x DECIMAL(10,6),
 nilai_r DECIMAL(10,6),
 nilai_v DECIMAL(10,6),
 FOREIGN KEY(topsis_run_id) REFERENCES topsis_runs(id),
 FOREIGN KEY(ukm_id) REFERENCES ukm(id),
 FOREIGN KEY(kriteria_id) REFERENCES kriteria(id)
);

CREATE TABLE topsis_ideals(
 id BIGINT AUTO_INCREMENT PRIMARY KEY,
 topsis_run_id BIGINT NOT NULL,
 kriteria_id BIGINT NOT NULL,
 ideal_positif DECIMAL(10,6),
 ideal_negatif DECIMAL(10,6),
 FOREIGN KEY(topsis_run_id) REFERENCES topsis_runs(id),
 FOREIGN KEY(kriteria_id) REFERENCES kriteria(id)
);

CREATE TABLE topsis_results(
 id BIGINT AUTO_INCREMENT PRIMARY KEY,
 topsis_run_id BIGINT NOT NULL,
 ukm_id BIGINT NOT NULL,
 s_plus DECIMAL(10,6),
 s_minus DECIMAL(10,6),
 nilai_preferensi DECIMAL(10,6),
 ranking INT,
 FOREIGN KEY(topsis_run_id) REFERENCES topsis_runs(id),
 FOREIGN KEY(ukm_id) REFERENCES ukm(id)
);

CREATE TABLE pendaftaran_ukm(
 id BIGINT AUTO_INCREMENT PRIMARY KEY,
 nomor_pendaftaran VARCHAR(30) UNIQUE,
 mahasiswa_id BIGINT NOT NULL,
 ukm_id BIGINT NOT NULL,
 topsis_result_id BIGINT,
 alasan_mendaftar TEXT,
 persetujuan BOOLEAN DEFAULT TRUE,
 status ENUM('menunggu_verifikasi','perlu_perbaikan','terverifikasi','selesai') DEFAULT 'menunggu_verifikasi',
 tanggal_daftar DATETIME,
 tanggal_verifikasi DATETIME NULL,
 FOREIGN KEY(mahasiswa_id) REFERENCES mahasiswa(id),
 FOREIGN KEY(ukm_id) REFERENCES ukm(id),
 FOREIGN KEY(topsis_result_id) REFERENCES topsis_results(id)
);

CREATE TABLE riwayat_status_pendaftaran(
 id BIGINT AUTO_INCREMENT PRIMARY KEY,
 pendaftaran_id BIGINT NOT NULL,
 status ENUM('menunggu_verifikasi','perlu_perbaikan','terverifikasi','selesai'),
 catatan TEXT,
 diubah_oleh BIGINT,
 created_at TIMESTAMP NULL,
 FOREIGN KEY(pendaftaran_id) REFERENCES pendaftaran_ukm(id),
 FOREIGN KEY(diubah_oleh) REFERENCES users(id)
);
