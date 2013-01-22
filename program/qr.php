<?php
namespace UrlShortener;

require  __DIR__.'/3rdparty/phpqrcode/phpqrcode.php';


function output_qr_png($url)
{
	$ecclvl = QR_ECLEVEL_L;
	switch (QR_ECC_LVL)
	{
		case 'L': $ecclvl = QR_ECLEVEL_L; break;
		case 'M': $ecclvl = QR_ECLEVEL_M; break;
		case 'Q': $ecclvl = QR_ECLEVEL_Q; break;
		case 'H': $ecclvl = QR_ECLEVEL_H; break;
	}
	\QRCode::png($url, false, $ecclvl, 6, 1);
}
