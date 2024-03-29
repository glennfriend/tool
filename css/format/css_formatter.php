<?php
/**
 The MIT License

 Copyright (c) 2007 <Tsung-Hao>

 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:

 The above copyright notice and this permission notice shall be included in
 all copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 THE SOFTWARE.
 *
 * css formatter
 * ex:
 *   selector {xxxx;xxxx;}
 *
 * convert to: 
 *   selector {
 *       xxxx;
 *       xxxx;
 *   }
 *
 * @author: Tsung <tsunghao@gmail.com> http://plog.longwin.com.tw
 * @date: 2007/12/03
 * @version: v1.00
 * @desc: http://plog.longwin.com.tw/programming/2007/12/03/php_css_inline_formatter_2007
 */
function css_formatter( $content ) {

    $format = '';

    /* "}" must end ln line. */

    $buffers = explode("\n",$content);
    foreach( $buffers as $buffer) {
        if (preg_match('/(.*)?{(.*)}/', $buffer, $match)) {
            $sp = explode(';', trim($match[2]));

            sort($sp);

            // remove empty array
            $ie_hack = '';
            for ($i = 0, $c = count($sp); $i < $c; $i++) {
                $sp[$i] = trim($sp[$i]);
                if (empty($sp[$i])) {
                    unset($sp[$i]);
                }

                if ( isset($sp[$i]) && preg_match('/^[_\*].+/', $sp[$i])) {
                    // sort default a/b/c => * => _
                    $ie_hack[] = $sp[$i];
                    unset($sp[$i]);
                }
            }

            if (is_array($ie_hack)) {
                $sp = array_merge($sp, $ie_hack);
            }

            $sp[] = '}';

            $format .= $match[1] ."{\n    ". implode(";\n    ", $sp) . "\n";
        } else {
            // option: "/* ... */"  =>  "\n/* ... */"
            //if (preg_match('#/\*.*?\*/#', $buffer)) {
            //    $buffer = "\n" . $buffer;
            //}

            // option: remove /* ... */
            //if (preg_match('#/\*.*?\*/#', $buffer)) {
            //    continue;
            //}

            $format .= $buffer;
        }
    }

    $format = str_replace('    }', "}", $format);

    return $format;

}
?>