<?php declare(strict_types=1);
/** @author Kirill Znamenskiy <Kirill@Znamenskiy.pw> */
namespace KZ\Assist;

class UrlHelper {

    static $empty_url_parsed = [
        'scheme' => '',   // - e.g. http
        'user' => '',     //
        'pass' => '',     //
        'host' => '',     //
        'port' => '',     //
        'path' => '',     //
        'query' => '',    // - after the question mark ?
        'fragment' => '', // - after the hashmark #
    ];

    /** @return array */
    static public function parse_url($url) {
        if (empty($url)) $ret = [];
        elseif (is_array($url)) $ret = $url;
        else {
            $ret = parse_url($url);
            if (empty($ret)) $ret = [];
        }
        $ret = array_merge(static::$empty_url_parsed,$ret);
        return $ret;
    }

    /** @return string */
    static public function extract_scheme_from_url($url) {
        $parsed_url = static::parse_url($url);
        return (empty($parsed_url['scheme']) ? '' : strval($parsed_url['scheme']));
    }
    static public function extract_schema_from_url($url) { return static::extract_scheme_from_url($url); }

    /** @return string */
    static public function extract_host_from_url($url) {
        $parsed_url = static::parse_url($url);
        return (empty($parsed_url['host']) ? '' : strval($parsed_url['host']));
    }

    /** @return string */
    static public function extract_port_from_url($url) {
        $parsed_url = static::parse_url($url);
        return (empty($parsed_url['port']) ? '' : strval($parsed_url['port']));
    }

    /** @return string */
    static public function extract_path_from_url($url) {
        $parsed_url = static::parse_url($url);
        return (empty($parsed_url['path']) ? '' : strval($parsed_url['path']));
    }

    /** @return string */
    static public function extract_query_from_url($url) {
        $parsed_url = static::parse_url($url);
        return (empty($parsed_url['query']) ? '' : strval($parsed_url['query']));
    }

    /** @return string */
    static public function extract_fragment_from_url($url) {
        $parsed_url = static::parse_url($url);
        return (empty($parsed_url['fragment']) ? '' : strval($parsed_url['fragment']));
    }
    static public function extract_frag_from_url($url) { return static::extract_fragment_from_url($url); }



    /** @return array */
    static public function parse_query(string $query) {
        $ret = [];
        if (!empty($query)) parse_str($query, $ret);
        return $ret;
    }

    static public function extract_params_from_url($url) { return static::parse_query(static::extract_query_from_url($url)); }
    static public function extract_parameters_from_url($url) { return static::parse_query(static::extract_query_from_url($url)); }





    static public function add_parameters_to_url($url, array $pname2value = null) {
        if (empty($pname2value)) return $url;
        if (!is_array($pname2value)) throw new \InvalidArgumentException('(!is_array($params))');

        $parsed_url = static::parse_url($url);
        $url_params = static::extract_params_from_url($parsed_url);

        foreach ($pname2value as $pname => $pvalue) {
            if (!isset($pvalue)) unset($url_params[$pname]);
            else $url_params[$pname] = $pvalue;
        }

        $parsed_url['query'] = static::build_query($url_params);
        $url = static::build_url($parsed_url);
        return $url;
    }
    static public function add_params_to_url($url, array $pname2value = null) { return static::add_parameters_to_url($url,$pname2value ); }

    static public function remove_parameters_from_url($url, array $pnames = null) {
        if (empty($pnames)) return $url;
        if (!is_array($pnames)) throw new \InvalidArgumentException('(!is_array($pnames))');

        $pname2value = array_fill_keys($pnames, null);

        return static::add_params_to_url($url, $pname2value);
    }
    static public function remove_params_from_url($url, array $pnames = null) { return static::remove_parameters_from_url($url, $pnames); }

