<?php 
require __DIR__ . "/src/bmkg.php";

$cuaca = new BMKG;
// Mendapatkan list Daerah beserta ID Daerah.
echo json_encode($cuaca->GetListDaerah());

// Mendapatkan Info Cuaca
echo json_encode($cuaca->GetInfoCuaca(28, "Sulawesi Selatan"));