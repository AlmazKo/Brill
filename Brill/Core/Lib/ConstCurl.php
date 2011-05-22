<?php
/**
 * Константы настроек курла
 *
 * @author almazKo
 */
class ConstCurl {
    //"стратегически" важные заголовки
    const
        HEADER_CONTENT_TYPE = 'content-type',
        HEADER_SET_COOKIE = 'set-cookie',
        HEADER_LOCATION = 'location';

    // способы отправки формы
    const
        FORM_ENCTYPE_APP = 'application/x-www-form-urlencoded',
        FORM_ENCTYPE_MULTIPART = 'multipart/form-data';
    
    public static $opts = array (
        CURLOPT_AUTOREFERER => 'CURLOPT_AUTOREFERER',
        CURLOPT_BINARYTRANSFER => 'CURLOPT_BINARYTRANSFER',
        CURLOPT_BUFFERSIZE => 'CURLOPT_BUFFERSIZE',
        CURLOPT_CAINFO => 'CURLOPT_CAINFO',
        CURLOPT_CAPATH => 'CURLOPT_CAPATH',
        CURLOPT_CLOSEPOLICY => 'CURLOPT_CLOSEPOLICY',
        CURLOPT_CONNECTTIMEOUT => 'CURLOPT_CONNECTTIMEOUT',
#       CURLOPT_CONNECTTIMEOUT_MS => 'CURLOPT_CONNECTTIMEOUT_MS',
        /*
         * Содержимое заголовка "Cookie: ", который будет отправлен с HTTP запросом.
         */
        CURLOPT_COOKIE => 'CURLOPT_COOKIE',
        /*
         * Имя файла, содержащего данные cookie. Данные могут быть либо в формате Netscape, либо просто HTTP-заголовки.
         */
        CURLOPT_COOKIEFILE => 'CURLOPT_COOKIEFILE',
        CURLOPT_COOKIEJAR => 'CURLOPT_COOKIEJAR',
        /*
         * При установке этого параметра в true CURL игнорирует все сессионные cookie (хранящиеся в оперативной памяти и лишь до момента закрытия браузера)
         */
        CURLOPT_COOKIESESSION => 'CURLOPT_COOKIESESSION',
        CURLOPT_CRLF => 'CURLOPT_CRLF',
        CURLOPT_CUSTOMREQUEST => 'CURLOPT_CUSTOMREQUEST',
        CURLOPT_DNS_CACHE_TIMEOUT => 'CURLOPT_DNS_CACHE_TIMEOUT',
        CURLOPT_DNS_USE_GLOBAL_CACHE => 'CURLOPT_DNS_USE_GLOBAL_CACHE',
        CURLOPT_EGDSOCKET => 'CURLOPT_EGDSOCKET',
        CURLOPT_ENCODING => 'CURLOPT_ENCODING',
        CURLOPT_FAILONERROR => 'CURLOPT_FAILONERROR',
        CURLOPT_FILE => 'CURLOPT_FILE',
        CURLOPT_FILETIME => 'CURLOPT_FILETIME',
        /*
         * При установке этого параметра в ненулевое значение, при получении HTTP заголовка "Location: "
         * будет происходить перенаправление на указанный этим заголовком URL
         * (это действие выполняется рекурсивно, для каждого полученного заголовка "Location:").
         */
        CURLOPT_FOLLOWLOCATION => 'CURLOPT_FOLLOWLOCATION',
        CURLOPT_FORBID_REUSE => 'CURLOPT_FORBID_REUSE',
        CURLOPT_FRESH_CONNECT => 'CURLOPT_FRESH_CONNECT',

        CURLOPT_FTP_CREATE_MISSING_DIRS => 'CURLOPT_FTP_CREATE_MISSING_DIRS',
#       CURLOPT_FTP_FILEMETHOD => 'CURLOPT_FTP_FILEMETHOD',
#        CURLOPT_FTP_SKIP_PASV_IP => 'CURLOPT_FTP_SKIP_PASV_IP',
        CURLOPT_FTP_SSL => 'CURLOPT_FTP_SSL',
        CURLOPT_FTP_USE_EPRT => 'CURLOPT_FTP_USE_EPRT',
        CURLOPT_FTP_USE_EPSV => 'CURLOPT_FTP_USE_EPSV',
        CURLOPT_FTPAPPEND => 'CURLOPT_FTPAPPEND',
        CURLOPT_FTPLISTONLY => 'CURLOPT_FTPLISTONLY',
        CURLOPT_FTPPORT => 'CURLOPT_FTPPORT',
        CURLOPT_FTPSSLAUTH => 'CURLOPT_FTPSSLAUTH',
        /*
         * При установке этого параметра в ненулевое значение результат будет включать полученные заголовки
         */
        CURLOPT_HEADER => 'CURLOPT_HEADER',
        /*
         * Массив с HTTP заголовками
         */
        CURLOPT_HEADERFUNCTION => 'CURLOPT_HEADERFUNCTION',
        CURLOPT_HTTP_VERSION => 'CURLOPT_HTTP_VERSION',
        CURLOPT_HTTP200ALIASES => 'CURLOPT_HTTP200ALIASES',
        CURLOPT_HTTPAUTH => 'CURLOPT_HTTPAUTH',
        CURLOPT_HTTPGET => 'CURLOPT_HTTPGET',
        CURLOPT_HTTPHEADER => 'CURLOPT_HTTPHEADER',
        /*
         * При установке этого параметра в true данные будут передаваться через прокси-сервер 
         */
        CURLOPT_HTTPPROXYTUNNEL => 'CURLOPT_HTTPPROXYTUNNEL',
        CURLOPT_INFILE => 'CURLOPT_INFILE',
        CURLOPT_INFILESIZE => 'CURLOPT_INFILESIZE',
        /*
         * Имя используемого сетевого интерфейса. Может быть именем интерфейса, IP адресом или именем хоста.
         */
        CURLOPT_INTERFACE => 'CURLOPT_INTERFACE',
#        CURLOPT_IPRESOLVE => 'CURLOPT_IPRESOLVE',
        CURLOPT_KRB4LEVEL => 'CURLOPT_KRB4LEVEL',
        /*
         * Задает минимальную скорость передачи в байтах в секунду.
         * Если в течении времени, заданного параметром CURLOPT_LOW_SPEED_TIME,
         * скорость передачи будет меньше этого значения, операция будет прервана.
         */
        CURLOPT_LOW_SPEED_LIMIT => 'CURLOPT_LOW_SPEED_LIMIT',
        /*
         * Задает время в секундах, в течение которого скорость передачи должна быть ниже,
         * чем CURLOPT_LOW_SPEED_LIMIT, чтобы операция была признана слишком медленной и прервана.
         */
        CURLOPT_LOW_SPEED_TIME => 'CURLOPT_LOW_SPEED_TIME',
        CURLOPT_MAXCONNECTS => 'CURLOPT_MAXCONNECTS',
        CURLOPT_MAXREDIRS => 'CURLOPT_MAXREDIRS',
        /*
         * При установке этого параметра в ненулевое значение, все сообщения cURL будут подавляться.
         */
#       CURLOPT_MUTE => 'CURLOPT_MUTE',
        CURLOPT_NETRC => 'CURLOPT_NETRC',
        CURLOPT_NOBODY => 'CURLOPT_NOBODY',
        CURLOPT_NOPROGRESS => 'CURLOPT_NOPROGRESS',
        CURLOPT_NOSIGNAL => 'CURLOPT_NOSIGNAL',
        CURLOPT_PORT => 'CURLOPT_PORT',
        /*
         * При установке этого параметра в ненулевое значение будет отправлен HTTP запрос методом POST
         * типа application/x-www-form-urlencoded, используемый браузерами при отправке форм.
         */
        CURLOPT_POST => 'CURLOPT_POST',
        /*
         * Строка, содержащая данные для HTTP POST запроса.
         */
        CURLOPT_POSTFIELDS => 'CURLOPT_POSTFIELDS',
        CURLOPT_POSTQUOTE => 'CURLOPT_POSTQUOTE',
        CURLOPT_PRIVATE => 'CURLOPT_PRIVATE',
#       CURLOPT_PROGRESSFUNCTION => 'CURLOPT_PROGRESSFUNCTION',
        /*
         * Имя HTTP прокси, через который будут направляться запросы.
         */
        CURLOPT_PROXY => 'CURLOPT_PROXY',
        /*
         * Метод HTTP-авторизации для использования при соединении с прокси. 
         */
        CURLOPT_PROXYAUTH => 'CURLOPT_PROXYAUTH',
        /*
         * Номер порта для соединения с прокси-сервером; используется совместно с CURLOPT_PROXY
         */
        CURLOPT_PROXYPORT => 'CURLOPT_PROXYPORT',
        /*
         * CURLPROXY_HTTP по умолчанию или CURLPROXY_SOCKS5
         */
        CURLOPT_PROXYTYPE => 'CURLOPT_PROXYTYPE',
        /*
         * Стока с именем пользователя и паролем к HTTP прокси-серверу в виде [username]:[password].
         */
        CURLOPT_PROXYUSERPWD => 'CURLOPT_PROXYUSERPWD',
        
        CURLOPT_PUT => 'CURLOPT_PUT',
        CURLOPT_QUOTE => 'CURLOPT_QUOTE',
        CURLOPT_RANDOM_FILE => 'CURLOPT_RANDOM_FILE',
        /*
         * Задает участок файла, который нужно загрузить, в формате "X-Y" , причем X или Y могут быть опущены.
         * Протокол HTTP также поддерживает передачу нескольких фрагментов файла, это задается в виде "X-Y,N-M".
         */
        CURLOPT_RANGE => 'CURLOPT_RANGE',
        CURLOPT_READDATA => 'CURLOPT_READDATA',
        CURLOPT_READFUNCTION => 'CURLOPT_READFUNCTION',
        /*
         * Задает значение HTTP заголовка "Referer: ".
         */
        CURLOPT_REFERER => 'CURLOPT_REFERER',
        /*
         * Задает позицию в файле в байтах, с которой начнется передача данных.
         */
        CURLOPT_RESUME_FROM => 'CURLOPT_RESUME_FROM',
        /*
         * При установке этого параметра в false значение CURL будет возвращать результат, а не выводить его.
         */
        CURLOPT_RETURNTRANSFER => 'CURLOPT_RETURNTRANSFER',
        CURLOPT_SSL_CIPHER_LIST => 'CURLOPT_SSL_CIPHER_LIST',
        CURLOPT_SSL_VERIFYHOST => 'CURLOPT_SSL_VERIFYHOST',
        CURLOPT_SSL_VERIFYPEER => 'CURLOPT_SSL_VERIFYPEER',
        CURLOPT_SSLCERT => 'CURLOPT_SSLCERT',
        CURLOPT_SSLCERTPASSWD => 'CURLOPT_SSLCERTPASSWD',
        CURLOPT_SSLCERTTYPE => 'CURLOPT_SSLCERTTYPE',
        CURLOPT_SSLENGINE => 'CURLOPT_SSLENGINE',
        CURLOPT_SSLENGINE_DEFAULT => 'CURLOPT_SSLENGINE_DEFAULT',
        CURLOPT_SSLKEY => 'CURLOPT_SSLKEY',
        CURLOPT_SSLKEYPASSWD => 'CURLOPT_SSLKEYPASSWD',
        CURLOPT_SSLKEYTYPE => 'CURLOPT_SSLKEYTYPE',
        CURLOPT_SSLVERSION => 'CURLOPT_SSLVERSION',
        CURLOPT_STDERR => 'CURLOPT_STDERR',
        CURLOPT_TCP_NODELAY => 'CURLOPT_TCP_NODELAY',
        CURLOPT_TIMECONDITION => 'CURLOPT_TIMECONDITION',
        /*
         * Задает масимальное время выполнения операции в секундах.
         */
        CURLOPT_TIMEOUT => 'CURLOPT_TIMEOUT',
#       CURLOPT_TIMEOUT_MS => 'CURLOPT_TIMEOUT_MS',
        CURLOPT_TIMEVALUE => 'CURLOPT_TIMEVALUE',
        CURLOPT_TRANSFERTEXT => 'CURLOPT_TRANSFERTEXT',
        CURLOPT_UNRESTRICTED_AUTH => 'CURLOPT_UNRESTRICTED_AUTH',
        CURLOPT_UPLOAD => 'CURLOPT_UPLOAD',
        /*
         * URL, с которым будет производиться операция.
         */
        CURLOPT_URL => 'CURLOPT_URL',
        /*
         * Задает значение HTTP заголовка "User-Agent: ".
         */
        CURLOPT_USERAGENT => 'CURLOPT_USERAGENT',
        /*
         * Стока с именем пользователя и паролем в виде [username]:[password].
         */
        CURLOPT_USERPWD => 'CURLOPT_USERPWD',
        CURLOPT_VERBOSE => 'CURLOPT_VERBOSE',
        CURLOPT_WRITEFUNCTION => 'CURLOPT_WRITEFUNCTION',
        CURLOPT_WRITEHEADER => 'CURLOPT_WRITEHEADER',
        );
}