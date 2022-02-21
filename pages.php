<?php

$curPage = isset($_GET['page']) ? $_GET['page'] : "default";
if (!ctype_alnum($curPage))
    $curPage = "";

$curPage = strtolower($curPage);

function buildMenuLink($pagename)
{
    global $curPage;

    if (!empty($pagename))
        $href = "?page=$pagename";
    else
        $href = "?";
    $class = "list-group-item";

    if ($pagename == $curPage || empty($pagename) && $curPage == "default")
        $class .= " active";

    return "href=\"$href\" class=\"$class\"";
}

function includeCurrentPage()
{
    global $curPage;

    if (empty($curPage))
        $curPage = "default";

    if (file_exists("pages/$curPage.php"))
        require "pages/$curPage.php";
}
