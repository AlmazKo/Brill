<?php
/*
 * source: http://en.wikipedia.org/wiki/Internet_media_type
 * last update list in 16 Nov 2010
 */

/**
 * Класс подавляющего количества Mime-типов
 *
 * @author almaz
 */
class Mimetypes {
    private static $_all = null;
    #private $_categories = array ('application', 'audio', 'image', 'multipart', 'text', 'video', 'x');

    private static $_application = array(
        'application/atom+xml' => "Atom feeds",
        'application/EDI-X12' => "EDI X12 data; Defined in RFC 1767",
        'application/EDIFACT' => "EDI EDIFACT data; Defined in RFC 1767",
        'application/json' => "JavaScript Object Notation JSON; Defined in RFC 4627",
        'application/javascript' => "JavaScript; Defined in RFC 4329 but not accepted in IE 8 or earlier",
        'application/octet-stream' => "Arbitrary binary data.4' Generally speaking this type identifies files that are not associated with a specific application. Contrary to past assumptions by software packages such as Apache this is not a type that should be applied to unknown files. In such a case, a server or application should not indicate a content type, as it may be incorrect, but rather, should omit the type in order to allow the recipient to guess the type.5'",
        'application/ogg' => "Ogg, a multimedia bitstream container format; Defined in RFC 5334",
        'application/pdf' => "Portable Document Format, PDF has been in use for document exchange on the Internet since 1993; Defined in RFC 3778",
        'application/postscript' => "PostScript; Defined in RFC 2046",
        'application/soap+xml' => "SOAP; Defined by RFC 3902",
        'application/xhtml+xml' => "XHTML; Defined by RFC 3236",
        'application/xml-dtd' => "DTD files; Defined by RFC 3023",
        'application/zip' => "ZIP archive files; Registered");

    private static $_audio = array(
        'audio/basic' => "mulaw audio at 8 kHz, 1 channel; Defined in RFC 2046",
        'audio/mp4' => "MP4 audio",
        'audio/mpeg' => "MP3 or other MPEG audio; Defined in RFC 3003",
        'audio/ogg' => "Ogg Vorbis, Speex, Flac and other audio; Defined in RFC 5334",
        'audio/vorbis' => "Vorbis encoded audio; Defined in RFC 5215",
        'audio/x-ms-wma' => "Windows Media Audio; Documented in Microsoft KB 288102",
        'audio/x-ms-wax' => "Windows Media Audio Redirector; Documented in Microsoft help page",
        'audio/vnd.rn-realaudio' => "RealAudio; Documented in RealPlayer Customer Support Answer 2559",
        'audio/vnd.wave' => "WAV audio; Defined in RFC 2361");

    private static $_image = array(
        'image/gif' => "GIF image; Defined in RFC 2045 and RFC 2046",
        'image/jpeg' => "JPEG JFIF image; Defined in RFC 2045 and RFC 2046",
        'image/png' => "Portable Network Graphics; Registered,[7] Defined in RFC 2083",
        'image/svg+xml' => "SVG vector image; Defined in SVG Tiny 1.2 Specification Appendix M",
        'image/tiff' => "Tag Image File Format; Defined in RFC 3302",
        'image/vnd.microsoft.icon' => "ICO image; Registered[8]");

    private static $_message = array('message/http' => "message/http");

    private static $_multipart = array(
        'multipart/mixed' => "MIME E-mail; Defined in RFC 2045 and RFC 2046",
        'multipart/alternative' => "MIME E-mail; Defined in RFC 2045 and RFC 2046",
        'multipart/related' => "MIME E-mail; Defined in RFC 2387 and used by MHTML (HTML mail)",
        'multipart/form-data' => "MIME Webform; Defined in RFC 2388",
        'multipart/signed' => "Defined in RFC 1847",
        'multipart/encrypted' => "Defined in RFC 1847");

