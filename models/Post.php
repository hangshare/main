<?php

namespace app\models;

use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property integer $userId
 * @property string $cover
 * @property string $title
 * @property string $created_at
 *
 * @property User $user
 * @property PostBody[] $postBodies
 * @property PostStats $postStats
 * @property PostTag[] $postTags
 * @property string urlTitle
 */
class Post extends \yii\db\ActiveRecord
{

    public $body, $tags, $cover_file, $q, $keywords, $ylink, $vidId, $vidType, $url;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    public static function featured($limit = 8)
    {
        $featured = Yii::$app->cache->get('featured-posts');
        if ($featured === false) {
            $featured = Post::find()
                ->where("type=0 AND cover <> '' AND featured = 1")
                ->orderBy('sort desc')
                ->select('id,cover,title, urlTitle')
                ->limit($limit)
                ->all();
            Yii::$app->cache->set('featured-posts', $featured, 300);
        }
        return $featured;
    }

    public static function mostViewed($limit = 5)
    {
        $most = Yii::$app->cache->get('mostViewed');
        if ($most === false) {
            $most = Post::find()
                ->where("cover <> ''")
                ->joinWith(['postStats'])
                ->select('id,cover,title, urlTitle')
                ->orderBy('post_stats.views desc')
                ->limit($limit)
                ->all();
            Yii::$app->cache->set('mostViewed', $most, 300);
        }
        return $most;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'tags', 'body'], 'required'],
            [['title', 'urlTitle'], 'unique'],
            [['cover_file', 'ylink'], 'either'],
            ['ylink', 'match', 'pattern' => '/^https?:\/\/(?:.*?)\.?(youtube|vimeo)\.com\/(watch\?[^#]*v=([\w-]+)|(\d+)).*$/'],
            [['cover_file'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'gif', 'jpeg'],
                'maxSize' => 1024 * 1024 * 4],
            [['userId', 'type', 'deleted'], 'integer'],
            [['created_at', 'body', 'featured', 'deleted', 'tags', 'keywords', 'cover_file', 'q', 'type', 'ylink', 'vidId', 'vidType'], 'safe'],
            [['cover'], 'string', 'max' => 100],
            [['urlTitle'], 'string', 'max' => 200],
            [['title'], 'string', 'max' => 50],
            [['body'], 'string', 'min' => 150],
        ];
    }

    public function either($attribute_name, $params)
    {
        if (!empty($this->ylink) || $this->cover_file) {
            return true;
        }
        $this->addError('cover_file', Yii::t('app', "يرجى اضافة صورة غلاف"));
        return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'userId' => Yii::t('app', 'User ID'),
            'cover_file' => 'صورة الغلاف',
            'title' => 'عنوان الموضوع',
            'body' => 'الموضوع',
            'created_at' => Yii::t('app', 'Created At'),
            'q' => 'البحث',
            'tags' => 'التصنيفات الرئيسية',
            'ylink' => 'رابط من موقع YouTube أو Vimeo',
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->userId = Yii::$app->user->id;
            Yii::$app->db->createCommand('UPDATE `user_stats` SET `post_count`=`post_count`+1 WHERE `userId`=' . $this->userId)->query();

            $url = Yii::$app->helper->urlTitle($this->title);
            $exist = Yii::$app->db->createCommand("SELECT id FROM post WHERE urlTitle = '{$url}' LIMIT 1")->queryOne();
            if ($exist) {
                $rand = $exist['id'] * random_int(2, 5);
                $url .= "-{$rand}";
            }
            $this->urlTitle = $url;
        }
        return true;
    }


    public function saveExternal($image)
    {
        $imageLink = $this->cover;

        if (preg_match("/https?:\/\/(?:www\.)?vimeo\.com\/\d+/", $this->ylink)) {
            $typeid = 'vimeo';
            $vidId = substr(parse_url($this->ylink, PHP_URL_PATH), 1);
            if ($image) {
                $JSonurl = "http://vimeo.com/api/v2/video/{$vidId}.json";
                $headers = get_headers($JSonurl);
                $urlResopnse = substr($headers[0], 9, 3);
                if ($urlResopnse == '200') {
                    $url = file_get_contents($JSonurl);
                    $url_json = json_decode($url);
                    $path = Yii::$app->basePath . "/web/media/{$typeid}/$vidId.jpg";
                    $imageLink = "{$typeid}/$vidId.jpg";
                    Yii::$app->helper->downloadFromUrl($url_json[0]->thumbnail_large, $path);
                }
            }
        } else if (preg_match('#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x', $this->ylink)) {
            $typeid = 'youtube';
            $vidId = Yii::$app->helper->youtubeId($this->ylink);
            if ($image) {
                $imageLink = "{$typeid}/$vidId.jpg";
                $path = Yii::$app->basePath . "/web/media/{$typeid}/$vidId.jpg";
                Yii::$app->helper->downloadFromUrl("http://img.youtube.com/vi/{$vidId}/0.jpg", $path);
            }
        }
        if (isset($typeid)) {
            $this->cover = json_encode([
                'type' => $typeid,
                'link' => $vidId,
                'image' => $imageLink
            ]);
        } else {
            $this->type = 0;
        }
    }

    public function afterFind()
    {
        if ($this->type) {
            $obj = json_decode($this->cover);
            if (isset($obj)) {
                $this->cover = $obj->image;
                $this->vidId = $obj->link;
                $this->vidType = $obj->type;
                if ($obj->type == 'youtube') {
                    $this->ylink = "https://www.youtube.com/watch?v={$obj->link}";
                } else {
                    $this->ylink = "https://vimeo.com/{$obj->link}";
                }
            }
        }
        $this->url = Yii::$app->urlManager->createAbsoluteUrl(["/{$this->urlTitle}"]);
        return TRUE;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $stats = new PostStats;
            $stats->postId = $this->id;
            $stats->views = 0;
            $stats->fb_share = 0;
            $stats->tw_share = 0;
            $stats->g_share = 0;
            $stats->comments = 0;
            $stats->save();
            $body = new PostBody;
            $body->postId = $this->id;
        } else {
            if (!empty($this->tags)) {
                Yii::$app->db->createCommand('DELETE FROM post_tag WHERE postId = ' . $this->id)->query();
            }
            $body = PostBody::findOne(['postId' => $this->id]);
            if (!isset($body)) {
                $body = new PostBody;
                $body->postId = $this->id;
            }
        }
        if ($this->type) {
            array_push($this->tags, 2);
        }
        if (!empty($this->tags)) {
            $insertrow = '';
            foreach ($this->tags as $tag) {
                $insertrow .= "('{$this->id}','{$tag}'),";
            }
            $insertrow = rtrim($insertrow, ",");
            Yii::$app->db->createCommand("INSERT INTO post_tag (postId, tag) VALUES $insertrow")->query();
        }
        if (!empty($this->keywords)) {
            $insertrow = '';
            foreach ($this->keywords as $keywords) {
                if (!is_numeric($keywords)) {
                    $nu = new Tags;
                    $nu->name = $keywords;
                    if ($nu->save()) {
                        $keywords = $nu->id;
                    }
                }
                $insertrow .= "('{$this->id}','{$keywords}'),";
            }
            $insertrow = rtrim($insertrow, ",");
            Yii::$app->db->createCommand("INSERT INTO post_tag (postId, tag) VALUES $insertrow")->query();
        }

        $body->body = $this->body;
        if (!$body->save()) {
            var_dump($body->getErrors());
            die();
        }
    }

    public function upload()
    {
        Yii::$app->controller->enableCsrfValidation = false;
        if ($this->validate() && isset($this->cover_file)) {

            $this->cover = date('Ydm');
            if (!is_dir(Yii::$app->basePath . '/web/media/' . $this->cover)) {
                mkdir(Yii::$app->basePath . '/web/media/' . $this->cover, 0777, true);
            }
            $filename = rand(1, 100) . '-' . preg_replace("/[^A-Za-z0-9?!]/", '-', $this->cover_file->baseName) . '.' . $this->cover_file->extension;
            $this->cover = $this->cover . '/' . $filename;
            try {

                $re = $this->cover_file->saveAs(Yii::$app->basePath . '/web/media/' . $this->cover);
                var_dump(Yii::$app->basePath . '/web/media/' . $this->cover);
                var_dump($re);
                die();
            } catch (Exception $e) {

            }


            $this->cover_file = NULL;
            return TRUE;
        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId'])->select('id,name,image,bio');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostBodies()
    {
        return $this->hasMany(PostBody::className(), ['postId' => 'id'])->select('body');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostStats()
    {
        return $this->hasOne(PostStats::className(), ['postId' => 'id'])->select('views');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostTags()
    {
        return $this->hasMany(PostTag::className(), ['postId' => 'id'])->limit(30);
    }

}
