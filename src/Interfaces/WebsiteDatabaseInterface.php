<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 20.03.19
 * Time: 11:02
 */

namespace Stefantoczek\SitemapParser\Interfaces;

interface WebsiteDatabaseInterface
{
    /** method for inserting webpage with hostname into database, must return inserted website
     *
     * @param string $hostname
     */
    public function insertWebpage($hostname);

    /** method for inserting page for parentWebsite passed as parameter, pageUrl is query path
     *
     * @param        $parentWebsite
     * @param string $pageUrl
     */
    public function insertPage($parentWebsite, $pageUrl);
}