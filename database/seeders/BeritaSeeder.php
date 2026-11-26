<?php

namespace Database\Seeders;

use App\Models\Berita;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus berita dummy lama agar hanya 3 berita resmi yang tampil
        Berita::whereIn('slug', [
            'pendaftaran-pmb-pnm-jakarta-gelombang-1-dibuka',
            'jadwal-ujian-seleksi-jalur-mandiri-diumumkan',
            'informasi-tarif-ukt-tahun-akademik-2025-2026',
        ])->delete();

        $berita = [
            [
                'judul'        => 'Pemerintah Perluas Akses Kuliah, Rp15,6 Triliun Dialokasikan untuk Beasiswa 2026',
                'slug'         => 'pemerintah-perluas-akses-kuliah-rp156-triliun-dialokasikan-untuk-beasiswa-2026',
                'image_url'    => '/assets/images/KIPBeasiswa.jpg',
                'author'       => 'Sevia Hasanah/infopublik',
                'status'       => 'publish',
                'published_at' => now()->subDays(1),
                'deskripsi'    => <<<'EOT'
Pemerintah mengalokasikan anggaran beasiswa pendidikan tinggi sebesar Rp15,638 triliun pada 2026 dengan sasaran sebanyak 1.072.240 mahasiswa di seluruh Indonesia. Dari jumlah tersebut, sekitar 210 ribu merupakan mahasiswa baru penerima program beasiswa.

Menteri Pendidikan Tinggi, Sains, dan Teknologi (Mendiktisaintek), Brian Yuliarto, mengatakan dukungan pembiayaan tersebut merupakan bagian dari upaya pemerintah memperluas akses pendidikan tinggi, khususnya bagi masyarakat dari keluarga dengan keterbatasan ekonomi.

Hal tersebut disampaikan Brian dalam kegiatan Sarasehan dan Temu Nasional Mahasiswa Penerima Kartu Indonesia Pintar Kuliah (KIP Kuliah) dan Beasiswa Afirmasi Pendidikan Tinggi (ADik) di Graha Diktisaintek, Jakarta, Kamis (26/11/2026).

Brian menjelaskan, pemerintah terus meningkatkan kuota penerima KIP Kuliah setiap tahun. Namun, tingginya jumlah pendaftar membuat masih terdapat calon mahasiswa yang belum terjangkau bantuan pemerintah pusat.

“Kuota kita sampai ke 210 ribu itu naik dari tahun lalu dan setiap tahun kita mencoba menaikkan. Tentu masih ada yang belum terjaring,” ujar Brian.

Ia menambahkan, seluruh mahasiswa di perguruan tinggi negeri (PTN) yang berasal dari kelompok desil 1–4 juga memperoleh bantuan beasiswa pemerintah pada tahun ini.

Untuk memperluas jangkauan bantuan pendidikan, pemerintah turut mendorong keterlibatan pemerintah daerah dalam penyediaan beasiswa. Kolaborasi tersebut diharapkan dapat membantu calon mahasiswa yang belum memperoleh bantuan dari pemerintah pusat.

Sebagai langkah lanjutan, Kemdiktisaintek berencana menghadirkan portal Satu Beasiswa pada akhir Desember 2026. Portal ini akan mengintegrasikan informasi dan peluang beasiswa yang berasal dari pemerintah pusat, pemerintah daerah, maupun pihak swasta.

“Jadi kita berharap sebagian yang belum ditanggung oleh pemerintah pusat, itu harapannya bisa juga ditanggung Pemda atau dari sumber-sumber yang lain” jelasnya.

Selain memperluas bantuan pembiayaan, Brian menekankan pentingnya pendampingan bagi mahasiswa penerima beasiswa selama menjalani perkuliahan. Menurutnya, mahasiswa dari keluarga dengan keterbatasan ekonomi terkadang menghadapi kendala dalam beradaptasi, termasuk rasa malu dan kurang percaya diri untuk menyampaikan permasalahan yang dihadapi.
EOT,
            ],
            [
                'judul'        => 'Kemnaker Perluas Akses Pelatihan Vokasi Nasional 2026, Batasan Tahun Kelulusan Dihapus',
                'slug'         => 'kemnaker-perluas-akses-pelatihan-vokasi-nasional-2026-batasan-tahun-kelulusan-dihapus',
                'image_url'    => '/assets/images/BatasanUsia.jpg',
                'author'       => 'Kemnaker (Kementerian Ketenagakerjaan Republik Indonesia)',
                'status'       => 'publish',
                'published_at' => now()->subDays(2),
                'deskripsi'    => <<<'EOT'
Jakarta — Kementerian Ketenagakerjaan (Kemnaker) memperluas akses Program Pelatihan Vokasi Nasional 2026 Batch 1 dengan menghapus batasan tahun kelulusan. Kebijakan ini membuka peluang bagi lulusan SMA/SMK/MA atau sederajat, termasuk lulusan lama, untuk meningkatkan keterampilan dan peluang kerja, dengan target 20.000 peserta.

Direktur Jenderal Pembinaan Pelatihan Vokasi dan Produktivitas (Binalavotas) Kemnaker, Darmawansyah, mengatakan kebijakan ini diambil agar akses pelatihan tidak hanya bagi lulusan baru, tetapi juga lulusan lama yang membutuhkan peningkatan kompetensi. Sebelumnya, program ini hanya diperuntukkan bagi lulusan 2023–2025. Kini, ketentuan tersebut dihapus untuk memperluas akses pelatihan.

“Penghapusan batasan tahun kelulusan ini merupakan bentuk komitmen kami untuk membuka akses pelatihan seluas-luasnya bagi masyarakat. Siapa pun yang memiliki kemauan untuk meningkatkan keterampilan, kami fasilitasi melalui program ini,” ujar Darmawansyah dalam siaran pers Biro Humas Kemnaker, Jumat (20/3/2026).

Menurutnya, perubahan ini penting karena masih banyak lulusan SMA/SMK/MA sederajat yang belum sempat mengikuti pelatihan untuk memperkuat bekal kerja. Padahal, kebutuhan dunia kerja terus bergerak, sementara banyak pencari kerja memerlukan keterampilan yang relevan agar lebih siap bersaing.

“Ini menjadi peluang bagi lulusan lama untuk kembali meningkatkan kompetensinya, sehingga dapat lebih siap memasuki maupun bersaing di dunia kerja,” katanya.

Ia menambahkan, pelatihan vokasi yang diselenggarakan Kemnaker dirancang berbasis kebutuhan dunia usaha dan dunia industri (link and match), sehingga peserta dibekali keterampilan yang relevan dan aplikatif.

Kesempatan mengikuti pelatihan juga kini semakin terbuka lebar. Pendaftaran tidak hanya tersedia di Balai Pelatihan Vokasi dan Produktivitas (BPVP) Kemnaker atau yang juga dikenal sebagai Balai Latihan Kerja (BLK), tetapi juga melalui BLK milik pemerintah daerah. Keterlibatan lebih banyak lembaga pelatihan ini diharapkan dapat memperluas jangkauan layanan serta memudahkan masyarakat mengakses pelatihan di lebih banyak daerah.

Program Pelatihan Vokasi Nasional 2026 terbuka bagi Warga Negara Indonesia berusia minimal 17 tahun dan lulusan SMA/SMK/MA atau sederajat. Dengan dihapusnya batasan tahun kelulusan, seluruh lulusan kini memiliki kesempatan yang sama untuk mengikuti pelatihan dan meningkatkan daya saing.

Peserta pelatihan akan memperoleh berbagai fasilitas, antara lain pelatihan dan makan siang gratis, bantuan uang transportasi, perlindungan Jaminan Kecelakaan Kerja (JKK) dan Jaminan Kematian (JKM) melalui BPJS Ketenagakerjaan, sertifikat pelatihan dari BPVP, serta sertifikat kompetensi dari Badan Nasional Sertifikasi Profesi (BNSP). Fasilitas asrama juga tersedia sesuai kriteria dan ketersediaan.

Kemnaker mengajak masyarakat memanfaatkan kesempatan ini dengan segera mendaftar melalui platform Skillhub Kemnaker di laman skillhub.kemnaker.go.id sebelum batas akhir pendaftaran pada 24 Maret 2026.

“Kami mengajak masyarakat untuk memanfaatkan kesempatan ini sebaik-baiknya. Segera lakukan pendaftaran sebelum batas waktu yang telah ditentukan,” ujar Darmawansyah.

Biro Humas Kemnaker
EOT,
            ],
            [
                'judul'        => 'Pelatihan Vokasi Nasional 2026 Dimulai, Lebih dari 10 Ribu Peserta Ikuti Batch I',
                'slug'         => 'pelatihan-vokasi-nasional-2026-dimulai-lebih-dari-10-ribu-peserta-ikuti-batch-i',
                'image_url'    => '/assets/images/PelatihanVokasi.jpg',
                'author'       => 'Kemnaker (Kementerian Ketenagakerjaan Republik Indonesia)',
                'status'       => 'publish',
                'published_at' => now()->subDays(3),
                'deskripsi'    => <<<'EOT'
Bekasi — Pemerintah melalui Kementerian Ketenagakerjaan resmi memulai (kick-off) Program Pelatihan Vokasi Nasional (PVN) Batch I Tahun 2026 yang diikuti lebih dari 10 ribu peserta dari lulusan SMA dan SMK sederajat. Program ini dilaksanakan serentak secara nasional sebagai upaya meningkatkan kesiapan tenaga kerja memasuki dunia kerja.

Peluncuran program dipusatkan di Balai Besar Pelatihan Vokasi dan Produktivitas (BBPVP) Bekasi, Jawa Barat, Rabu (8/4/2026), dan dibuka langsung oleh Menteri Ketenagakerjaan Yassierli, didampingi Wakil Menteri Ketenagakerjaan Afriansyah Noor, Direktur Jenderal Binalavotas Darmawansyah, serta Wakil Wali Kota Bekasi Abdul Harris Bobihoe.

Dalam kesempatan tersebut, Yassierli menegaskan bahwa pelatihan vokasi merupakan salah satu program prioritas pemerintah dalam meningkatkan kualitas sumber daya manusia sekaligus mempercepat penyerapan tenaga kerja.
Sepanjang 2026, program ini menargetkan 70.000 peserta yang dibiayai melalui APBN.

"Untuk Batch I dimulai serentak sejak 1 April 2026. Antusiasme masyarakat terhadap program ini juga cukup tinggi, dengan total 29.850 pendaftar melalui platform Skillhub pada tahap pertama," ucap Menaker.

Dari jumlah tersebut, sebanyak 10.405 peserta dinyatakan lolos, terdiri atas 5.833 orang melalui jalur seleksi dan 4.572 orang melalui jalur kerja sama afirmatif dengan mitra industri dan masyarakat. Para peserta tersebar di 21 Balai Latihan Kerja (BLK), 13 satuan pelatihan di bawah Direktorat Jenderal Pembinaan Pelatihan Vokasi dan Produktivitas, serta 46 BLK UPTD.

Yassierli menegaskan, pelaksanaan pelatihan secara serentak ini bertujuan memberikan akses yang lebih adil dan merata bagi seluruh pencari kerja di Indonesia.

“Dengan sistem yang serentak ini, kita ingin memastikan setiap pencari kerja memiliki kesempatan yang sama untuk meningkatkan kompetensinya,” ujar Yassierli.
Program ini juga dirancang secara inklusif, termasuk memberikan kesempatan yang sama bagi penyandang disabilitas untuk mengikuti pelatihan.

Lebih dari sekadar jumlah peserta, program ini menunjukkan hasil konkret. Sekitar 50 persen peserta pada batch pertama ini telah memiliki komitmen penempatan kerja setelah menyelesaikan pelatihan, mencerminkan kuatnya keterkaitan antara pelatihan vokasi dan kebutuhan industri.

Menaker juga menegaskan bahwa manfaat program ini dirancang agar benar-benar dirasakan peserta. Mereka memperoleh pelatihan gratis, makan siang selama pelatihan, bantuan transportasi, perlindungan jaminan kecelakaan kerja dan jaminan kematian, sertifikat pelatihan, sertifikat kompetensi dari Badan Nasional Sertifikasi Profesi (BNSP), serta fasilitas asrama untuk pelatihan tertentu.

“Dukungan ini penting agar peserta dapat mengikuti pelatihan dengan lebih fokus tanpa terbebani biaya dasar selama proses belajar,” ucapnya.

Sementara itu, Direktur Jenderal Binalavotas, Darmawansyah, menyampaikan bahwa pelatihan vokasi ini dirancang untuk menjembatani kesenjangan antara kebutuhan industri dan ketersediaan tenaga kerja.

“Program ini diharapkan mampu mengurangi pengangguran, meningkatkan produktivitas, serta memperkuat daya saing angkatan kerja Indonesia,” ujarnya.

Ia menambahkan, untuk menjamin pelaksanaan yang transparan, inklusif, dan non-diskriminatif, proses pendaftaran dan seleksi dilakukan secara terpusat melalui platform Skillhub, sehingga prosesnya lebih transparan dan mudah dipantau.

With pendekatan yang semakin terintegrasi dan berorientasi pada kebutuhan industri, Program Pelatihan Vokasi Nasional 2026 diharapkan mampu memberikan dampak nyata dalam meningkatkan kualitas tenaga kerja sekaligus memperluas kesempatan kerja di Indonesia.

Biro Humas Kemnaker
EOT,
            ],
            [
                'judul'        => '169 Talenta Muda Berprestasi Terima Beasiswa Talenta Indonesia Dorong Prestasi Jadi Kontribusi Nyata',
                'slug'         => '169-talenta-muda-berprestasi-terima-beasiswa-talenta-indonesia-dorong-prestasi-jadi-kontribusi-nyata',
                'image_url'    => '/assets/images/169talenta.png',
                'author'       => 'Kontributor BKHM/Kemendikdasmen',
                'status'       => 'publish',
                'published_at' => now()->subDays(4),
                'deskripsi'    => <<<'EOT'
Sebanyak 169 talenta muda berprestasi ditetapkan sebagai penerima Beasiswa Talenta Indonesia Angkatan I 2026. Melalui beasiswa ini, Kementerian Pendidikan Dasar dan Menengah (Kemendikdasmen) melalui Pusat Pengembangan Prestasi dan Pembinaan Karakter (Puspreska) bekerja sama dengan Lembaga Pengelola Dana Pendidikan (LPDP), mendorong talenta muda berprestasi untuk melanjutkan pengembangan potensi melalui pendidikan tinggi serta menjadikan prestasi sebagai kontribusi nyata bagi masyarakat dan bangsa.

Kepala Puspreska, Kemendikdasmen, Rusprita Putri Utami, dalam penutupan Pembekalan Studi Beasiswa Talenta Indonesia Angkatan I 2026, mengatakan bahwa program ini memastikan prestasi yang diraih murid melalui berbagai ajang talenta dapat berlanjut pada pengembangan kompetensi dan pendidikan tinggi.

“Prestasi membuat kalian dikenal. Integritas membuat kalian dipercaya dan kontribusi membuat keberadaan kalian bermakna. Manfaatkan kesempatan beasiswa ini untuk berkontribusi bagi Indonesia,” ujar Rusprita, Minggu (1/11).

Ia berpesan agar para penerima beasiswa tidak hanya berorientasi pada gelar akademik dan penghargaan, tetapi juga mampu menghasilkan karya yang bermanfaat. “Jangan menunggu menjadi orang besar untuk mulai memberikan manfaat. Mulailah dari apa yang kalian miliki dan dari tempat kalian berada saat ini,” pesannya.

Lebih lanjut, Direktur Beasiswa Lembaga Pengelola Dana Pendidikan (LPDP), Dwi Larso, menyampaikan bahwa Beasiswa Talenta Indonesia merupakan bentuk kesinambungan pembinaan bagi murid berprestasi yang telah dikembangkan sejak jenjang pendidikan dasar hingga menengah.

“Kami melihat anak-anak yang sudah dibina sejak SD hingga SMA perlu mendapatkan kelanjutannya, sehingga bibit-bibit unggul yang sudah diidentifikasi oleh Puspreska bisa melanjutkan studi ke universitas terbaik dunia, baik di dalam maupun luar negeri,” ujar Dwi Larso.

Sebanyak 169 penerima telah ditetapkan sebagai penerima Beasiswa Talenta Indonesia. Jumlah tersebut terdiri atas 166 penerima beasiswa untuk studi di dalam negeri dan tiga penerima untuk studi di luar negeri. Mereka merupakan talenta berprestasi dari berbagai ajang talenta nasional dan internasional yang melanjutkan pendidikan tinggi di beragam perguruan tinggi dan bidang keilmuan.

Sebelum memulai perjalanan pendidikan tinggi, para penerima beasiswa telah mengikuti rangkaian Pembekalan Studi yang dirancang untuk memperkuat kesiapan akademik sekaligus karakter. Rangkaian pembekalan diawali pada 17 September 2026 dan dilanjutkan melalui empat sesi utama pada 2, 3, 16, dan 17 Oktober 2026.

Dalam kegiatan tersebut, peserta memperoleh delapan materi utama, meliputi Thriving in a New Learning Environment, Resilience and Adaptive Capacity, Growth and Learning Mindset: Talent vs Passion, Integrity in Academic Life, Leading Change, Tolerance and Living with Diversity, Purpose and Meaning, serta Social Responsibility.

Materi tersebut disusun untuk membangun kemampuan intrapersonal dan interpersonal penerima beasiswa, termasuk kemampuan beradaptasi dengan lingkungan baru, ketangguhan menghadapi tantangan, integritas akademik, kepemimpinan, toleransi, dan tanggung jawab sosial.

Rangkaian penutupan juga diisi dengan presentasi kelompok melalui Talent to Impact Project. Kegiatan ini menjadi ruang bagi penerima beasiswa untuk merefleksikan materi pembekalan sekaligus menyusun gagasan mengenai kontribusi yang dapat diwujudkan melalui ilmu pengetahuan dan kompetensi yang mereka miliki.

Salah satu penerima Beasiswa Talenta Indonesia, yakni Salwa Salsabila AZ dari SMAS Islam Atthirah, Sulawesi Selatan, mengutarakan rasa bangganya menjadi awardee Beasiswa Talenta Indonesia. Salwa sebelumnya merupakan peraih Medali Perak Olimpiade Penelitian Siswa Indonesia (OPSI) 2025. Ia melanjutkan pendidikan pada Program Studi Pendidikan Dokter, Universitas Islam Negeri Alauddin Makassar.

“Proses seleksi Beasiswa Talenta Indonesia menjadi pengalaman yang menantang. Melalui pembekalan saya mendapatkan wawasan baru, khususnya mengenai pola pikir berkembang, ketangguhan mental, integritas, toleransi, dan tanggung jawab sosial,” jelas Salwa.

Penerima Beasiswa Talenta lainnya, Muhammad Lingga Padantya Junaedi dari MAN 2 Kota Malang merupakan penerima beasiswa luar negeri yang melanjutkan pendidikan di University of Toronto, Kanada. Lingga merupakan peraih Medali Emas OPSI 2024 serta Medali Perak Young Inventors Challenge (YIC) 2025. “Materi Purpose and Meaning menjadi salah satu pembelajaran yang paling membekas selama pembekalan. Terima kasih kepada Kemendikdasmen dan Puspreska,” ucap Lingga.

Aspirasi kontribusi juga disampaikan Jovan Nathaniel Permana dari SMAN 1 Mataram, peraih Medali Perak Olimpiade Sains Nasional (OSN) 2025 cabang ajang Fisika ini mengungkapkan, dalam sepuluh tahun ke depan dirinya bercita-cita menjadi dosen atau peneliti di bidang fisika.

“Saya ingin mengembangkan berbagai penelitian yang tidak hanya berfokus pada fisika, tetapi juga melibatkan bidang ilmu lain. Harapannya, penelitian tersebut dapat menghasilkan pengetahuan yang lebih luas dan bermanfaat,” ungkap Jovan yang saat ini menempuh pendidikan di Fakultas Matematika dan Ilmu Pengetahuan Alam Institut Teknologi Bandung (FMIPA ITB).

Melalui Beasiswa Talenta Indonesia, Kemendikdasmen berharap dapat membangun kesinambungan pembinaan talenta, mulai dari identifikasi prestasi, pengembangan melalui pendidikan, hingga aktualisasi potensi dalam bentuk kontribusi nyata bagi Indonesia. *** (Penulis: Tim Puspreska, Editor: Destian, Stephanie)
EOT,
            ],
            [
                'judul'        => 'LPB Nasional 2026 Tumbuhkan Peneliti Muda, Siapkan Masa Depan Sains RI',
                'slug'         => 'lpb-nasional-2026-tumbuhkan-peneliti-muda-siapkan-masa-depan-sains-ri',
                'image_url'    => '/assets/images/LPBNasionaPelita.jpg',
                'author'       => 'Ezri Tri Suro/IDN Times',
                'status'       => 'publish',
                'published_at' => now()->subDays(5),
                'deskripsi'    => <<<'EOT'
Fakultas Teknik dan Informatika Universitas Multimedia Nusantara (FTI UMN) bersama Center for Young Scientist (CYS) kembali menyelenggarakan Lomba Penelitian Belia (LPB) Nasional 2026. Kegiatan ini turut mendapat dukungan dari Prof. Stella Christie, A.B., Ph.D., selaku Wakil Menteri Pendidikan Tinggi, Sains, dan Teknologi Republik Indonesia, sebagai bagian dari upaya mendorong pengembangan budaya riset dan inovasi sejak dini di kalangan generasi muda. Babak Nasional LPB 2026 menjadi wadah bagi para peneliti muda tingkat SMP-SMA untuk mempresentasikan hasil penelitian dan bertukar gagasan.

Tahun ini, LPB Nasional 2026 tidak hanya menjadi ajang kompetisi, tetapi juga konferensi riset bagi pelajar tingkat sekolah menengah (SMP-SMA) dalam 10 bidang penelitian, yaitu fisika, matematika, komputer, lingkungan, ilmu hayati, ekonomi, geografi, Sejarah, Sosiologi, dan Psikologi. Kegiatan ini diikuti oleh 262 tim peneliti, 482 siswa peneliti, 113 sekolah, 216 guru pembimbing, dan 33 dosen dari berbagai perguruan tinggi sebagai juri.

Pelaksanaan Lomba Penelitian Belia Nasional 2026 di Kampus UMN menjadi salah satu bentuk komitmen UMN dalam mendukung keberlanjutan ekosistem riset dan inovasi, khususnya melalui pendidikan yang berkualitas. Komitmen ini tidak hanya diwujudkan melalui pendidikan bagi mahasiswa, tetapi juga bagi siswa-siswi tingkat sekolah menengah. Sebagai bentuk apresiasi atas prestasi peserta LPB Nasional 2026, UMN memberikan dukungan berupa beasiswa potongan uang pangkal kuliah sebesar 100% bagi peraih juara pertama, 75% peraih juara kedua, dan 50% bagi peraih juara ketiga.

“Saat ini dunia semakin kompleks akibat perkembangan teknologi yang cepat. Penelitian diharapkan tidak berhenti pada laporan atau gelar juara saja, tetapi juga dapat memberikan dampak dan menghasilkan penemuan yang mampu menawarkan solusi nyata. Siswa-siswi yang mengikuti LPB merupakan generasi penerus yang nantinya akan turut mewujudkan Indonesia Emas 2045”, sambut Dr. Andrey Andoko selaku Rektor UMN.

1. Bukti ketekunan dan proses ilmiah
Andrey menegaskan bahwa keterlibatan para siswa dalam LPB merupakan bukti ketekunan dan proses ilmiah yang telah mereka lalui. Ia berharap, kegiatan ini bukan sekadar ajang perlombaan, melainkan juga wadah bagi para siswa untuk bertukar gagasan dengan sesama peneliti muda, berkolaborasi, dan membangun relasi.

“Tentu, peran ini juga tidak terlepas dari kontribusi para guru yang telah membimbing dan mengantarkan para siswa meraih prestasi serta membangun negara kita menjadi Indonesia yang kuat dan maju. Diharapkan melalui kegiatan ini, kita dapat bersama-sama membangun masa depan Indonesia yang lebih maju”, tutupnya.

Monika Raharti, Ph.D., selaku Direktur Center for Young Scientist (CYS), juga turut hadir dalam pembukaan LPB Nasional 2026. Dalam sambutannya, Monika menyoroti pentingnya LPB sebagai ruang bagi para peneliti muda untuk tidak hanya berkompetisi, tetapi juga membangun wawasan melalui pertukaran pengetahuan dan pengalaman antarpeserta.

“Walaupun disebut sebagai lomba, hal yang melekat pada kegiatan ini adalah konferensi. Di sini, siswa-siswi dapat saling belajar, membuka perspektif yang lebih luas, dan mengenal berbagai bidang keilmuan lainnya. Saya berharap para siswa-siswi dapat memberikan hasil terbaiknya,” ucap Monika.

2. Antara AI dan manusia peneliti
Pembukaan LPB Nasional 2026 juga dimeriahkan dengan kehadiran dan dukungan Prof. Stella Christie, A.B., Ph.D., selaku Wakil Menteri Pendidikan Tinggi, Sains, dan Teknologi Republik Indonesia. Pada kesempatan ini, Stella membawakan materi bertajuk “Inovasi: AI vs Manusia”.

“Sebagaimana kita ketahui, saat ini kecerdasan artifisial (artificial intelligence/AI) berada di mana-mana. Ini menjadi sebuah tantangan, terutama bagi para peneliti. Salah satu kasus yang baru terjadi adalah OpenAI yang berhasil memecahkan masalah ‘The Navier-Stokes Millennium Prize Problem’. Satu dari tujuh permasalahan matematika yang telah menjadi tantangan selama satu milenium berhasil dipecahkan oleh AI. Namun, di saat yang bersamaan, ada peneliti manusia yang hampir menyelesaikan masalah yang sama", papar Stella.

Lewat kasus ini, Stella mempertanyakan bagaimana peran ilmuwan di tengah perkembangan AI. Menurutnya, ilmuwan bukan sekadar individu yang berputar pada teori dan angka, melainkan juga sosok yang memiliki kepedulian terhadap persoalan sosial, peradaban, dan komunitas. Ilmuwan juga perlu membangun jejaring yang luas serta mengenal berbagai kalangan, tidak terbatas pada sesama ilmuwan. Sebab, penelitian tidak hanya bertujuan menghasilkan pengetahuan, tetapi juga memberikan pengaruh bagi dunia.

“Apakah dalam kurun waktu 10 tahun lagi penelitian akan digantikan oleh AI? Tentu tidak selama kita memiliki kemampuan berpikir kritis, analitis, mempertanyakan hal-hal yang sebelumnya tidak ada, dan kompetitif," tambah Stella.

Bagi Stella, kompetisi kerap dianggap sebagai hal yang tabu di Indonesia. Padahal, menurutnya, kompetitif merupakan salah satu karakter yang perlu dimiliki seorang peneliti. Ia menilai bahwa meskipun Indonesia memiliki banyak sumber daya manusia yang cerdas dan berpotensi, ketertinggalan di bidang sains dan teknologi masih menjadi tantangan yang perlu diatasi.

“Saya juga ingin menegaskan bahwa sebagai peneliti, kita tidak perlu takut mencoba hal baru atau takut melakukan kesalahan. Justru dari kesalahan itulah kita bisa menemukan penemuan baru. Dalam penelitian, kita melakukan eksperimen untuk menguji dan membuktikan apakah hipotesis yang kita ajukan benar atau justru salah. Terkadang, hasilnya memang menunjukkan bahwa hipotesis kita keliru, dan justru itulah bagian yang paling penting dari proses penelitian", tegasnya.

Stella juga menjelaskan bahwa proses tersebut menjadi salah satu nilai tambah yang dimiliki manusia dibandingkan AI. Dalam penelitian, kesalahan dan kegagalan yang tercatat dapat menjadi dasar bagi munculnya pemikiran baru. Ketika peneliti menemukan sesuatu yang sebelumnya belum diketahui, hal tersebut membuka ruang untuk memahami persoalan lebih dalam. Proses pencatatan dan pembelajaran dari kesalahan inilah yang menjadi bagian penting dalam perkembangan ilmu pengetahuan.

“Menurut saya, penelitian yang kita hasilkan bukan hanya menghasilkan definisi atau teorema, tetapi juga pemahaman yang mendalam, termasuk tentang apa yang benar dan bukan hanya apa yang salah. Apakah LPB masih akan ada? Saya yakin masih ada jika adik-adik semua mengerti dan memahami apa tujuan kita melakukan penelitian," tutup Stella. (WEB)
EOT,
            ],
        ];

        foreach ($berita as $item) {
            Berita::updateOrCreate(['slug' => $item['slug']], $item);
        }
    }
}
