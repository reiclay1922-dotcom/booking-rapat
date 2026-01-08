<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function format_durasi($menit) {
  $menit = (int)$menit;
  if ($menit <= 0) return '-';

  $jam = intdiv($menit, 60);
  $sisa = $menit % 60;

  if ($jam > 0 && $sisa > 0) return $jam.' jam '.$sisa.' menit';
  if ($jam > 0) return $jam.' jam';
  return $sisa.' menit';
}
