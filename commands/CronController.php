<?php
/**
 * Created by PhpStorm.
 * User: kaldm
 * Date: 2/26/2016
 * Time: 1:23 AM
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\commands\User;
use app\commands\Post;

class CronController extends Controller
{



    public function actionFix()
    {
        $model = Yii::$app->db->createCommand("SELECT id, title FROM post WHERE title != '' AND (urlTitle = '' OR urlTitle is null) LIMIT 10000")->queryAll();
        print 'Count ' . count($model) . chr(10);
        foreach ($model as $item) {
            print ' Processing id : ' . $item['id'] . chr(10);
            $url = Yii::$app->helper->urlTitle($item['title']);
            $exist = Yii::$app->db->createCommand("SELECT id FROM post WHERE urlTitle = '{$url}' LIMIT 1")->queryOne();
            if ($exist) {
                $url .= "-{$item['id']}";
            }
            $re = Yii::$app->db->createCommand("UPDATE post SET urlTitle='{$url}' WHERE id={$item['id']}")->query();
            echo chr(10);
        }
    }

    public function actionSitemap(){
        $posts= Yii::$app->db->createCommand("SELECT t.id, t.title, t.urlTitle, t.created_at FROM  post t WHERE t.deleted = 0 ORDER BY t.id DESC;")->queryAll();
        $sitemap = new \DomDocument('1.0', 'UTF-8');
        $sitemap->preserveWhiteSpace = false;
        $sitemap->formatOutput = true;
        $root = $sitemap->createElement("urlset");
        $sitemap->appendChild($root);
        $root_attr = $sitemap->createAttribute('xmlns');
        $root->appendChild($root_attr);
        $root_attr_text = $sitemap->createTextNode('http://www.sitemaps.org/schemas/sitemap/0.9');
        $root_attr->appendChild($root_attr_text);
        foreach ($posts as $post) {
            $link = "www.hangshare.com/{$post['urlTitle']}/";
            $url = $sitemap->createElement('url');
            $root->appendChild($url);
            $loc = $sitemap->createElement("loc");
            $lastmod = $sitemap->createElement("lastmod");
            $changefreq = $sitemap->createElement("changefreq");
            $url->appendChild($loc);
            $url_text = $sitemap->createTextNode($link);
            $loc->appendChild($url_text);
            $url->appendChild($lastmod);
            $lastmod_text = $sitemap->createTextNode(date("Y-m-d", strtotime($post['created_at'])));
            $lastmod->appendChild($lastmod_text);
            $url->appendChild($changefreq);
            $changefreq_text = $sitemap->createTextNode("weekly");
            $changefreq->appendChild($changefreq_text);
        }
        $users= Yii::$app->db->createCommand("SELECT t.id, t.created_at, t.name FROM user t WHERE 1 ORDER BY t.id DESC;")->queryAll();
        foreach ($users as $user) {
            $link = "www.hangshare.com/user/{$user['id']}/";
            $url = $sitemap->createElement('url');
            $root->appendChild($url);

            $loc = $sitemap->createElement("loc");
            $lastmod = $sitemap->createElement("lastmod");
            $changefreq = $sitemap->createElement("changefreq");

            $url->appendChild($loc);
            $url_text = $sitemap->createTextNode($link);
            $loc->appendChild($url_text);

            $url->appendChild($lastmod);
            $lastmod_text = $sitemap->createTextNode(date("Y-m-d", strtotime($user['created_at'])));
            $lastmod->appendChild($lastmod_text);

            $url->appendChild($changefreq);
            $changefreq_text = $sitemap->createTextNode("weekly");
            $changefreq->appendChild($changefreq_text);
        }

        $file = Yii::$app->basePath . "/web/sitemap.xml";
        echo $file;
        $fh = fopen($file, 'w') or die("Can't open the sitemap file.");
        fwrite($fh, $sitemap->saveXML());
        fclose($fh);
    }
}



