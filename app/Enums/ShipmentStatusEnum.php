<?php
namespace App\Enums;

enum ShipmentStatusEnum: string
{
    case Kirim = 'Kirim';
    case Diterima = 'Diterima';
    case KirimKembali = 'Kirim kembali';
    case DiterimaCabang = 'Diterima Cabang';
}