<?php namespace App\Database\Seeds;

class InitSeeder extends \CodeIgniter\Database\Seeder
{
        public function run()
        {
                $initJK = [
                        ["nama" => "Laki - Laki"],
                        ["nama" => "Perempuan"],
                        ["nama" => "Tidak Ada Identitas"],
                ];
                $this->db->table('jenis_kelamin')->insertBatch($initJK);

                $initAgama = [
                        ["nama" => "Islam"],
                        ["nama" => "Protestan"],
                        ["nama" => "Katolik"],
                        ["nama" => "Hindu"],
                        ["nama" => "Buddha"],
                        ["nama" => "Konghucu"],
                        ["nama" => "Tidak Ada Identitas"],
                ];
                $this->db->table('agama')->insertBatch($initAgama);

                $initJenisDisabilitas = [
                        ["nama" => "Wicara"],
                        ["nama" => "Daksa"],
                        ["nama" => "Ganda"],
                        ["nama" => "Intelektual"],
                        ["nama" => "Celebral Palcy"],
                        ["nama" => "Netra"],
                        ["nama" => "Gangguan Jiwa"],
                ];
                $this->db->table('jenis_disabilitas')->insertBatch($initJenisDisabilitas);

                $initAlatBantu = [
                        ["nama" => "Kursi Roda"],
                        ["nama" => "Kruk"],
                        ["nama" => "Walker"],
                        ["nama" => "Kursi Roda Adaftive"],
                        ["nama" => "Tongkat"],
                        ["nama" => "Kaki Palsu"],
                        ["nama" => "Tangan Palsu"],
                        ["nama" => "Tidak Menggunakan Alat Bantu"],
                ];
                $this->db->table('alat_bantu')->insertBatch($initAlatBantu);
                
                $initFasilitasKesehatan = [
                        ["nama" => "BPJS PBI/KIS PEMERINTAH"],
                        ["nama" => "BPJS MANDIRI"],
                        ["nama" => "TIDAK PUNYA"],
                        ["nama" => "ASURANSI"],
                ];
                $this->db->table('fasilitas_kesehatan')->insertBatch($initFasilitasKesehatan);

                $initKeterampilan = [
                        ["nama" => "Pijat"],
                        ["nama" => "Bermain Musik"],
                        ["nama" => "Memasak"],
                        ["nama" => "Menjahit"],
                        ["nama" => "Membuat Keset"],
                ];
                $this->db->table('keterampilan')->insertBatch($initKeterampilan);
                
                $initOrganisasi = [
                        ["nama" => "Pertuni"],
                        ["nama" => "Itmi"],
                        ["nama" => "Gergatin"],
                        ["nama" => "PPDI"],
                        ["nama" => "Bina Akses"],
                        ["nama" => "Tidak Ikut Sama Sekali"],
                ];
                $this->db->table('organisasi')->insertBatch($initOrganisasi);
                
                $initPekerjaan = [
                        ["nama" => "Tidak Bekerja"],
                        ["nama" => "Sekolah"],
                ];
                $this->db->table('pekerjaan')->insertBatch($initPekerjaan);

                $initKebutuhanPelatihan = [
                        ["nama" => "Pijat"],
                        ["nama" => "Menjahit"],
                        ["nama" => "Bermain Musik"],
                        ["nama" => "Membuat Kerajinan"],
                ];
                $this->db->table('kebutuhan_pelatihan')->insertBatch($initKebutuhanPelatihan);

                $initKebutuhanPerawatan = [
                        ["nama" => "Makan/Minum"],
                        ["nama" => "Bimbingan Konseling"],
                        ["nama" => "Obat Obatan"],
                        ["nama" => "Peralatan Medis"],
                        ["nama" => "Kursi Roda"],
                        ["nama" => "Kruk"],
                        ["nama" => "Kaki Palsu"],
                        ["nama" => "Tongkat"],
                ];
                $this->db->table('kebutuhan_perawatan')->insertBatch($initKebutuhanPerawatan);
                
                $initKondisiDifabel = [
                        ["nama" => "Bisa Mandiri"],
                        ["nama" => "Tidak Bisa Mandiri"],
                        ["nama" => "Bed Ridden"],
                ];
                $this->db->table('kondisi_difabel')->insertBatch($initKondisiDifabel);

                $initKondisiOrangTua = [
                        ["nama" => "Mampu"],
                        ["nama" => "Tidak Mampu"],
                        ["nama" => "NO DTKS/BDT"],
                        ["nama" => "NON DTKS/BDT"],
                        ["nama" => "Tidak Ada Identitas"],
                ];
                $this->db->table('kondisi_orang_tua')->insertBatch($initKondisiOrangTua);
                
                $initUser = [
                    'nik'       => "330411",
                    'nama'       => "heraya fitra",
                    'tempat'       => "banjarnegara",
                    'tanggal'       => "07/01/2000",
                    'jenis'       => 1,
                    'no_telp'       => "0895378036526",
                    'desa'       => "gelang",
                    'rt'       => "02",
                    'rw'       => "03",
                    'kecamatan'       => "rakit",
                    'kabupaten'       => "banjarnegara",
                    'provinsi'       => "jawa tengah",
                    'status'       => 1,
                    'password'       => password_hash("123456",PASSWORD_DEFAULT),
                ];

                // Using Query Builder
                $this->db->table('users')->insert($initUser);
        }
}