<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@cnn.co.id',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]);
        }

        $articles = [
            // Politik - 4 artikel
            [
                'category_slug' => 'politik',
                'title' => 'Presiden Umumkan Program Reformasi Birokrasi Nasional 2025',
                'excerpt' => 'Pemerintah meluncurkan program reformasi birokrasi komprehensif yang bertujuan meningkatkan efisiensi pelayanan publik dan memberantas korupsi di seluruh instansi pemerintahan.',
                'content' => '<p>Jakarta - Presiden Indonesia mengumumkan program reformasi birokrasi nasional yang akan dimulai pada awal tahun 2025. Program ini mencakup digitalisasi sistem pemerintahan, peningkatan transparansi, dan penguatan akuntabilitas publik.</p><p>"Kita harus memastikan bahwa setiap rupiah dari uang rakyat digunakan dengan tepat dan efisien," ujar Presiden dalam konferensi pers di Istana Negara.</p><p>Program ini akan melibatkan seluruh kementerian dan lembaga negara dengan target penyelesaian dalam 3 tahun ke depan. Beberapa langkah konkret yang akan diambil antara lain implementasi sistem e-government, pengurangan birokrasi berbelit, dan peningkatan kesejahteraan ASN.</p>',
                'image' => 'https://picsum.photos/seed/politik1/800/500',
            ],
            [
                'category_slug' => 'politik',
                'title' => 'DPR Sahkan RUU Pemilu dengan Beberapa Catatan Penting',
                'excerpt' => 'Dewan Perwakilan Rakyat mengesahkan Rancangan Undang-Undang Pemilu dalam sidang paripurna dengan beberapa revisi dan catatan dari berbagai fraksi.',
                'content' => '<p>Jakarta - Dalam sidang paripurna yang berlangsung hingga larut malam, DPR RI secara resmi mengesahkan RUU Pemilu menjadi undang-undang. Keputusan ini diambil setelah melalui pembahasan panjang selama 6 bulan.</p><p>Ketua DPR menyatakan bahwa UU Pemilu yang baru ini diharapkan dapat menciptakan pemilu yang lebih demokratis, transparan, dan berintegritas. "Ini adalah komitmen kita untuk menciptakan sistem pemilu yang lebih baik," ujarnya.</p><p>Beberapa poin penting dalam UU baru ini mencakup pengaturan ambang batas pencalonan, mekanisme verifikasi calon, dan sistem pengawasan yang lebih ketat untuk mencegah kecurangan.</p>',
                'image' => 'https://picsum.photos/seed/politik2/800/500',
            ],
            [
                'category_slug' => 'politik',
                'title' => 'Menteri Luar Negeri Kunjungi 5 Negara ASEAN Bahas Kerja Sama Regional',
                'excerpt' => 'Kunjungan diplomatik ini bertujuan memperkuat kerja sama ekonomi dan keamanan di kawasan Asia Tenggara serta membahas isu-isu strategis regional.',
                'content' => '<p>Jakarta - Menteri Luar Negeri memulai kunjungan kerja ke lima negara ASEAN dalam rangka memperkuat hubungan bilateral dan multilateral Indonesia dengan negara-negara tetangga.</p><p>Agenda utama kunjungan ini meliputi pembahasan kerja sama ekonomi digital, penanganan perubahan iklim, dan koordinasi keamanan maritim di kawasan. "Indonesia berkomitmen untuk terus berperan aktif dalam menciptakan stabilitas dan kemakmuran di ASEAN," kata Menlu.</p><p>Kunjungan ini juga akan membahas persiapan KTT ASEAN tahun depan yang akan diselenggarakan di Jakarta, dengan fokus pada isu-isu strategis seperti perdagangan bebas dan konektivitas infrastruktur.</p>',
                'image' => 'https://picsum.photos/seed/politik3/800/500',
            ],
            [
                'category_slug' => 'politik',
                'title' => 'Pemerintah Alokasikan Anggaran Rp 50 Triliun untuk Infrastruktur Daerah',
                'excerpt' => 'Program percepatan pembangunan infrastruktur daerah akan fokus pada konektivitas antar wilayah dan peningkatan akses masyarakat terhadap layanan dasar.',
                'content' => '<p>Jakarta - Kementerian Keuangan mengumumkan alokasi anggaran sebesar Rp 50 triliun untuk program pembangunan infrastruktur di daerah. Dana ini akan didistribusikan ke seluruh provinsi dengan prioritas pada daerah tertinggal dan terluar.</p><p>Menteri Keuangan menjelaskan bahwa program ini merupakan bagian dari upaya pemerintah untuk menciptakan pemerataan pembangunan. "Tidak boleh ada lagi kesenjangan infrastruktur yang signifikan antara pusat dan daerah," tegasnya.</p><p>Proyek-proyek prioritas yang akan dikerjakan meliputi pembangunan jalan trans regional, jembatan penghubung antar pulau, fasilitas pelabuhan, dan infrastruktur pendukung pariwisata.</p>',
                'image' => 'https://picsum.photos/seed/politik4/800/500',
            ],

            // Ekonomi - 4 artikel
            [
                'category_slug' => 'ekonomi',
                'title' => 'Bank Indonesia Pertahankan Suku Bunga Acuan di Level 6%',
                'excerpt' => 'Keputusan ini diambil untuk menjaga stabilitas nilai tukar rupiah dan mengendalikan inflasi yang masih berada dalam target.',
                'content' => '<p>Jakarta - Bank Indonesia memutuskan untuk mempertahankan suku bunga acuan BI Rate di level 6% dalam Rapat Dewan Gubernur bulan ini. Keputusan ini sejalan dengan proyeksi inflasi yang terkendali dan stabilitas makroekonomi.</p><p>Gubernur BI menjelaskan bahwa kebijakan ini diambil setelah mempertimbangkan berbagai faktor ekonomi global dan domestik. "Kami akan terus memonitor perkembangan ekonomi dan siap mengambil langkah-langkah yang diperlukan," ujarnya.</p><p>Para analis ekonomi menyambut positif keputusan ini, menilai bahwa BI mengambil langkah yang tepat untuk menjaga momentum pertumbuhan ekonomi sambil tetap waspada terhadap risiko inflasi.</p>',
                'image' => 'https://picsum.photos/seed/ekonomi1/800/500',
            ],
            [
                'category_slug' => 'ekonomi',
                'title' => 'Rupiah Menguat ke Level Rp 15.200 per Dolar AS',
                'excerpt' => 'Penguatan rupiah didorong oleh membaiknya sentimen pasar terhadap perekonomian Indonesia dan aliran dana asing yang masuk ke pasar modal.',
                'content' => '<p>Jakarta - Nilai tukar rupiah terhadap dolar Amerika Serikat menguat signifikan di pasar spot hingga menyentuh level Rp 15.200. Penguatan ini merupakan yang tertinggi dalam tiga bulan terakhir.</p><p>Analis valas mengatakan bahwa penguatan rupiah didorong oleh kombinasi faktor fundamental ekonomi Indonesia yang solid dan melemahnya dolar AS di pasar global. "Investor asing melihat Indonesia sebagai destinasi investasi yang menarik," kata seorang analis.</p><p>Bank Indonesia menyatakan akan terus menjaga stabilitas nilai tukar melalui operasi pasar dan koordinasi dengan otoritas terkait untuk memastikan pergerakan rupiah tetap sesuai dengan fundamental ekonomi.</p>',
                'image' => 'https://picsum.photos/seed/ekonomi2/800/500',
            ],
            [
                'category_slug' => 'ekonomi',
                'title' => 'Inflasi Desember Terkendali di Angka 2,5 Persen',
                'excerpt' => 'BPS mencatat inflasi tahunan masih berada dalam target pemerintah, didorong oleh stabilitas harga pangan dan energi.',
                'content' => '<p>Jakarta - Badan Pusat Statistik (BPS) mengumumkan bahwa inflasi pada Desember 2024 tercatat sebesar 2,5 persen year-on-year. Angka ini masih berada dalam kisaran target inflasi pemerintah sebesar 2-4 persen.</p><p>Kepala BPS menjelaskan bahwa terkendalinya inflasi terutama didukung oleh stabilitas harga bahan pangan strategis dan kebijakan energi pemerintah. "Koordinasi antara kementerian terkait sangat efektif dalam menjaga stabilitas harga," ujarnya.</p><p>Ekonom menilai bahwa terkendalinya inflasi memberikan ruang bagi Bank Indonesia untuk mempertahankan kebijakan moneter yang akomodatif guna mendukung pertumbuhan ekonomi yang berkelanjutan.</p>',
                'image' => 'https://picsum.photos/seed/ekonomi3/800/500',
            ],
            [
                'category_slug' => 'ekonomi',
                'title' => 'Ekspor Indonesia Naik 8,5 Persen di Akhir Tahun 2024',
                'excerpt' => 'Peningkatan ekspor didorong oleh permintaan global yang membaik dan diversifikasi produk ekspor unggulan Indonesia.',
                'content' => '<p>Jakarta - Kementerian Perdagangan mencatat nilai ekspor Indonesia pada Desember 2024 mengalami peningkatan 8,5 persen dibanding periode yang sama tahun sebelumnya. Kinerja ekspor yang positif ini menjadi angin segar bagi perekonomian nasional.</p><p>Menteri Perdagangan menyatakan bahwa peningkatan ekspor didorong oleh sektor manufaktur, pertambangan, dan pertanian. "Produk-produk Indonesia semakin diterima di pasar global berkat peningkatan kualitas dan daya saing," katanya.</p><p>Pemerintah menargetkan pertumbuhan ekspor yang lebih tinggi di tahun 2025 melalui program promosi intensif, kemudahan perizinan, dan pengembangan pasar tujuan ekspor baru di berbagai kawasan.</p>',
                'image' => 'https://picsum.photos/seed/ekonomi4/800/500',
            ],

            // Teknologi - 4 artikel
            [
                'category_slug' => 'teknologi',
                'title' => 'Startup Indonesia Raih Pendanaan Seri C Senilai $100 Juta',
                'excerpt' => 'Startup e-commerce lokal berhasil menarik investasi besar dari investor global untuk ekspansi ke pasar Asia Tenggara.',
                'content' => '<p>Jakarta - Sebuah startup e-commerce Indonesia berhasil menutup pendanaan Seri C dengan total nilai $100 juta. Pendanaan ini dipimpin oleh beberapa venture capital terkemuka dari Silicon Valley dan Asia.</p><p>CEO startup tersebut mengatakan bahwa dana akan digunakan untuk ekspansi regional, pengembangan teknologi AI, dan peningkatan infrastruktur logistik. "Ini adalah momen penting dalam perjalanan kami untuk menjadi pemain utama di kawasan," ujarnya.</p><p>Para investor menyatakan optimisme terhadap potensi pertumbuhan ekonomi digital Indonesia yang diprediksi mencapai $130 miliar pada tahun 2025.</p>',
                'image' => 'https://picsum.photos/seed/teknologi1/800/500',
            ],
            [
                'category_slug' => 'teknologi',
                'title' => 'Indonesia Luncurkan Satelit Komunikasi Generasi Terbaru',
                'excerpt' => 'Peluncuran satelit ini menandai kemajuan signifikan program ruang angkasa Indonesia dan akan meningkatkan konektivitas internet di seluruh nusantara.',
                'content' => '<p>Jakarta - Indonesia berhasil meluncurkan satelit komunikasi generasi terbaru dari pusat peluncuran di luar negeri. Satelit ini dilengkapi dengan teknologi terkini yang mampu menyediakan bandwidth lebih besar untuk internet broadband.</p><p>Menteri Komunikasi dan Informatika menyatakan bahwa satelit ini akan mempercepat program digitalisasi nasional, terutama untuk daerah 3T (Tertinggal, Terdepan, dan Terluar). "Ini adalah investasi untuk masa depan bangsa," tegasnya.</p><p>Satelit ini diperkirakan akan beroperasi penuh dalam 3 bulan ke depan setelah menyelesaikan serangkaian tes dan kalibrasi. Target utamanya adalah menyediakan akses internet berkecepatan tinggi ke 10.000 desa di seluruh Indonesia.</p>',
                'image' => 'https://picsum.photos/seed/teknologi2/800/500',
            ],
            [
                'category_slug' => 'teknologi',
                'title' => 'Aplikasi Fintech Lokal Capai 50 Juta Pengguna Aktif',
                'excerpt' => 'Platform fintech terdepan Indonesia mencapai milestone penting dengan pertumbuhan pengguna yang pesat dalam 2 tahun terakhir.',
                'content' => '<p>Jakarta - Salah satu aplikasi fintech terkemuka di Indonesia mengumumkan telah mencapai 50 juta pengguna aktif bulanan. Pencapaian ini menempatkan aplikasi tersebut sebagai salah satu platform keuangan digital terbesar di Asia Tenggara.</p><p>Founder perusahaan mengatakan bahwa pertumbuhan pesat ini didorong oleh meningkatnya literasi keuangan digital dan kepercayaan masyarakat terhadap layanan fintech. "Kami fokus memberikan solusi keuangan yang mudah, aman, dan terjangkau," ujarnya.</p><p>Aplikasi ini menawarkan berbagai layanan mulai dari pembayaran digital, pinjaman online, investasi, hingga asuransi mikro. Perusahaan menargetkan untuk mengakuisisi 100 juta pengguna pada akhir tahun 2025.</p>',
                'image' => 'https://picsum.photos/seed/teknologi3/800/500',
            ],
            [
                'category_slug' => 'teknologi',
                'title' => 'Pemerintah Akan Bangun 1000 Smart City di Indonesia',
                'excerpt' => 'Program smart city nasional bertujuan meningkatkan kualitas hidup masyarakat melalui integrasi teknologi IoT dan big data.',
                'content' => '<p>Jakarta - Kementerian Dalam Negeri mengumumkan program ambisius untuk mengembangkan 1000 kota pintar (smart city) di seluruh Indonesia dalam 5 tahun ke depan. Program ini akan memanfaatkan teknologi Internet of Things (IoT), big data, dan kecerdasan buatan.</p><p>Menteri Dalam Negeri menjelaskan bahwa smart city akan meningkatkan efisiensi pelayanan publik, manajemen lalu lintas, pengelolaan sampah, dan keamanan kota. "Teknologi harus dimanfaatkan untuk kesejahteraan masyarakat," katanya.</p><p>Pilot project akan dimulai di 50 kota prioritas dengan fokus pada implementasi sistem transportasi cerdas, smart lighting, dan platform e-government yang terintegrasi.</p>',
                'image' => 'https://picsum.photos/seed/teknologi4/800/500',
            ],

            // Olahraga - 4 artikel
            [
                'category_slug' => 'olahraga',
                'title' => 'Timnas Indonesia Lolos ke Piala Dunia 2026',
                'excerpt' => 'Kemenangan dramatis di babak kualifikasi mengantarkan Tim Garuda ke pentas sepak bola tertinggi dunia setelah penantian panjang.',
                'content' => '<p>Jakarta - Timnas Indonesia berhasil meraih tiket ke Piala Dunia 2026 setelah mengalahkan rival regional dengan skor 2-1 di pertandingan kualifikasi terakhir. Pencapaian bersejarah ini disambut euphoria luar biasa dari seluruh pendukung sepak bola Indonesia.</p><p>Pelatih timnas menyatakan bangga dengan perjuangan para pemain. "Ini adalah hasil kerja keras seluruh tim dalam 4 tahun terakhir. Kami akan mempersiapkan diri sebaik mungkin untuk tampil maksimal di Piala Dunia," ujarnya.</p><p>Presiden PSSI mengapresiasi pencapaian ini dan berkomitmen untuk terus mengembangkan sepak bola Indonesia agar bisa bersaing di level internasional.</p>',
                'image' => 'https://picsum.photos/seed/olahraga1/800/500',
            ],
            [
                'category_slug' => 'olahraga',
                'title' => 'Atlet Indonesia Raih Medali Emas di Olimpiade Paris',
                'excerpt' => 'Prestasi gemilang atlet muda Indonesia mengharumkan nama bangsa di ajang olahraga paling bergengsi di dunia.',
                'content' => '<p>Paris - Atlet Indonesia berhasil meraih medali emas pertama di Olimpiade Paris 2024 pada cabang olahraga bulu tangkis. Kemenangan ini dicapai setelah pertandingan sengit melawan juara bertahan dari China.</p><p>Atlet berusia 22 tahun ini menunjukkan permainan luar biasa di final dengan mengalahkan lawannya 21-18, 18-21, 21-19. "Ini untuk Indonesia, untuk semua yang telah mendukung saya," katanya sambil menangis haru.</p><p>Menpora langsung memberikan bonus dan penghargaan atas prestasi gemilang ini. Total bonus yang diterima atlet mencapai Rp 5 miliar plus rumah dan mobil dari pemerintah dan sponsor.</p>',
                'image' => 'https://picsum.photos/seed/olahraga2/800/500',
            ],
            [
                'category_slug' => 'olahraga',
                'title' => 'Liga 1 Indonesia Masuk 20 Besar Liga Terbaik Dunia',
                'excerpt' => 'Peningkatan kualitas kompetisi dan infrastruktur mengangkat peringkat Liga 1 dalam penilaian federasi sepak bola internasional.',
                'content' => '<p>Jakarta - International Federation of Football History & Statistics (IFFHS) menempatkan Liga 1 Indonesia di peringkat 18 dalam daftar liga sepak bola terbaik dunia. Ini adalah pencapaian tertinggi dalam sejarah sepak bola profesional Indonesia.</p><p>Ketua PSSI menyatakan bahwa pencapaian ini adalah hasil dari reformasi total kompetisi, peningkatan standar stadion, dan profesionalisasi klub. "Kami akan terus bekerja untuk meningkatkan kualitas liga," ujarnya.</p><p>Peningkatan peringkat ini juga berdampak positif pada koefisien AFC, yang memberikan Indonesia lebih banyak slot di kompetisi klub Asia seperti AFC Champions League.</p>',
                'image' => 'https://picsum.photos/seed/olahraga3/800/500',
            ],
            [
                'category_slug' => 'olahraga',
                'title' => 'Indonesia Tuan Rumah SEA Games 2027',
                'excerpt' => 'Kepercayaan untuk menyelenggarakan pesta olahraga terbesar Asia Tenggara menjadi momentum bangkitnya prestasi olahraga nasional.',
                'content' => '<p>Jakarta - ASEAN Olympic Council resmi menunjuk Indonesia sebagai tuan rumah SEA Games 2027. Keputusan ini diambil dalam sidang umum yang dihadiri oleh delegasi dari 11 negara anggota.</p><p>Menpora menyambut antusias keputusan ini dan menjanjikan penyelenggaraan SEA Games yang spektakuler. "Kita akan mempersiapkan dengan matang mulai dari infrastruktur olahraga, akomodasi, hingga sistem transportasi," katanya.</p><p>Pemerintah mengalokasikan anggaran Rp 10 triliun untuk pembangunan venue olahraga baru dan renovasi fasilitas existing. Target utamanya adalah meraih juara umum dan menunjukkan kemajuan olahraga Indonesia kepada dunia.</p>',
                'image' => 'https://picsum.photos/seed/olahraga4/800/500',
            ],

            // Hiburan - 4 artikel
            [
                'category_slug' => 'hiburan',
                'title' => 'Film Indonesia Menang di Festival Cannes 2025',
                'excerpt' => 'Sutradara muda Indonesia meraih Palme d\'Or untuk karya perdananya, mengharumkan nama perfilman tanah air di kancah internasional.',
                'content' => '<p>Cannes - Sebuah film Indonesia berhasil memenangkan penghargaan tertinggi di Festival Film Cannes 2025. Ini adalah kali pertama dalam sejarah film Indonesia meraih Palme d\'Or, penghargaan paling bergengsi dalam dunia perfilman.</p><p>Sutradara film tersebut menyampaikan rasa syukur dan bangga bisa mengangkat cerita Indonesia ke panggung dunia. "Film ini adalah representasi dari kekayaan budaya dan kisah manusia Indonesia," ujarnya dalam pidato penerimaan penghargaan.</p><p>Film yang mengangkat tema kehidupan nelayan tradisional di pesisir Jawa ini mendapat standing ovation 15 menit di premiere-nya dan dipuji oleh kritikus internasional sebagai masterpiece.</p>',
                'image' => 'https://picsum.photos/seed/hiburan1/800/500',
            ],
            [
                'category_slug' => 'hiburan',
                'title' => 'Konser Musik K-Pop Terbesar di Indonesia Sold Out dalam 5 Menit',
                'excerpt' => 'Antusiasme tinggi fans musik Korea membuat tiket konser boy group papan atas habis terjual dalam waktu singkat.',
                'content' => '<p>Jakarta - Tiket konser grup K-Pop terkenal di Jakarta habis terjual dalam waktu 5 menit setelah dibuka untuk umum. Total 80.000 tiket untuk konser dua hari di Jakarta International Stadium ludes diserbu penggemar.</p><p>Promotor konser menyatakan ini adalah salah satu penjualan tiket tercepat dalam sejarah konser musik di Indonesia. "Demand sangat tinggi, bahkan kami sudah menambah kapasitas venue tapi tetap tidak cukup," kata perwakilan promotor.</p><p>Konser yang dijadwalkan pada Maret 2025 ini akan menampilkan full setlist dengan produksi panggung kelas dunia, termasuk special stage yang hanya dipentaskan di Jakarta.</p>',
                'image' => 'https://picsum.photos/seed/hiburan2/800/500',
            ],
            [
                'category_slug' => 'hiburan',
                'title' => 'Serial Drama Indonesia Tembus Netflix Global Top 10',
                'excerpt' => 'Produksi original Indonesia masuk jajaran 10 besar konten paling banyak ditonton di platform streaming global.',
                'content' => '<p>Jakarta - Serial drama produksi Indonesia berhasil masuk dalam daftar Top 10 Netflix Global untuk kategori non-English TV. Pencapaian ini menunjukkan semakin diakuinya kualitas produksi konten Indonesia di pasar internasional.</p><p>Sutradara serial tersebut mengungkapkan rasa bangga atas pencapaian ini. "Kami membuktikan bahwa konten Indonesia bisa bersaing di level global jika dibuat dengan serius dan profesional," katanya.</p><p>Serial yang bercerita tentang kehidupan modern Jakarta dengan sentuhan thriller dan misteri ini telah ditonton oleh lebih dari 50 juta akun Netflix di 190 negara dalam minggu pertama perilisannya.</p>',
                'image' => 'https://picsum.photos/seed/hiburan3/800/500',
            ],
            [
                'category_slug' => 'hiburan',
                'title' => 'Festival Musik Jazz Terbesar Asia Tenggara Digelar di Bali',
                'excerpt' => 'Event tahunan ini menghadirkan lebih dari 100 musisi jazz dari berbagai negara dalam rangkaian pertunjukan 3 hari.',
                'content' => '<p>Bali - Java Jazz Festival 2025 resmi dibuka dengan kemeriahan luar biasa di Nusa Dua, Bali. Festival musik jazz terbesar di Asia Tenggara ini menghadirkan line-up musisi internasional dan lokal yang spektakuler.</p><p>Direktur festival mengatakan bahwa tahun ini jumlah pengunjung diprediksi mencapai 150.000 orang dari berbagai negara. "Java Jazz bukan hanya festival musik, tapi juga perayaan budaya dan destinasi wisata," ujarnya.</p><p>Festival yang berlangsung selama 3 hari ini menampilkan 12 stage dengan berbagai genre musik dari jazz klasik, fusion, hingga contemporary. Selain pertunjukan, ada juga workshop, talkshow, dan pameran alat musik.</p>',
                'image' => 'https://picsum.photos/seed/hiburan4/800/500',
            ],

            // Lifestyle - 4 artikel
            [
                'category_slug' => 'lifestyle',
                'title' => '10 Destinasi Wisata Tersembunyi di Indonesia yang Wajib Dikunjungi',
                'excerpt' => 'Jelajahi keindahan alam Indonesia yang masih perawan dan belum terjamah wisatawan massal untuk pengalaman liburan yang tak terlupakan.',
                'content' => '<p>Indonesia menyimpan ribuan destinasi wisata tersembunyi yang menawarkan keindahan luar biasa. Dari pantai dengan pasir putih bersih hingga air terjun di tengah hutan tropis, semuanya siap memanjakan mata dan jiwa Anda.</p><p>Beberapa destinasi yang direkomendasikan antara lain Pulau Weh di Aceh dengan spot diving terbaik, Danau Labuan Cermin di Kalimantan yang airnya jernih seperti kaca, dan Raja Ampat Papua yang menjadi surga bagi para penyelam.</p><p>Travel blogger senior menyarankan untuk mengunjungi tempat-tempat ini di luar musim puncak agar bisa menikmati keindahan alam dengan lebih privat dan mendapat harga yang lebih terjangkau.</p>',
                'image' => 'https://picsum.photos/seed/lifestyle1/800/500',
            ],
            [
                'category_slug' => 'lifestyle',
                'title' => 'Tren Fashion 2025: Sustainable Fashion Jadi Pilihan Utama',
                'excerpt' => 'Kesadaran lingkungan mendorong industri fashion beralih ke material ramah lingkungan dan proses produksi yang berkelanjutan.',
                'content' => '<p>Jakarta - Industri fashion Indonesia menunjukkan tren positif dengan semakin banyak brand yang mengadopsi konsep sustainable fashion. Penggunaan material organik, daur ulang, dan proses produksi ramah lingkungan menjadi prioritas utama.</p><p>Fashion designer ternama Indonesia mengatakan bahwa konsumen saat ini lebih aware terhadap dampak lingkungan dari produk yang mereka beli. "Sustainable fashion bukan hanya trend, tapi kebutuhan untuk masa depan planet kita," ujarnya.</p><p>Beberapa brand lokal bahkan telah mendapat sertifikasi internasional untuk praktik sustainable mereka, membuka peluang ekspor ke pasar global yang semakin concern terhadap isu lingkungan.</p>',
                'image' => 'https://picsum.photos/seed/lifestyle2/800/500',
            ],
            [
                'category_slug' => 'lifestyle',
                'title' => 'Tips Hidup Sehat: 5 Kebiasaan Sederhana untuk Meningkatkan Imunitas',
                'excerpt' => 'Ahli kesehatan berbagi tips praktis yang bisa dilakukan sehari-hari untuk menjaga kesehatan dan meningkatkan daya tahan tubuh.',
                'content' => '<p>Menjaga kesehatan dan imunitas tubuh tidak harus dengan cara yang rumit. Dokter spesialis gizi menyarankan 5 kebiasaan sederhana yang bisa dilakukan setiap hari untuk meningkatkan daya tahan tubuh.</p><p>Kebiasaan tersebut meliputi: 1) Konsumsi air putih minimal 8 gelas per hari, 2) Tidur cukup 7-8 jam, 3) Olahraga ringan minimal 30 menit, 4) Konsumsi buah dan sayur beragam, dan 5) Kelola stress dengan meditasi atau yoga.</p><p>"Kunci hidup sehat adalah konsistensi. Tidak perlu langkah besar, yang penting rutin dilakukan setiap hari," kata dokter dalam seminar kesehatan virtual yang dihadiri ribuan peserta.</p>',
                'image' => 'https://picsum.photos/seed/lifestyle3/800/500',
            ],
            [
                'category_slug' => 'lifestyle',
                'title' => 'Kuliner Nusantara Masuk dalam Daftar 50 Best Foods in The World',
                'excerpt' => 'Rendang dan Nasi Goreng Indonesia kembali masuk dalam jajaran makanan terenak di dunia versi survei kuliner internasional.',
                'content' => '<p>Jakarta - Dua makanan khas Indonesia, Rendang dan Nasi Goreng, kembali masuk dalam daftar 50 Best Foods in The World yang dirilis oleh CNN International. Rendang menempati posisi ke-3 sementara Nasi Goreng di posisi ke-9.</p><p>Chef internasional yang menjadi juri menyatakan bahwa kompleksitas rasa dan teknik memasak Rendang yang membutuhkan waktu lama menciptakan cita rasa yang sulit ditandingi. "Rendang adalah karya seni kuliner," ujarnya.</p><p>Kementerian Pariwisata melihat ini sebagai peluang besar untuk mempromosikan kuliner Indonesia ke mancanegara. Program "Wonderful Indonesian Cuisine" akan diluncurkan untuk memperkenalkan lebih banyak hidangan tradisional kepada dunia.</p>',
                'image' => 'https://picsum.photos/seed/lifestyle4/800/500',
            ],

            // Internasional - 4 artikel
            [
                'category_slug' => 'internasional',
                'title' => 'KTT G20 Hasilkan Kesepakatan Global untuk Perubahan Iklim',
                'excerpt' => 'Para pemimpin negara G20 mencapai konsensus untuk mempercepat transisi energi bersih dan mengurangi emisi karbon global.',
                'content' => '<p>New Delhi - Konferensi Tingkat Tinggi (KTT) G20 berhasil menghasilkan deklarasi bersama yang berisi komitmen kuat untuk mengatasi perubahan iklim. Seluruh negara anggota sepakat untuk mempercepat program dekarbonisasi dan investasi energi terbarukan.</p><p>Sekretaris Jenderal PBB menyambut baik hasil KTT ini dan menyebutnya sebagai langkah bersejarah dalam perjuangan melawan krisis iklim. "Ini adalah momentum yang kita tunggu-tunggu," katanya dalam konferensi pers.</p><p>Kesepakatan mencakup komitmen pendanaan $500 miliar untuk negara berkembang dalam program adaptasi perubahan iklim dan target net zero emission pada 2050.</p>',
                'image' => 'https://picsum.photos/seed/internasional1/800/500',
            ],
            [
                'category_slug' => 'internasional',
                'title' => 'Terobosan Ilmiah: Vaksin Baru untuk Kanker Lulus Uji Klinis',
                'excerpt' => 'Peneliti internasional berhasil mengembangkan vaksin revolusioner yang menunjukkan hasil menjanjikan dalam melawan berbagai jenis kanker.',
                'content' => '<p>Boston - Tim peneliti dari berbagai universitas terkemuka dunia mengumumkan keberhasilan uji klinis fase 3 untuk vaksin kanker inovatif. Vaksin ini menunjukkan efektivitas hingga 70% dalam mencegah perkembangan sel kanker.</p><p>Kepala tim peneliti menjelaskan bahwa vaksin bekerja dengan melatih sistem imun tubuh untuk mengenali dan menyerang sel kanker secara spesifik. "Ini adalah breakthrough yang bisa mengubah cara kita melawan kanker," ujarnya.</p><p>FDA Amerika Serikat diperkirakan akan memberikan persetujuan penggunaan vaksin ini dalam 6 bulan ke depan, membuka harapan baru bagi jutaan pasien kanker di seluruh dunia.</p>',
                'image' => 'https://picsum.photos/seed/internasional2/800/500',
            ],
            [
                'category_slug' => 'internasional',
                'title' => 'Ekonomi Global Diprediksi Tumbuh 3,5 Persen di Tahun 2025',
                'excerpt' => 'IMF merilis proyeksi pertumbuhan ekonomi dunia yang optimistis didorong oleh pemulihan sektor manufaktur dan perdagangan.',
                'content' => '<p>Washington - International Monetary Fund (IMF) memprediksi pertumbuhan ekonomi global akan mencapai 3,5 persen pada tahun 2025. Angka ini lebih tinggi dari proyeksi sebelumnya sebesar 3,2 persen.</p><p>Managing Director IMF menyatakan bahwa outlook positif ini didukung oleh membaiknya kondisi ekonomi di negara-negara maju dan emerging markets. "Risiko resesi global semakin kecil," katanya.</p><p>Namun, IMF tetap mengingatkan bahwa masih ada tantangan seperti ketegangan geopolitik, inflasi yang belum sepenuhnya terkendali, dan perubahan iklim yang bisa mempengaruhi proyeksi pertumbuhan.</p>',
                'image' => 'https://picsum.photos/seed/internasional3/800/500',
            ],
            [
                'category_slug' => 'internasional',
                'title' => 'Misi Antariksa Mars 2025 Berhasil Mendarat dengan Selamat',
                'excerpt' => 'Wahana antariksa multinasional sukses mendarat di planet merah untuk misi eksplorasi mencari tanda-tanda kehidupan.',
                'content' => '<p>Houston - Misi eksplorasi Mars yang melibatkan kerja sama beberapa badan antariksa dunia berhasil mendaratkan rover terbarunya di permukaan planet merah. Misi ini bertujuan untuk mengumpulkan sampel tanah dan mencari bukti kehidupan mikroba.</p><p>Direktur NASA menyatakan bahwa ini adalah misi paling ambisius dalam sejarah eksplorasi Mars. "Teknologi yang kita gunakan jauh lebih canggih dari misi sebelumnya," ujarnya dalam konferensi pers.</p><p>Rover dilengkapi dengan instrumen saintifik tercanggih termasuk spektrometer, kamera resolusi tinggi, dan drill untuk mengambil sampel dari kedalaman permukaan Mars. Data yang dikumpulkan akan membantu ilmuwan memahami lebih dalam tentang potensi kehidupan di planet lain.</p>',
                'image' => 'https://picsum.photos/seed/internasional4/800/500',
            ],

            // Otomotif - 4 artikel
            [
                'category_slug' => 'otomotif',
                'title' => 'Mobil Listrik Produksi Indonesia Siap Meluncur ke Pasar Global',
                'excerpt' => 'Produsen otomotif nasional berhasil mengembangkan kendaraan listrik dengan teknologi baterai yang kompetitif dan harga terjangkau.',
                'content' => '<p>Jakarta - PT Mobil Listrik Indonesia mengumumkan peluncuran model electric vehicle (EV) pertamanya yang akan dipasarkan baik domestik maupun ekspor. Mobil ini diklaim memiliki jangkauan hingga 500 km dengan sekali pengisian.</p><p>CEO perusahaan menjelaskan bahwa mobil listrik ini dirancang khusus untuk kondisi tropis dengan sistem pendinginan baterai yang canggih. "Harganya juga sangat kompetitif, 30% lebih murah dari kompetitor luar negeri," katanya.</p><p>Pemerintah mendukung penuh program ini dengan memberikan insentif pajak dan pengembangan infrastruktur charging station di seluruh Indonesia. Target penjualan tahun pertama adalah 50.000 unit.</p>',
                'image' => 'https://picsum.photos/seed/otomotif1/800/500',
            ],
            [
                'category_slug' => 'otomotif',
                'title' => 'Formula E Jakarta E-Prix Sukses Digelar dengan Antusias Tinggi',
                'excerpt' => 'Gelaran balapan mobil listrik kelas dunia di Jakarta menarik perhatian puluhan ribu penonton dan mempromosikan mobilitas berkelanjutan.',
                'content' => '<p>Jakarta - Sirkuit jalanan di kawasan Ancol menjadi saksi balapan sengit Formula E Jakarta E-Prix 2025. Event ini dihadiri lebih dari 60.000 penonton yang memadati grandstand dan fan zone.</p><p>Gubernur DKI Jakarta menyatakan bahwa penyelenggaraan Formula E adalah bagian dari komitmen Jakarta untuk mendorong adopsi kendaraan listrik. "Ini bukan hanya soal olahraga, tapi juga edukasi masyarakat tentang masa depan transportasi," ujarnya.</p><p>Selain balapan utama, event ini juga menggelar exhibition zone dimana pengunjung bisa test drive berbagai mobil listrik, seminar teknologi EV, dan konser musik yang menghibur penonton.</p>',
                'image' => 'https://picsum.photos/seed/otomotif2/800/500',
            ],
            [
                'category_slug' => 'otomotif',
                'title' => 'Teknologi Self-Driving Mulai Diuji Coba di Jalan Raya Indonesia',
                'excerpt' => 'Pilot project kendaraan otonom dimulai di beberapa rute terpilih sebagai langkah menuju transportasi masa depan yang lebih aman.',
                'content' => '<p>Surabaya - Kementerian Perhubungan secara resmi memberikan izin uji coba kendaraan self-driving di beberapa rute khusus di Surabaya. Ini adalah langkah awal menuju implementasi teknologi autonomous vehicle di Indonesia.</p><p>Direktur Jenderal Perhubungan Darat menjelaskan bahwa uji coba ini akan berlangsung selama 6 bulan dengan monitoring ketat. "Safety adalah prioritas utama, teknologi ini harus benar-benar mature sebelum diizinkan untuk penggunaan komersial," katanya.</p><p>Kendaraan yang diuji dilengkapi dengan berbagai sensor, kamera 360 derajat, radar, dan sistem AI yang mampu mendeteksi hambatan, menyesuaikan kecepatan, dan mengambil keputusan dalam milidetik.</p>',
                'image' => 'https://picsum.photos/seed/otomotif3/800/500',
            ],
            [
                'category_slug' => 'otomotif',
                'title' => 'Penjualan Motor Listrik di Indonesia Naik 150 Persen',
                'excerpt' => 'Pertumbuhan eksponensial penjualan sepeda motor listrik menunjukkan shifting preferensi konsumen terhadap kendaraan ramah lingkungan.',
                'content' => '<p>Jakarta - Data Asosiasi Industri Sepeda Motor Indonesia (AISI) menunjukkan penjualan motor listrik pada kuartal pertama 2025 melonjak 150% dibanding periode yang sama tahun lalu. Total unit terjual mencapai 300.000 unit.</p><p>Ketua AISI mengatakan bahwa pertumbuhan ini didorong oleh berbagai faktor termasuk harga yang semakin terjangkau, infrastruktur charging yang membaik, dan kesadaran lingkungan yang meningkat. "Trend ini akan terus berlanjut," prediksinya.</p><p>Produtor motor listrik lokal juga mulai ekspansi produksi dengan target meraup 30% market share sepeda motor baru pada 2026. Pemerintah terus mendorong melalui berbagai insentif dan program konversi kendaraan konvensional ke listrik.</p>',
                'image' => 'https://picsum.photos/seed/otomotif4/800/500',
            ],
        ];

        foreach ($articles as $data) {
            $category = Category::where('slug', $data['category_slug'])->first();
            if (!$category) continue;

            $slug = Str::slug($data['title']);
            $originalSlug = $slug;
            $counter = 1;
            while (Article::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            Article::create([
                'admin_id' => $user->id,
                'category_id' => $category->id,
                'title' => $data['title'],
                'slug' => $slug,
                'excerpt' => $data['excerpt'],
                'content' => $data['content'],
                'featured_image' => null,
                'status' => 'published',
                'published_at' => now()->subDays(rand(0, 30)),
                'view_count' => rand(100, 10000),
            ]);

            $this->command->info("Created article: {$data['title']}");
        }

        $this->command->info('Article seeder completed!');
    }
}
