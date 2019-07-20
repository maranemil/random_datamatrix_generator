<?php

/**
 * Maschinenlesbare Freimachungsvermerke / DV-Freimachung
 * https://www.deutschepost.de/content/dam/dpag/images/D_d/DV-Freimachung/dp_datamatrixcode-dv-freimachung_1-5-1.pdf
 * https://www.deutschepost.de/content/dam/dpag/images/D_d/DV-Freimachung/dp-datamatrixcode-dv-freimachung_1.5.2.pdf
 *https://www.deutschepost.de/content/dam/dpag/images/D_d/DV-Freimachung/dp_datamatrixcode-dv-freimachung_1.4.0.pdf
 *
 * Matrixcode-Inhalt V1.5 / Inhalte des Datamatrixcodes (inkl.Varianten)
 *
 * DMC Inhalt (hexadezimal):
 * 44 45 41 12 21 01 31 7B 93 AC 00 B9 3C 9C 00 94 02 62 A2 01 02
 * 4C 00 AA 48 61 6C 6C 6F 20 57 65 6C 74 21 00 00 00 00 00 00 00 *
 *
 * Post-Unternehmen: .......... ASCII DEA (HEX:444541)
 * Frankierart: ............... 18 (HEX:12)
 * Version Produkte/Preise: ... 36 (HEX:24)
 * Kundennummer: .............. 5111111111 (HEX:0130A55DC7)
 * Frankierwert: .............. 1,50 (HEX:0096)
 * Einlieferungsdatum: ........ 23919 (HEX: 5D6F)
 * Produktschlüssel: .......... 232 (HEX: 00E8)
 * laufende Sendungsnummer: ... 120 (HEX: 000078)
 * Teilnahmenummer: ........... 10 (HEX: 34)
 * Entgeltabrechnungsnummer: .. 10 (HEX: 34)
 * Ankündigung Inhalt Datenelement HEX: 00
 * Kundenindividuelle Informationen: 0000000000000000000000000000000000000000
 */

