<?php

namespace App\Http\Controllers;

use View;

use Illuminate\Http\Request;
use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;
// use Illuminate\Support\Facades\DB;


use App\Skripsi;

class SkripsiController extends Controller
{
    public function sastrawi($word = 'default')
    {
        //////// stop word removal ///////
        // $word = 'kepada dikau yang selalu mencinta tanpa berkabar-kabar';
        $stopWordRemoverFactory = new StopWordRemoverFactory;
        $stopWordRemover = $stopWordRemoverFactory->createStopWordRemover();
        $stopWordRemoved = $stopWordRemover->remove($word);

        //////// stemming ////////
        $stemmerFactory = new StemmerFactory();
        $stemmer  = $stemmerFactory->createStemmer();
        $steamed   = $stemmer->stem($stopWordRemoved);

        return [
            "origin_sentence"=>$word,
            "removed_stopword"=>$stopWordRemoved,
            "stemmed" => $steamed
        ];
        // echo $output . "\n";
        // // ekonomi indonesia sedang dalam tumbuh yang bangga

        // echo $stemmer->stem('Mereka meniru-nirukannya') . "\n";
        // // mereka tiru
    }
    public function index()
    {
        // $data['page_title'] = "Skripsi";
        ///////////////////// batas ////////////////////
        $mydata = Skripsi::all();
        return view('page.skripsi', ['datas' => $mydata]);
        // return ;
    }

    public function gateway(Request $request)
    {
        switch ($request->input('action')) {
            case 'check':
                return Self::mainProcess($request);
                break;
            case 'insert':
                return Self::insert($request);
                break;

            case 'index':
                return Self::index();
                break;
        }
    }

    public function preProcessing($request)
    {
        function filtering($b)
        {
            $teksTemp = $b;
            $query = mysql_query("SELECT * FROM stopword");
            while ($row = @mysql_fetch_array($query)) {
                $stopword[] = trim($row['stopword']);
            }
            $pieces = explode(" ", $teksTemp);
            $jml = count($pieces) - 1;
            for ($i = 0; $i <= $jml; $i++) {
                if (in_array($pieces[$i], $stopword)) {
                    unset($pieces[$i]);
                }
            }
            $removal = implode(" ", $pieces);
            return $teksTemp;
        }
    }

    public function mainProcess($request)
    {
        // return 'yay im called';
        $db = Skripsi::all();
        $db_finger = [];
        $kgram = 8;
        $hasilproses = [];
        $data['hasilproses'] = $hasilproses;
        $data['db'] = $db;

        // memproses DB
        foreach ($db as $d) { //perulangan setiap value pada database
            // $algoritm = Self::rabinkarp($d->judulskripsi, $kgram);
            $algoritm = Self::rabinkarp($d->judulskripsi_preprocessed, $kgram);
            $db_finger[$d->nim] = $algoritm; //memasukkan array fingerprint dari db kedalam db_finger
        }

        // Return Value
        $request_judul_preprocessed = Self::sastrawi($request->judul);
        $algoritm = Self::rabinkarp($request_judul_preprocessed['stemmed'], $kgram);
        $dice = Self::diceCoefficient($db_finger, $algoritm);

        // insert into array db
        $temp_db = $db;
        foreach ($temp_db as $key => $val) {
            $db[$key]->similarity = $dice[$val->nim];
        }

        // maximum limit reached
        $temp_db = $db;
        $max_limit = 50; // in percent
        foreach ($temp_db as $key => $val) {
            if ($val->similarity <= $max_limit) {
                unset($db[$key]);
            } else {
                continue;
            }
        }

        return view('page.skripsi', ['formdata' => $request, 'datas' => $db]);
    }

    public function insert(Request $request)
    {
        // $preprocessed;
        $preprocessed = $this->sastrawi($request->judul);

        $data = new Skripsi();
        $data->nim = $request->nim;
        $data->nama = $request->nama;
        $data->judulskripsi = $request->judul;
        $data->judulskripsi_preprocessed = $preprocessed['stemmed'];
        $data->save();

        return redirect()->route('skripsi');
    }

    private function rabinkarp($judul, $kgram)
    {
        $judulmod = str_replace(' ', '', $judul);
        $judulkecil = strtolower($judulmod);
        $lenght = strlen($judulmod);
        $perstring = []; //menampung hasil pemisahan string
        $ascii = []; //menampung hasil konversi string ke ascii
        $hash = []; //menampung hasil pemisahan string
        // $result =[];

        //PreProcessing

        // Pemisahan String
        for ($i = 0; $i <= $lenght; $i++) {
            $res = substr($judulkecil, $i, $kgram);
            if (strlen($res) == $kgram) {
                array_push($perstring, $res);
            } else break;
        }

        // Ascii
        foreach ($perstring as $key => $v) {
            $res = unpack("C*", $v);
            array_push($ascii, $res);
        }

        // Hashing
        foreach ($ascii as $key => $v) { //perulangan array ascii
            $res = 0;
            $dash = 0;
            $pangkat = $kgram;
            foreach ($v as $d) {
                $cons = pow(52, $pangkat); // konstanta (pow = pangkat)
                $pangkat--; //pangkat dimulai dari jumlah kgram-1 dan berkurang hingga pangkat 0
                $dash += ($d * $cons);
                // array_push($this->cek,$d);
                $res = $dash;
            }
            array_push($hash, $res);
        }
        // Fingering
        $fingerprint =  array_unique($hash); //eliminasi array yang sama
        return $fingerprint;
        // dice Coeficient similarity
        // $finger_length = count($fingerprint);

        // foreach($db_finger as $key=>$u){
        //     $same_finger = count(array_intersect($fingerprint,$u)); //
        //     $finger_db_length = count($u);
        //     $similarity = ((2*$same_finger) / ($finger_length+$finger_db_length))*100; // rumus dice coeficient similarity
        //     $result[$key] = $similarity;
        // }
        // return $result;
    }

    private function diceCoefficient($db_finger, $algoritm)
    {
        $finger_length = count($algoritm);
        $result = [];
        foreach ($db_finger as $key => $u) {
            $same_finger = count(array_intersect($algoritm, $u)); //
            $finger_db_length = count($u);
            $similarity = ((2 * $same_finger) / ($finger_length + $finger_db_length)) * 100; // rumus dice coeficient similarity
            $result[$key] = $similarity;
        }
        return $result;
    }
}
