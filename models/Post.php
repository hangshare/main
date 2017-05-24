<?php

namespace app\models;

use app\components\WordValidator;
use Yii;
use yii\base\Exception;
use yii\db\Expression;
use yii\helpers\Url;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $score
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

    public $body, $tags, $cover_file, $q, $keywords, $categories, $ylink, $vidId, $vidType, $url;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'body'], 'required'],
            [['title'], WordValidator::className()],
            [['urlTitle'], 'unique'],
            [['cover_file', 'ylink'], 'either'],
            ['ylink', 'match', 'pattern' => '/^https?:\/\/(?:.*?)\.?(youtube|vimeo)\.com\/(watch\?[^#]*v=([\w-]+)|(\d+)).*$/'],
            [['cover_file'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'gif', 'jpeg'],
                'maxSize' => 1024 * 1024 * 4],
            [['userId', 'type', 'deleted', 'score'], 'integer'],
            [['created_at', 'body', 'featured', 'score', 'deleted', 'tags', 'keywords', 'cover_file', 'q', 'type', 'ylink', 'vidId', 'vidType', 'published'], 'safe'],
            [['cover'], 'string', 'max' => 500],
            [['urlTitle'], 'string', 'max' => 200],
            [['title'], 'string', 'max' => 100],
            [['body'], 'string', 'min' => 500, 'message' => Yii::t('app', 'Article is too short')],
            [['lang'], 'string', 'max' => 2],
        ];
    }


    public static function related($id, $limit = 8)
    {
        $related = Yii::$app->cache->get('related-posts-' . $id . 'num' . $limit . Yii::$app->language);
        if ($related === false) {
            $cat = PostCategory::find()
                ->select('categoryId')
                ->where(['postId' => $id])->all();
            $ids = '';
            $ars = [];
            foreach ($cat as $ca) {
                $ars[] = $ca->categoryId;
            }
            $ids = implode(',', $ars);
            $query = Post::find();
            $query->joinWith(['postCategories']);
            $query->select('post.id,post.cover,post.title, post.urlTitle');
            $query->where("post.deleted=0 AND post.published=1 AND post.lang = '" . Yii::$app->language . "'");
            if ($ids)
                $query->andWhere("post_category.categoryId IN ({$ids})");
            $query->andWhere(['<>', 'post.cover', '']);
            $query->orderBy(new Expression('rand()'));
            $query->limit($limit);
            $related = $query->all();

            Yii::$app->cache->set('related-posts-' . $id . 'num' . $limit . Yii::$app->language, $related, 300);
        }
        return $related;
    }

    public static function featured($limit = 8)
    {
        $featured = Yii::$app->cache->get('featured-posts-' . $limit . '-' . Yii::$app->language);
        if ($featured === false) {
            $featured = Post::find()
                ->where("deleted=0 AND published=1 AND score = 5 AND lang = '" . Yii::$app->language . "'")
                ->select('id,cover,title, urlTitle')
                ->orderBy('id desc')
                ->limit($limit)
                ->all();

            Yii::$app->cache->set('featured-posts-' . $limit . '-' . Yii::$app->language, $featured, 300);
        }
        return $featured;
    }

    public static function mostViewed($limit = 5)
    {
        $most = Yii::$app->cache->get('mostViewed');
        if ($most === false) {
            $most = Post::find()
                ->where("published=1 AND cover <> ''  AND lang = '" . Yii::$app->language . "'")
                ->joinWith(['postStats'])
                ->select('id,cover,title, urlTitle')
                ->orderBy('post_stats.views desc')
                ->limit($limit)
                ->all();
            Yii::$app->cache->set('mostViewed', $most, 300);
        }
        return $most;
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
            'id' => Yii::t('app', 'Post.id'),
            'userId' => Yii::t('app', 'Post.user'),
            'cover_file' => Yii::t('app', 'Post.coverFile'),
            'title' => Yii::t('app', 'Post.title'),
            'body' => Yii::t('app', 'Post.body'),
            'created_at' => Yii::t('app', 'Post.created_at'),
            'q' => Yii::t('app', 'Post.search'),
            'tags' => Yii::t('app', 'Post.tags'),
            'ylink' => Yii::t('app', 'Post.ylink'),
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->userId = Yii::$app->user->id;
            if (!empty($this->userId)) {
                Yii::$app->db->createCommand('UPDATE `user_stats` SET `post_count`=`post_count`+1 WHERE `userId`=' . $this->userId)->query();
            }
            $url = Yii::$app->helper->urlTitle($this->title);
            $exist = Yii::$app->db->createCommand("SELECT id FROM post WHERE urlTitle = '{$url}' LIMIT 1")->queryOne();
            if ($exist) {
                $rand = uniqid(rand(), true);
                $rand = substr($rand, 0, 8);
                $url .= "-{$rand}";
            }
            $this->urlTitle = $url;
            $this->lang = Yii::$app->language;
            $this->published = 0;
        }
        return true;
    }

    public function saveExternal()
    {
        $this->type = 0;
        if (isset($_POST['cover']) && $json = $_POST['cover']) {
            if (!empty($json)) {

                var_dump($this->cover);
                $json = json_decode($json);
                var_dump($json);
                die();

                $this->cover = json_encode([
                    'type' => 's3',
                    'image' => $json->key,
                    'bucket' => $json->bucket,
                    'width' => $json->width,
                    'height' => $json->height
                ]);


            }
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
        if ($this->lang == 'en')
            $this->url = Yii::$app->urlManager->createAbsoluteUrl(["//en/{$this->urlTitle}"]);
        else
            $this->url = Yii::$app->urlManager->createAbsoluteUrl(["//{$this->urlTitle}"]);
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
            $body = PostBody::findOne(['postId' => $this->id]);
            if (!isset($body)) {
                $body = new PostBody;
                $body->postId = $this->id;
            }
        }

        if (!empty($this->categories)) {
            Yii::$app->db->createCommand('DELETE FROM post_category WHERE postId = ' . $this->id)->query();
            $insertrow = '';
            foreach ($this->categories as $tag) {
                $insertrow .= "('{$this->id}','{$tag}'),";
            }
            $insertrow = rtrim($insertrow, ",");
            Yii::$app->db->createCommand("INSERT INTO post_category (postId, categoryId) VALUES $insertrow")->query();
        }

        if (!empty($this->keywords)) {
            Yii::$app->db->createCommand('DELETE FROM post_tag WHERE postId = ' . $this->id)->query();
            $insertrow = '';

            foreach ($this->keywords as $keywords) {

                if (!is_numeric($keywords)) {
                    $nu = new Tags;
                    $nu->name = $keywords;
                    if ($nu->save()) {
                        $keywords = $nu->id;
                    } else {
                        $keywords = Yii::$app->db->createCommand("SELECT id FROM tags WHERE name LIKE '%{$keywords}%'")->queryScalar();
                    }
                }
                $insertrow .= "('{$this->id}','{$keywords}'),";
            }
            $insertrow = rtrim($insertrow, ",");
            Yii::$app->db->createCommand("INSERT INTO post_tag (postId, tag) VALUES $insertrow")->query();
        }
        $body->body = $this->body;
        $body->save();
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
        return $this->hasOne(User::className(), ['id' => 'userId'])->select('id,name,username,image,bio,plan');
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
        return $this->hasOne(PostStats::className(), ['postId' => 'id'])->select('views,profit');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostTags()
    {
        return $this->hasMany(PostTag::className(), ['postId' => 'id'])->limit(30);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostCategories()
    {
        return $this->hasMany(PostCategory::className(), ['postId' => 'id'])->limit(10);
    }

}