function genDMECC200($arrShipPack)
{
    echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~" . PHP_EOL;
    // F1 - F3 Post-Unternehmen
    $strMatrixCode = "444541";
    echo "F1-F3--:[Post-Unternehmen] ..........(DEZ) DEA ........... (HEX) 444541" . PHP_EOL;
    // F4 Frankierart
    $Frankierart = dec2hexFormat(DP_FRANKIERART, 2);
    $strMatrixCode .= $Frankierart;
    echo "F4-----:[Frankierart] .............. (DEZ) 18 ............ (HEX) " . $Frankierart . PHP_EOL;
    // F5 Version Produkte/Preise
    $DPVersion = dec2hexFormat(DP_VERSIONPRODUKTE, 2);
    $strMatrixCode .= $DPVersion;
    echo "F5-----:[Version Produkte/Preise] .. (DEZ) " . DP_VERSIONPRODUKTE . " ............ (HEX) " . $DPVersion . PHP_EOL;
    // F6-F10 Kundennummer
    $Kundennummer = dec2hexFormat($arrShipPack["Kundennummer"], 10);
    $strMatrixCode .= $Kundennummer;
    echo "F6-F10-:[Kundennummer] ............. (DEZ) " . $arrShipPack["Kundennummer"] . " .... (HEX) " . $Kundennummer . PHP_EOL;
    // F11-F12 Frankierwert
    $Frankierwert = dec2hexFormat(str_replace(".", "", str_pad(substr($arrShipPack["Frankierwert"], 0, 4), 6, "0", STR_PAD_LEFT)), 4);
    $strMatrixCode .= $Frankierwert;
    echo "F11-F12:[Frankierwert] ............. (DEZ) " . $arrShipPack["Frankierwert"] . " .......... (HEX) " . $Frankierwert . PHP_EOL;
    // F13-F14 Einlieferungsdatum
    # $strDateDP = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . ' +1 day'));
    # $strEinlieferungsdatum = date("zy", strtotime($strDateDP));
    $strEinlieferungsdatum = dec2hexFormat($arrShipPack["Einlieferungsdatum"], 4);
    $strMatrixCode .= $strEinlieferungsdatum;
    echo "F13-F14:[Einlieferungsdatum] ....... (DEZ) " . $arrShipPack["Einlieferungsdatum"] . " ......... (HEX) " . $strEinlieferungsdatum . PHP_EOL;
    // F15-16
    $strProduktschluessel = dec2hexFormat(DP_PRODUKTSCHLUESSEL, 4); //  10264 10262 Warenpost
    $strMatrixCode .= $strProduktschluessel;
    echo "F15-F16:[Produktschlüssel] ......... (DEZ) " . DP_PRODUKTSCHLUESSEL . " ......... (HEX) " . $strProduktschluessel . PHP_EOL;
    // F17 F19 ------------------------------------------------------------------
    $Sendungsnummer = dec2hexFormat($arrShipPack["Sendungsnummer"], 6);
    $strMatrixCode .= $Sendungsnummer;
    echo "F17-F19:[laufende Sendungsnummer] .. (DEZ) " . $arrShipPack["Sendungsnummer"] . " ........... (HEX) " . $Sendungsnummer . PHP_EOL;
    // F20 ------------------------------------------------------------------
    $Teilnahmenummer = dec2hexFormat(DP_TEILNAHMENUMMER, 2);
    $strMatrixCode .= $Teilnahmenummer;
    echo "F20----:[Teilnahmenummer] .......... (DEZ) " . DP_TEILNAHMENUMMER . " ............ (HEX) " . $Teilnahmenummer . PHP_EOL;
    // F22 23 - ------------------------------------------------------------------
    $Einlieferungsbelegnummer = dec2hexFormat($arrShipPack["Einlieferungsbelegnummer"], 4);
    $strMatrixCode .= $Einlieferungsbelegnummer;
    echo "F21-F22:[Einlieferungsbelegnummer] ..(DEZ) " . $arrShipPack["Einlieferungsbelegnummer"] . " ........... (HEX) " . $Einlieferungsbelegnummer . PHP_EOL;
    // F23 - ------------------------------------------------------------------
    $premiumAddress = "00";
    #$strMatrixCode .= $premiumAddress;
    echo "F23----:[Ankündigung PREMIUMADRESS] .(DEZ) 0 ............. (HEX) " . $premiumAddress . PHP_EOL;
    
    // F24 - F42 ------------------------------------------------------------------
    $strMatrixCode = str_pad($strMatrixCode, 84, 0, STR_PAD_RIGHT);
    echo "F24-F42:[Kundenindividuelle Informationen] ............... (HEX) " . strlen("0000000000000000000000000000000000000000") . "x0" . PHP_EOL;
    // Encode
    $strMatrixCodeEnc = hex2bin($strMatrixCode);
    if (DP_ENCODE_UTF8) {
        $strMatrixCodeEnc = utf8_decode(utf8_encode($strMatrixCodeEnc));
    } else {
        $strMatrixCodeEnc = iconv("ISO-8859-1", 'UTF-8', $strMatrixCodeEnc);
        // "Windows-1252","UTF-8","CP437","ISO-8859-1"
        // $strMatrixCodeEnc = mb_convert_encoding( $strMatrixCodeEnc ,'auto','ISO-8859-15');
        // https://github.com/zxing/zxing/issues/365
    }
    echo "--------------------------------------------------" . PHP_EOL;
    echo "[MAX STR LNG HEX]: " . strlen($strMatrixCode) . " [MAX STR LNG BIN]: " . strlen($strMatrixCodeEnc) . PHP_EOL;
    echo "--------------------------------------------------" . PHP_EOL;

    return array(
        "hex" => $strMatrixCode,
        "bin" => $strMatrixCodeEnc,
    );
}

