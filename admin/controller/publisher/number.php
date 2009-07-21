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

define('STARTPATH', '../../../');

require_once(STARTPATH.'config.php');
require_once(STARTPATH.'costants.php');
require_once(STARTPATH.DATAMODELPATH.'number.php');
require_once(STARTPATH.UTILSPATH.'paginator.php');

session_start();

function index($posts) {
    if (isset($posts['page'])) $page = $posts['page'];
    else $page = 1;

    $out = array();

    $numb = new Number();
    $out['numb'] = $numb;

    $numbs = Number::findAllOrderedByIndexNumber();
    $out['numbs'] = Paginator::paginate($numbs, $page);
    $out['page_numbers'] = Number::getPageNumbers();
    $out['pageSelected'] = $page;
    
    return $out;
}


function find($string) {
    $page = 1;
    $out = array();

    $numb = new Number();
    $out['numb'] = $numb;

    $numbs = Number::findInAllTextFields($string);
    $out['numbers'] = $numbs;
    $out['page_numbers'] = Number::getPageNumbers();
    $out['pageSelected'] = $page;

    if (count($numbs)==0) { $out['warning'] = 'No numbers corresponding to search criteria';  }
    return $out;
}

function edit($id) {
    $out = array();

    $numb = Number::findById($id);
    $out['numb'] = $numb;

    $numbs = Number::findAllOrderedByIndexNumber();
    $out['numbs'] = $numbs;
    $out['page_numbers'] = Number::getPageNumbers();

    return $out;
}

function delete($id) {
    $out = array();

    $numb = Number::findById($id);
    $numb->delete();
    $numb = new Number();
    $out['numb'] = $numb;

    $numbs = Number::findAllOrderedByIndexNumber();
    $out['numbs'] = $numbs;
    $out['page_numbers'] = Number::getPageNumbers();

    return $out;
}

function deleteimg($id) {
    $out = array();

    $numb = Number::findById($id);
    $numb->deleteImg();
    $out['numb'] = $numb;

    $numbs = Number::findAllOrderedByIndexNumber();
    $out['numbs'] = $numbs;
    $out['page_numbers'] = Number::getPageNumbers();

    return $out;
}

function up($id) {
    $out = array();

    $numb1 = Number::findById($id);
    $indexnumber = $numb1->getIndexNumber();
    $numb2 = Number::findUpIndexNumber($indexnumber);

    if ($numb2) {
        $numb1->setIndexNumber($numb2->getIndexNumber());
        $numb2->setIndexNumber($indexnumber);
        $numb1->save();
        $numb2->save();
    }

    $numb = new Number();
    $out['numb'] = $numb;

    $numbs = Number::findAllOrderedByIndexNumber();
    $out['numbs'] = $numbs;
    $out['page_numbers'] = Number::getPageNumbers();
    
    return $out;
}

function down($id) {
    $out = array();

    $numb1 = Number::findById($id);
    $indexnumber = $numb1->getIndexNumber();
    $numb2 = Number::findDownIndexNumber($indexnumber);

    if ($numb2) {
        $numb1->setIndexNumber($numb2->getIndexNumber());
        $numb2->setIndexNumber($indexnumber);
        $numb1->save();
        $numb2->save();
    }
    
    $numb = new Number();
    $out['numb'] = $numb;

    $numbs = Number::findAllOrderedByIndexNumber();
    $out['numbs'] = $numbs;
    $out['page_numbers'] = Number::getPageNumbers();
    
    return $out;
}

function save($toSave, $files) {
    $out = array();

    if (!isset($toSave['Published'])) { $toSave['Published'] = 0; }
    if (!isset($toSave['commentsallowed'])) { $toSave['commentsallowed'] = 0; }
    if (!isset($toSave['imagefilename'])) { $toSave['imagefilename'] = ''; }

    $numb = new Number(
        $toSave['id'],
        $toSave['indexnumber'],
        $toSave['Published'],
        $toSave['Title'],
        $toSave['SubTitle'],
        $toSave['Summary'],
        $toSave['commentsallowed'],
        $toSave['imagefilename'],
        $toSave['ImageDescription'],
        $toSave['created'],
        $toSave['updated']);
    $numb->save();

    if (isset($files['Image']) && $files['Image']['size'] > 0) {
        $numb->deleteImg();
        $numb->saveImg($files['Image']);
    }
    $out['numb'] = $numb;

    $numbs = Number::findAllOrderedByIndexNumber();
    $out['numbs'] = $numbs;
    $out['page_numbers'] = Number::getPageNumbers();
    
    return $out;
}


if (isset($_SESSION['user'])) {
    if (!isset($_GET['action'])) { $out = index($_POST); }
    else {
        switch ($_GET['action']) {
            case  'index':             $out = index($_POST); break;
            case  'save':              $out = save($_POST, $_FILES); break;
            case  'edit':              $out = edit($_GET['id']); break;
            case  'delete':            $out = delete($_GET['id']); break;
            case  'up':                $out = up($_GET['id']); break;
            case  'down':              $out = down($_GET['id']); break;
            case  'deleteimg':         $out = deleteimg($_GET['id']); break;
            case  'find':              $out = find($_POST['string']); break;
        }
    }
    } else {
         header("Location: ../../loginError.php");
}

$numbs = $out['numbs'];
$numb = $out['numb'];
$page_numbers = $out['page_numbers'];
if (isset($_GET['action'])) $lastAction = $_GET['action'];
else $lastAction = 'index';
$pageSelected = $out['pageSelected'];

if (isset($out['info'])) { $info = $out['info']; }
if (isset($out['warning'])) { $warning = $out['warning']; }
if (isset($out['question'])) { $question = $out['question']; }
if (isset($out['error'])) { $error = $out['error']; }

include('../../view/publisher/numbers.php');

?>
