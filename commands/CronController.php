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
    public function actionViewsbak()
    {
        $char = range('a', 'zzz');
        $i = 3;
        Yii::$app->db->createCommand("CREATE TABLE hangshare.post_view_{$char[$i]} LIKE hangshare.post_view;
              INSERT hangshare.post_view_{$char[$i]} SELECT * FROM hangshare.post_view;")->query();
        Yii::$app->db->createCommand("TRUNCATE `hangshare`.`post_view`;")->query();
    }

    public function actionDeservedamount()
    {
        //GOLD USERS
        Yii::$app->db->createCommand("SET SQL_SAFE_UPDATES = 0;")->query();
        Yii::$app->db->createCommand("UPDATE user_stats stats
        LEFT OUTER JOIN user on (stats.userId = user.id)
        SET
        stats.cantake_amount = stats.available_amount,
        stats.available_amount = 0

        WHERE plan = 1 AND stats.available_amount >=50")->query();
        //Normal USERS

        Yii::$app->db->createCommand("UPDATE user_stats stats
        LEFT OUTER JOIN user on (stats.userId = user.id)
        SET
        stats.cantake_amount = stats.available_amount,
        stats.available_amount = 0

        WHERE plan = 0 AND stats.available_amount >=100")->query();

        Yii::$app->db->createCommand("SET SQL_SAFE_UPDATES = 1;")->query();
    }

    public function actionGoldend()
    {
        $time = time();
        $users = Yii::$app->db->createCommand("
        SELECT t.id ,t.userId FROM user_payment as t
        LEFT OUTER JOIN user as user on (user.id = t.userId)
        WHERE t.end_date <= {$time} AND user.plan = 1 AND t.active = 1;")->queryAll();
        $u_ar = [];
        $p_ar = [];
        foreach ($users as $user) {
            $u_ar[] = $user['userId'];
            $p_ar[] = $user['id'];
        }
        $st = implode(',', $u_ar);
        $pst = implode(',', $p_ar);
        if (!empty($st)) {
            Yii::$app->db->createCommand("UPDATE user_payment SET active = 0 WHERE id IN ({$pst});")->query();
            Yii::$app->db->createCommand("UPDATE user SET plan = 0 WHERE id IN ({$st});")->query();
        }
    }

    public function actionCounter()
    {
        $memcached = new \Memcache();
        $memcached->addserver('127.0.0.1');
        $results = $memcached->get('hang_mem_views');
        $memcached->delete('hang_mem_views');
        var_dump($results);
        if (is_array($results)) {
            foreach ($results as $id => $views) {
                $country = [];
                $total_views = 0;
                foreach ($views as $hash => $view) {
                    $vie = Yii::$app->db->createCommand("SELECT 1 FROM `post_view` WHERE hash = '{$hash}'")->queryScalar();
                    if (!$vie) {
                        if (!isset($country["{$view['country_code']}"])) {
                            $country["{$view['country_code']}"] = 0;
                        }
                        $country["{$view['country_code']}"] += 1;
                        $total_views++;
                        $ins[] = "({$view['userId']}, {$id}, '{$view['ip']}', '{$hash}')";
                    }
                }
                if (isset($ins)) {
                    $qar = implode(', ', $ins);
                    try {
                        Yii::$app->db->createCommand("INSERT INTO `hangshare`.`post_view` (`userId` , `postId` , `ip` , `hash`)
                                VALUES {$qar} ;")->query();
                    } catch (Exception $e) {

                    }
                }
                $total_price = 0;
                foreach ($country as $key => $num) {
                    $country_price = $memcached->get('country_price_' . $key);
                    if ($country_price == false) {
                        $country_price = Yii::$app->db->createCommand("SELECT id ,code, price, regionId FROM `country` WHERE code = '{$key}'")->queryOne();
                        $memcached->set('country_price_' . $key, $country_price);
                    }
                    if ($country_price['price'] == '-1') { // Default
                        $default_price = Yii::$app->db->createCommand("SELECT `value` FROM `sys_values` t WHERE t.`key` = 'default_view_price'")->queryScalar();
                        $cu_pr = $num * $default_price;
                    } elseif ($country_price['price'] == '-2') { // Region
                        $region_price = Yii::$app->db->createCommand("SELECT price FROM `region` WHERE id = '{$country_price['regionId']}'")->queryScalar();
                        $cu_pr = $num * $region_price;
                    } else {
                        $cu_pr = $num * $country_price['price'];
                    }

                    $total_price += $cu_pr;
                    if (empty($country_price['id'])) {
                        $countryId = '1';
                    } else {
                        $countryId = $country_price['id'];
                    }
                    $insq[] = "({$id}, {$countryId}, views+{$num}, income+{$cu_pr})";
                }
                if (isset($ins)) {
                    $qar = implode(', ', $insq);

                    Yii::$app->db->createCommand("
                    INSERT INTO post_view_country (`postId` , `countryId` , `views` , `income`)
                    VALUES {$qar} ;")->query();
                }
                if ($view['plan']) {
                    $gold_price = Yii::$app->db->createCommand("SELECT `value` FROM `sys_values` WHERE `key` = 'gold_view_price'")->queryScalar();
                    $total_price *= $gold_price;
                }
                Yii::$app->db->createCommand("UPDATE `post_stats` SET `views`=`views`+{$total_views}, `profit` = `profit` + {$total_price} WHERE `postId`= {$id}")->query();
                Yii::$app->db->createCommand("UPDATE `user_stats` SET `post_total_views`=`post_total_views`+{$total_views}, `post_views`=`post_views`+{$total_views},
                `available_amount`=`available_amount`+{$total_price}, `total_amount`=`total_amount`+ {$total_price} WHERE `userId`= {$view['post_user_id']}")->query();
            }
        }
        print 'Done ...' . chr(10);
    }

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

    public function actionSitemap()
    {
        $posts = Yii::$app->db->createCommand("SELECT t.id, t.title, t.urlTitle, t.created_at FROM  post t WHERE t.deleted = 0 ORDER BY t.id DESC;")->queryAll();
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
            $link = "http://www.hangshare.com/{$post['urlTitle']}/";
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
        $users = Yii::$app->db->createCommand("SELECT t.id, t.username, t.created_at, t.name FROM user t WHERE 1 ORDER BY t.id DESC;")->queryAll();
        foreach ($users as $user) {
            $username = empty($user['username']) ? $user['id'] : $user['username'];
            $link = "http://www.hangshare.com/user/{$username}/";
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