    /** @return string */
    static public function update_url($url, array $for_update = null) {
        if (empty($for_update)) return $url;
        if (!is_array($for_update)) throw new \InvalidArgumentException('(!is_array($for_update))');

        $parsed_url = static::parse_url($url);
        foreach ($for_update as $kkk => $vvv) {
            if ($kkk === 'params') $kkk = 'query';
            if (($kkk === 'query') AND is_array($vvv)) {

                $url_params = static::extract_params_from_url($parsed_url);
                foreach ($vvv as $qk => $qv) {
                    if (!isset($qv)) unset($url_params[$qk]);
                    else $url_params[$qk] = $qv;
                }
                $parsed_url['query'] = static::build_query($url_params);

                continue;
            }
            $parsed_url[$kkk] = $vvv;
        }

        $url = static::build_url($parsed_url);
        return $url;
    }

    /** @return string */
    static public function build_query(array $data, string $numeric_prefix = null, string $glue = null, bool $use_rawurlencode = false) : string {

        if (empty($data)) return '';
        if (!is_array($data)) throw new \InvalidArgumentException('(!is_array($data))');

        if (!isset($numeric_prefix)) $numeric_prefix = '';
        if (!is_string($numeric_prefix)) throw new \InvalidArgumentException('(!is_string($numeric_prefix))');

        if (!isset($glue)) $glue = '&';
        if (!is_string($glue)) throw new \InvalidArgumentException('(!is_string($glue))');

        if (!is_bool($use_rawurlencode)) throw new \InvalidArgumentException('(!is_array($use_rawurlencode))');


        foreach ($data as $kkk => $vvv) {
            if (!isset($vvv)) unset($data[$kkk]);
        }

        if ($use_rawurlencode) {

            //$ret[] = rawurlencode($k).'='.rawurlencode($v);

            // echo rawurlencode('test test'); // 'test%20text'
            // echo urlencode('test test'); // 'test+text'

            // rawurlencode - URL-кодирование в соответствии с RFC3986, а
            // http_build_query по-умолчанию использует PHP_QUERY_RFC1738, поэтому
            // обязательно нужно явно указать тип кодирования PHP_QUERY_RFC3986
            return http_build_query($data, $numeric_prefix, $glue, PHP_QUERY_RFC3986);

        }
        else {

            // $ret[] = urlencode($k).'='.urlencode($v);

            // Так называемое "обычное" кодирование данных, оно используется и при кодировании POST данных в формате application/x-www-form-urlencoded.
            // Это отличается от RFC3986-кодирования (см. rawurlencode()) тем, что, по историческим соображениям, пробелы кодируются как плюсы (+).
            // Эта функция удобна при кодировании строки для использования в части запроса URL для передачи переменных на следующую страницу.

            // echo rawurlencode('test test'); // 'test%20text'
            // echo urlencode('test test'); // 'test+text'

            // urlencode - URL-кодирование в соответствии с RFC1738, при этом
            // http_build_query по-умолчанию использует его же(PHP_QUERY_RFC1738), но для
            // более явного выделения типа кодирование указываем его явно PHP_QUERY_RFC1738
            return http_build_query($data, $numeric_prefix, $glue, PHP_QUERY_RFC1738);

        }
    }



    /** @return string */
    static public function build_url(array $parsed_url) : string {
        if (!is_array($parsed_url)) throw new \InvalidArgumentException('(!is_array($parsed_url))');

        $url = '';
        if (!empty($parsed_url['host'])) {
            if (!empty($parsed_url['scheme'])) $url .= $parsed_url['scheme'].'://';
            if (!empty($parsed_url['user'])) {
                $url .= $parsed_url['user'];
                if (!empty($parsed_url['pass'])) $url .= ':'.$parsed_url['pass'];
                $url .= '@';
            }
            $url .= $parsed_url['host'];
            if (!empty($parsed_url['port'])) $url .= ':'.$parsed_url['port'];
        }

        if (empty($parsed_url['path']) AND (!empty($parsed_url['query']) OR !empty($parsed_url['fragment']))) $parsed_url['path'] = '/';
        if (!empty($parsed_url['path']) AND ($parsed_url['path'] === '/') AND empty($parsed_url['query']) AND empty($parsed_url['fragment'])) $parsed_url['path'] = '';

        if (!empty($parsed_url['path'])) $url .= $parsed_url['path'];
        if (!empty($parsed_url['query'])) $url .= '?'.$parsed_url['query'];
        if (!empty($parsed_url['fragment'])) $url .= '#'.$parsed_url['fragment'];

        return $url;
    }


}