function dec2hexFormat($strIn, $nrChars)
{
    $strHexa = str_pad(strtoupper(dechex($strIn)), $nrChars, "0", STR_PAD_LEFT);
    if (strlen($strHexa) != $nrChars) {
        throw new Exception("Number of Hexa Chars does not match!");
    }
    return $strHexa;
}

function genFrankierID($arrShipPack)
{
    $strStampID = dec2hexFormat(sprintf("%04d", DP_TEILNAHMENUMMER), 2);
    $strStampID .= dec2hexFormat(substr($arrShipPack["Kundennummer"], 0, 8), 7); 
    $strStampID .= dec2hexFormat(str_pad($arrShipPack["Einlieferungsbelegnummer"], 4, 0, STR_PAD_LEFT), 4);
    $strStampID .= dec2hexFormat($arrShipPack["Sendungsnummer"], 6);
    $strCRC4 = strtoupper(crc4cksum((string) $strStampID));
    $strStampID .= $strCRC4;
    $strResultID = substr($strStampID, 0, 2) . ' ' . substr($strStampID, 2, 4) . ' ' . substr($strStampID, 6, 4) . ' ' . substr($strStampID, 10, 2) . ' ' .
    substr($strStampID, 12, 4) . ' ' . substr($strStampID, 16, 4);
    return $strResultID;
}

// https://introcs.cs.princeton.edu/java/61data/CRC32.java.html
// https://github.com/torvalds/linux/blob/master/lib/crc4.c
// https://github.com/perajim/CRC/blob/master/Crc4.java
// https://github.com/lukaville/crc/blob/master/src/main/java/main/crc/CRC.java
// https://stackoverflow.com/questions/46971887/crc-4-implementation-for-c-sharp
// https://stackoverflow.com/questions/54507106/how-to-classify-following-crc4-implementation
// https://stackoverflow.com/questions/39089950/crc4-implementation-in-c
// https://github.com/ghoshsoulib/NETWORK-LAB/blob/d57db43a958a102ee4116ba2aef24ad25e8835e2/arq/encoder.cpp
// https://github.com/jantari/crcc/blob/5e84f88d5f36325859b06402a3eee6f5ee8a7f9e/crcc_stat.c
// https://github.com/jantari/crcc/blob/5e84f88d5f36325859b06402a3eee6f5ee8a7f9e/crcc.c
// https://github.com/qinq-net/cbmroot/blob/fad1853a6080fb92f40290bdbe56188e98bacc8c/fles/cern2017/unpacker/StsXyterRawMessage.cxx
// https://www.rgagnon.com/javadetails/java-0416.html
// https://stackoverflow.com/questions/22742516/crc-calculation-with-arbitrary-values-and-lengths
// http://qaru.site/questions/8259323/crc-calculation-with-arbitrary-values-and-lengths
// http://hk.voidcc.com/question/p-tdzagmgo-bgr.html

function crc4cksum($strInput)
{
    $frame = "";
    $gp = "10011";
    for ($i = 0; $i < strlen($strInput); $i++) {
        $charAsBinaryStr = "";
        $charAsInt = $strInput[$i];
        $charAsBinaryStr .= decbin(ord($charAsInt));
        $fillBy = 8 - strlen($charAsBinaryStr);
        for ($l = 0; $l < $fillBy; $l++) {
            $charAsBinaryStr = "0" . $charAsBinaryStr;
        }
        $frame .= $charAsBinaryStr;
    }
    if (substr($frame, 0, 1) == 0) {
        $frame = substr($frame, 1, strlen($frame));
    }
    $frame .= "0000";
    while (strlen($frame) >= strlen($gp)) {
        $remainder = "";
        $part = substr($frame, 0, strlen($gp));
        $frame = substr($frame, strlen($gp), strlen($frame));
        for ($j = 0; $j < strlen($part); $j++) {
            if ($part[$j] != $gp[$j]) {
                $remainder .= "1";
            } else if (strlen($remainder) > 0) {
                $remainder .= "0";
            }
        }
        $frame = $remainder . $frame;
    }
    return dechex(bindec($frame));
}
