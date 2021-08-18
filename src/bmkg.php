<?php 
/*
Library PHP untuk scraping data cuaca
dari situs BMKG. Check update library
ini di https://github.com/IhsanDevs.
*/
require __DIR__ . "/simple_html_dom.php";
class BMKG {
    public function GetListDaerah(){
        $dom = new simple_html_dom();
        $dom = file_get_html("https://www.bmkg.go.id/cuaca/prakiraan-cuaca-indonesia.bmkg");
        $ListDaerah = $dom->find("div.list-cuaca-provinsi", 0);
        $ListDaerah = $ListDaerah->find("a");
        $DataListDaerah = [];
        
        for ($key=0; $key < count($ListDaerah); $key++) {
            $NewDaerah = explode("&", $ListDaerah[$key]->getAttribute("href"));
            $DataListDaerah += [
                $key => [
                    "id" => str_replace("?Prov=", "", $NewDaerah[0]),
                    "provinsi" => str_replace("NamaProv=", "", $NewDaerah[1])
                ]
            ];
        }
        return $DataListDaerah;
    }
    public function GetInfoCuaca($IdDaerah, $NamaDaerah){
        $html = new simple_html_dom();
        $html = file_get_html("https://www.bmkg.go.id/cuaca/prakiraan-cuaca-indonesia.bmkg?Prov={$IdDaerah}&NamaProv=" . str_replace(" ", "%20", $NamaDaerah));
        $table_one = $html->find('tbody', 0);
        $table_one = $table_one->find('tr');
        $thead = $html->find('thead tr');
        $number = 0;
        $arrayList = [];
        $statusWaktu = ($thead[1])->find('th', 0)->plaintext;
        for ($key=0; $key < count($table_one); $key++) { 
          switch ($statusWaktu) {
                case 'Dini Hari':
                    while ($number < count($table_one)) {
                        $arrayList += [
                            $number => [
                                "daerah" => ($table_one[$number])->find('td', 0)->plaintext,
                                "pagi" => [
                                    "icon" => "-",
                                    "status" => "-"
                                ],
                                "siang" => [
                                    "icon" => "-",
                                    "status" => "-"
                                ],
                                "malam" => [
                                    "icon" => "-",
                                    "status" => "-"
                                ],
                                "dini_hari" => [
                                    "icon" => ($table_one[$number])->find('img', 0)->getAttribute("src"),
                                    "status" => ($table_one[$number])->find('span.tekscuaca', 0)->plaintext
                                ],
                                "suhu" => ($table_one[$number])->find('td', 2)->plaintext."°C",
                                "kelembapan" => ($table_one[$number])->find('td', 3)->plaintext."%"
                            ]
                        ];
                        $number++;
                    }
                  break;
                case 'Pagi':
                    while ($number < count($table_one)) {
                        $arrayList += [
                            $number => [
                                "daerah" => ($table_one[$number])->find('td', 0)->plaintext,
                                "pagi" => [
                                    "icon" => ($table_one[$number])->find('img', 0)->getAttribute("src"),
                                    "status" => ($table_one[$number])->find('span.tekscuaca', 0)->plaintext
                                ],
                                "siang" => [
                                    "icon" => ($table_one[$number])->find('img', 1)->getAttribute("src"),
                                    "status" => ($table_one[$number])->find('span.tekscuaca', 1)->plaintext
                                ],
                                "malam" => [
                                    "icon" => ($table_one[$number])->find('img', 2)->getAttribute("src"),
                                    "status" => ($table_one[$number])->find('span.tekscuaca', 2)->plaintext
                                ],
                                "dini_hari" => [
                                    "icon" => ($table_one[$number])->find('img', 3)->getAttribute("src"),
                                    "status" => ($table_one[$number])->find('span.tekscuaca', 3)->plaintext
                                ],
                                "suhu" => ($table_one[$number])->find('td', 5)->plaintext." °C",
                                "kelembapan" => ($table_one[$number])->find('td', 6)->plaintext."%"
                            ]
                        ];
                        $number++;
                    }
                  break;
                case 'Siang':
                    while ($number < count($table_one)) {
                        $arrayList += [
                            $number => [
                                "daerah" => ($table_one[$number])->find('td', 0)->plaintext,
                                "pagi" => [
                                    "icon" => "-",
                                    "status" => "-"
                                ],
                                "siang" => [
                                    "icon" => ($table_one[$number])->find('img', 0)->getAttribute("src"),
                                    "status" => ($table_one[$number])->find('span.tekscuaca', 0)->plaintext
                                ],
                                "malam" =>[
                                    "icon" => ($table_one[$number])->find('img', 1)->getAttribute("src"),
                                    "status" => ($table_one[$number])->find('span.tekscuaca', 1)->plaintext
                                ],
                                "dini_hari" => [
                                    "icon" => ($table_one[$number])->find('img', 2)->getAttribute("src"),
                                    "status" => ($table_one[$number])->find('span.tekscuaca', 2)->plaintext
                                ],
                                "suhu" => ($table_one[$number])->find('td', 4)->plaintext." °C",
                                "kelembapan" => ($table_one[$number])->find('td', 5)->plaintext."%"
                            ]
                        ];
                        $number++;
                    }
                    break;
                case 'Malam':
                    while ($number < count($table_one)) {
                        $arrayList += [
                            $number => [
                                "daerah" => ($table_one[$number])->find('td', 0)->plaintext,
                                "pagi" => "-",
                                "siang" => '-',
                                "malam" => [
                                    "icon" => ($table_one[$number])->find('img', 0)->getAttribute("src"),
                                    "status" => ($table_one[$number])->find('span.tekscuaca', 0)->plaintext
                                ],
                                "dini_hari" => [
                                    "icon" => ($table_one[$number])->find('img', 1)->getAttribute("src"),
                                    "status" => ($table_one[$number])->find('span.tekscuaca', 1)->plaintext
                                ],
                                "suhu" => ($table_one[$number])->find('td', 3)->plaintext." °C",
                                "kelembapan" => ($table_one[$number])->find('td', 4)->plaintext."%"
                            ]
                        ];
                        $number++;
                    }
                    break;
                default:
                  return false;
                  break;
          }
          return $arrayList;
        }
    }
}