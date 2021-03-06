<?php

/**
 * Copyright (c) 2015-present Ganbaro Digital Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   Filesystem/ValueBuilders
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-file-system
 */

namespace GanbaroDigital\Filesystem\ValueBuilders;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

use GanbaroDigital\Filesystem\Checks\IsFolder;
use GanbaroDigital\Filesystem\DataTypes\FilesystemPathData;
use GanbaroDigital\Filesystem\Iterators\SplFolderIterator;

class MatchingFilteredFilenames
{
    const PATTERN_MATCH_ALL = '.+';

    /**
     * return a list of matching files and / or folders inside a given folder
     *
     * @param  FilesystemPathData $fsData
     *         the folder to look inside
     * @param  string $pattern
     *         the regex to match
     * @param  string $matcher
     *         class name of the matcher to apply to the RegexIterator results
     * @return array<string>
     *         a list of the matching files / folders found
     *         will be empty if no matches found
     */
    public static function fromFilesystemPathData(FilesystemPathData $fsData, $pattern, $matcher)
    {
        // make sure we have a folder
        if (!IsFolder::checkFilesystemPathData($fsData)) {
            return [];
        }

        // at this point, we are happy that we have a folder
        //
        // let's find out what's in it
        $regIter = SplFolderIterator::fromFilesystemPathData($fsData, $pattern);

        // what happened?
        $filenames = iterator_to_array(call_user_func_array([$matcher, 'fromRegexIterator'], [$regIter]));

        // let's get the list into some semblance of order
        sort($filenames);

        // all done
        return $filenames;
    }
}