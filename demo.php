<?php 
require __DIR__ . "/src/bmkg.php";

$cuaca = new BMKG;
// Mendapatkan data dari setiap masing - masing daerah
echo json_encode($cuaca->GetListDaerah());

// Mendapatkan info cuaca dari daerah yang ada
echo json_encode($cuaca->GetInfoCuaca(07, "DKI Jakarta"));