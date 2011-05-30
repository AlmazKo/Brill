<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserAgent
 *
 * @author almaz
 */
class UserAgent {
    const DICE = 100;
    static $userAgents = array (
        'mozilla_linux_4.0.1'           => array(50,  'Mozilla/5.0 (X11; Linux i686; rv:2.0.1) Gecko/20100101 Firefox/4.01'),

        'chromium_ubuntu_11.0.696.71'   => array(5,   'Mozilla/5.0 (X11; Linux i686) AppleWebKit/534.24 (KHTML, like Gecko) Ubuntu/10.10 Chromium/11.0.696.71 Chrome/11.0.696.71 Safari/534.24'),
        'chrome_linux_11.0.696.71'      => array(20,  'Mozilla/5.0 (X11; Linux i686) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/11.0.696.71 Safari/534.24'),
        'chrome_win_11.0.696.71'        => array(60,  'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/11.0.696.71 Safari/534.24'),


        'opera_linux_11.11'             => array(5,   'Opera/9.80 (X11; Linux i686; U; en) Presto/2.8.131 Version/11.11'),
        'opera_win_11.11'               => array(31,  'Opera/9.80 (Windows NT 6.1; U; ru) Presto/2.8.131 Version/11.11'),

        'mozilla_win_4.0.1'             => array(100, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:2.0.1) Gecko/20100101 Firefox/4.0.1'),
        'mozilla_windows_3.5.3'         => array(2,   "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.1.3) Gecko/20090824 Firefox/3.5.3"),
    );
    
    /**
     * Получить строку UserAgent
     * @return string
     */
    function getString() {
        $previouslyList = array();
        foreach (self::$userAgents as $userAgent) {
            if (rand(0, self::DICE) <= $userAgent[0]) {
                $previouslyList[] = $userAgent[1];
            }
        }
        shuffle($previouslyList);
        return $previouslyList[array_rand($previouslyList)];
    }
    
    
}
