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

    if (!empty($pagename) && !file_exists("pages/$pagename.php"))
        $class .= " nonexistent";

    return "href=\"$href\" class=\"$class\"";
}

function includeCurrentPage()
{
    global $curPage;

    if (empty($curPage))
        $curPage = "default";

    if (file_exists("pages/$curPage.php"))
        require "pages/$curPage.php";
    else
        echo "<h2>We are sorry...</h2><p>The subpage you are looking for does not exist. It may not have been created yet, or the link that led you here was typed incorrectly.</p>";
}
