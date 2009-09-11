<?php

/*
    Copyright (C) 2009  Fabio Mattei

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once('rindex.php');
require_once('comments.php');
require_once('page.php');
require_once('article.php');
require_once('number.php');
require_once('people.php');
require_once('articlesperson.php');

class RouterFactory {

    public function createRouter($request) {
        $out = null;

        if ($request['Router'] == "index") $out = new IndexRouter($request);

        if ($request['Router'] == "comments") $out = new CommentsRouter($request);

        if ($request['Router'] == "page") $out = new PagesRouter($request);

        if ($request['Router'] == "article") $out = new ArticlesRouter($request);

        if ($request['Router'] == "number") $out = new NumberRouter($request);

        if ($request['Router'] == "people") $out = new PeopleRouter($request);

        if ($request['Router'] == "articlesperson") $out = new ArticlesPersonRouter($request);

        return $out;
    }
    
}

?>