    private static $_text = array(
        'text/cmd' => "commands; subtype resident in Gecko browsers like FireFox 3.5",
        'text/css' => "Cascading Style Sheets; Defined in RFC 2318",
        'text/csv' => "Comma-separated values; Defined in RFC 4180",
        'text/html' => "HTML; Defined in RFC 2854",
        'text/javascript' => "JavaScript; Defined in and obsoleted by RFC 4329 in order to discourage its usage in favor of application/javascript. However, text/javascript is allowed in HTML 4 and 5 and, unlike application/javascript, has cross-browser support",
        'text/plain' => "Textual data; Defined in RFC 2046 and RFC 3676",
        'text/xml' => "Extensible Markup Language; Defined in RFC 3023");

    private static $_video = array(
        'video/mpeg' => "MPEG-1 video with multiplexed audio; Defined in RFC 2045 and RFC 2046",
        'video/mp4' => "MP4 video; Defined in RFC 4337",
        'video/ogg' => "Ogg Theora or other video (with audio); Defined in RFC 5334",
        'video/quicktime' => "QuickTime video; Registered[9]",
        'video/webm' => "WebM open media format",
        'video/x-ms-wmv' => "Windows Media Video; Documented in Microsoft KB 288102");

    private static $_vnd = array(
        'application/vnd.oasis.opendocument.text' => "OpenDocument Text",
        'application/vnd.oasis.opendocument.spreadsheet' => "OpenDocument Spreadsheet",
        'application/vnd.oasis.opendocument.presentation' => "OpenDocument Presentation",
        'application/vnd.oasis.opendocument.graphics' => "OpenDocument Graphics",
        'application/vnd.ms-excel' => "Microsoft Excel files",
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => "Microsoft Excel 2007 files",
        'application/vnd.ms-powerpoint' => "Microsoft Powerpoint files",
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => "Microsoft Powerpoint 2007 files",
        'application/msword' => "Microsoft Word files",
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => "Microsoft Word 2007 files",
        'application/vnd.mozilla.xul+xml' => "Mozilla XUL files");

    private static $_x = array(
        'application/x-www-form-urlencoded' => "Form Encoded Data; Documented in HTML 4.01 Specification, Section 17.13.4.1",
        'application/x-dvi' => "device-independent document in DVI format",
        'application/x-latex' => "LaTeX files",
        'application/x-font-ttf' => "TrueType Font No registered MIME type, but this is the most commonly used",
        'application/x-shockwave-flash' => "Adobe Flash files",
        'application/x-stuffit' => "StuffIt archive files",
        'application/x-rar-compressed' => "RAR archive files",
        'application/x-tar' => "Tarball files");

    private static $_xpkcs = array(
        'application/x-pkcs12' => "p12 files",
        'application/x-pkcs12' => "pfx files",
        'application/x-pkcs7-certificates' => "p7b files",
        'application/x-pkcs7-certificates' => "spc files",
        'application/x-pkcs7-certreqresp' => "p7r files",
        'application/x-pkcs7-mime' => "p7c files",
        'application/x-pkcs7-mime' => "p7m files",
        'application/x-pkcs7-signature' => "p7s files");


    /**
     * Проверка на существование mime-type
     * @param string $nameType
     * @return bool
     */
    public static function is($nameType) {
         if (is_null(self::$_all)) {
             self::$_all = array_merge(self::$_application, self::$_audio, self::$_image,
                                       self::$_multipart, self::$_text, self::$_x);
         }
         if (isset(self::$_all[$nameType])) {
             return true;
         } else {
             return false;
         }
    }

    /**
     * Определяет является тип - web-страницей
     * @param string $mimeType
     * @return bool
     */
    public static function isWebPage($mimeType) {
        if (isset(self::$_text[$mimeType]) || in_array($mimeType, array('application/xhtml+xml', 'application/xml-dtd'))) {
            return true;
        } else {
            return false;
        }
    }
}

