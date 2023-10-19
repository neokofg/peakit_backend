<?php

namespace App\Services;

use App\Models\Ticket;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Throwable;
use Intervention\Image\ImageManagerStatic as Image;

class TicketService {

    public function get_ticket($u): mixed
    {
        try {
            $ticket = Ticket::where("user_id", '=', $u->id)->where("is_payed", "=", true)->first();
            if(!$ticket){
                return false;
            } else return $ticket;
        } catch (Throwable $e) {
            return false;
        }
    }

    public function get_ticket_qr($u): mixed
    {
        try {
            $ticket = Ticket::where("user_id", '=', $u->id)->where("is_payed", "=", true)->first();
            if(!$ticket){
                return false;
            } else {
                $bgColor = [255, 255, 255];
                $fgColor = [0, 0, 0];
                $this->options = new QROptions([
                    'version' => 7,
                    'eccLevel' => EccLevel::H,
                    'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                    'imageBase64' => false,
                    'bgColor'      => $bgColor,
                    'scale' => 6,
                    'imageTransparent' => false,
                    'drawCircularModules' => false,
                    'drawLightModules'    => false,
                    'connectPaths' => true,
                    'keepAsSquare' => [QRMatrix::M_FINDER, QRMatrix::M_FINDER_DOT],
                    'moduleValues'        => [
                        QRMatrix::M_FINDER_DARK    => $fgColor,
                        QRMatrix::M_FINDER_DOT     => $fgColor,
                        QRMatrix::M_FINDER         => $fgColor,
                        QRMatrix::M_DATA_DARK      => $fgColor,
                        QRMatrix::M_DATA           => $fgColor,
                        QRMatrix::M_QUIETZONE      => $fgColor,
                        QRMatrix::M_SEPARATOR      => $fgColor,
                        QRMatrix::M_DARKMODULE     => $fgColor,
                        QRMatrix::M_VERSION_DARK   => $fgColor,
                        QRMatrix::M_VERSION        => $fgColor,
                        QRMatrix::M_TIMING_DARK    => $fgColor,
                        QRMatrix::M_TIMING         => $fgColor,
                        QRMatrix::M_ALIGNMENT_DARK => $fgColor,
                        QRMatrix::M_ALIGNMENT      => $fgColor,
                        QRMatrix::M_FORMAT_DARK    => $fgColor,
                        QRMatrix::M_FORMAT         => $fgColor,
                    ],
                ]);
                $qrcodeGenerator = new QRCode($this->options);
                $qrcodeImage = $qrcodeGenerator->render($ticket->id);
                $qrImage = Image::make($qrcodeImage);
                $base64Image = $qrImage->encode('data-url')->encoded;
                $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);

                return $base64Image;
            };
        } catch (Throwable $e) {
            return dd($e);
        }
    }

    public function get_ticket_by_id($r): mixed
    {
        try {
            $ticket = Ticket::where("id", '=', $r['id'])->first();
            if(!$ticket){
                return false;
            } else return $ticket;
        } catch (Throwable $e) {
            return false;
        }
    }

}
