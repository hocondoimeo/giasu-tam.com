<?php
/**
 * the news model
 * @author duy.ngo
 * @since 17-1-2013
 */
class Application_Model_News extends Application_Model_Abstract{
    /**
    * get all news from db
    * @param int $cate
    * @author duy.ngo
    * @since 17-1-2013
    * @return array $newsData
    */
    public function getAllNewsFromDB($cate = null){
        $newsData = array();
        try {
            $newsData['TopFeatureNews'] = self::getTopFeatureNews($cate);
            if(count($newsData['TopFeatureNews']['ids']) == 2)
                array_pop($newsData['TopFeatureNews']['ids']);

            /**
             * Get latest news
             */
            $lastestNews = self::getLastestNews($cate, $newsData['TopFeatureNews']['ids']);
            $newsData    = array_merge($newsData, $lastestNews);

            $newsData['PopularNews']    = self::getPopularNews();
            if(isset($newsData['TopFeatureNews']['ids'])) unset($newsData['TopFeatureNews']['ids']);
            if(isset($newsData['LastestNews']['ids'])) unset($newsData['LastestNews']['ids']);
            if(isset($newsData['PopularNews']['ids'])) unset($newsData['PopularNews']['ids']);

            return $newsData;
        } catch (Exception $e) {
            return $newsData;
        }
    }

    /**
     * Get post count by category
     * @author Quang Nguyen
     * @since Feb 5, 2013 11:27:58 AM
     * @param string $cate
     * @return int
     */
    public function getPostCount($cate = null) {
        global $wpdb;

        if (is_null($cate)) {
            $query = "SELECT post_status, COUNT( * ) AS num_posts FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status = 'publish'";
        } else {
            $query = "SELECT post_status, COUNT( * ) AS num_posts FROM {$wpdb->posts}
                JOIN {$wpdb->term_relationships} ON ({$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id)
                JOIN {$wpdb->term_taxonomy} ON ({$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id)
                JOIN {$wpdb->terms} ON ({$wpdb->term_taxonomy}.term_id = {$wpdb->terms}.term_id)
                WHERE post_type = 'post' AND post_status = 'publish'
                    AND {$wpdb->terms}.term_id = '{$cate}'";
        }

        $count = current($wpdb->get_results( $query, ARRAY_A ));
        if (isset($count['num_posts'])) return $count['num_posts'];

        return 0;
    }

    /**
     * Get fake data for pagination
     * @author Quang Nguyen
     * @since Feb 5, 2013 11:28:13 AM
     * @param int $totalPost
     * @return array
     */
    public function getFakeDataForPagination($totalPost) {
        $data = array();
        for ($i = 0; $i < $totalPost; $i++) {
            $data[] = $i;
        }

        return $data;
    }

    /**
    * get top feature news
    * @param int $cate
    * @author duy.ngo
    * @since 17-1-2013
    * @return array $postsSticky
    */
    public function getTopFeatureNews($cate = null, $exclude = false){
        $topFeatureNews = array();
        $args = array(
                'numberposts'      => 4,
                'offset'           => 0,
                'orderby'          => 'post_date',
                'order'            => 'DESC',
                'post_type'        => 'post',
                'post_status'      => 'publish',
                'suppress_filters' => true);
        if(!empty($cate)) $args['category'] = $cate;
        if($exclude) $args['exclude'] = true;
        //get news with sticky
        $postsSticky = self::getPosts($args, 'feature', true);
        $args['ids'] = array();
        if(!empty($postsSticky['ids'])){
            $args['ids'] = $postsSticky['ids'];
            unset($postsSticky['ids']);
        }
        $postNumber = count($postsSticky);
        //get news without sticky
        if(!$postNumber || $postNumber < 4){
            $args['stickys'] = $postsSticky;
            $postsSticky = self::getPosts($args, 'feature');
        }
        return $postsSticky;
    }

    /**
    * get lastest news
    * @param int $cate
    * @param int $featureNews
    * @param int $offset
    * @param int $numberPost
    * @author duy.ngo
    * @modify Quang Nguyen
    * @since 17-1-2013
    * @return array(LastestNews, FakeTotalLastestNews)
    */
    public function getLastestNews($cate = null, $featureNews = null, $offset = 1, $numberPost = LASTEST_NEWS_ITEMS){
        $args = array(
                'numberposts'      => $numberPost,
                'offset'           => ($offset - 1) * $numberPost,
                'orderby'          => 'post_date',
                'order'            => 'DESC',
                'post_type'        => 'post',
                'post_status'      => 'publish',
                'suppress_filters' => true );
        if(!empty($cate)) $args['category'] = $cate;
        if(empty($featureNews)){
            $featureNews = self::getTopFeatureNews($cate, true);

            $args['ids']     = $featureNews['ids'];
            $args['exclude'] = $featureNews['ids'];
        } else {
            $args['ids']     = $featureNews;
            $args['exclude'] = $featureNews;
        }
        /**
         * Create fake data for pagination
         */
        $totalLastestNews = $this->getPostCount($cate) - count($args['exclude']);
        $data = array(
            'LastestNews'           => self::getPosts($args, 'lastest'),
            'FakeTotalLastestNews'  => $this->getFakeDataForPagination($totalLastestNews),
        );
        return $data;
    }

    /**
    * get popular news
    * @param int $cate
    * @author duy.ngo
    * @since 17-1-2013
    * @return array
    */
    public function getPopularNews(){
        $args = array(
                'numberposts'      => POPULAR_NEWS_COUNT,
                'offset'           => 0,
                'orderby'          => 'meta_key',
                'order'            => 'DESC',
                'meta_key'         => POPULAR_META_KEY,
                'post_type'        => 'post',
                'post_status'      => 'publish',
                'suppress_filters' => true );
        return self::getPosts($args, 'popular');
    }

    /**
    * get posts from word press
    * @param array $args
    * @param string $image
    * @param boolean $sticky
    * @author duy.ngo
    * @since 17-1-2013
    * @return array $news
    */
    public function getPosts($args, $image, $sticky = false){
        $news = array(); $c = 0; $ids = array();
        try {
            if(is_array($args) && count($args)){
                if($sticky){
                    /* Get all sticky posts */
                    $sticky = get_option( 'sticky_posts' );
                    /* Sort the stickies with the newest ones at the top */
                    rsort( $sticky );
                    /* Get the 2 newest stickies */
                    $sticky = array_slice( $sticky, 0, 4 );
                    /* Query sticky posts */
                    $posts = query_posts( array( 'post__in' => $sticky, 'caller_get_posts' => 1 ) );
                }else
                    $posts = get_posts( $args );
                if(count($posts)){
                    foreach ($posts as $post){
                        if($sticky){
                            if(!isset($args['exclude']) && (!isset($args['category']) ||
                                 in_category($args['category'], $post))){
                                    $news[] = self::getPostData($post, $image, $c);
                                    $ids[] = $post->ID;
                             }
                        }else{
                            if($image == 'popular' || (isset($args['ids']) && !in_array($post->ID, $args['ids']))){
                                $news[] = $temp = self::getPostData($post, $image, $c);
                                if($image == 'feature' && count($args['ids']) < 4)
                                     array_push($args['ids'], $post->ID);
                            }
                            if(isset($args['stickys']) && count($args['stickys']) < 4) array_push($args['stickys'], $temp);
                        }$c++;
                    }
                    if($sticky) $news['ids'] = $ids;
                    if(isset($args['stickys'])) $news = $args['stickys'];
                    if(!empty($args['ids'])) $news['ids'] = $args['ids'];
                }
            }
            return $news;
        } catch (Exception $e) {
            return $news;
        }
    }

    /**
    * get post data
    * @param array $post
    * @param string $image
    * @param int $c
    * @author duy.ngo
    * @since 17-1-2013
    * @return array $news
    */
    public function getPostData($post, $image, $c){
        $news = $news_img = array();
        try {
            if(is_object($post) && is_array(get_object_vars($post)) && count($post)){
                $news['id']     = $post->ID;
                $news['title']  = $post->post_title;
                if($image == 'popular')
                    $news['title']  = self::subNewsString($post->post_title, POPULAR_NEWS_DESC_COUNT);
                $news['desc']   = '';
                if($image == 'feature')
                    $news['desc']   = self::subNewsString($post->post_excerpt, FEATURE_NEWS_DESC_COUNT);
                elseif($image == 'popular')
                    $news['desc']   = self::subNewsString($post->post_excerpt, POPULAR_NEWS_DESC_COUNT);
                elseif($image == 'lastest')
                    $news['desc']   = self::subNewsString($post->post_excerpt, LASTEST_NEWS_DESC_COUNT);
                $date = new Zend_Date($post->post_date, DATE_FORMAT_DATABASE);
                $news['date']   = $date->toString(DATE_FORMAT_NORMAL);
                //get attached images
                if (has_post_thumbnail($post->ID)) {
                    $imageId = get_post_thumbnail_id($post->ID);
                    $news_img['large']  = wp_get_attachment_image_src( $imageId, 'large');
                    if($image == 'feature'){
                        $news_img['thumbnail']  = wp_get_attachment_image_src( $imageId, 'medium');
                    }else{
                        $news_img['thumbnail']  = wp_get_attachment_image_src( $imageId, 'thumbnail');
                    }
                    $news_img['large']          = $news_img['large'][0];
                    $news_img['thumbnail']      = $news_img['thumbnail'][0];
                }
                $news['image']  = $news_img;
                //get author name from Staffs
                $staffsModel = new Application_Model_Staffs();
                $staff = $staffsModel->find(get_post_meta($post->ID, 'StaffId', true))->current();
                $news['author'] = $staff->FirstName.' '.$staff->LastName;
            }
            return $news;
        } catch (Exception $e) {
            return $news;
        }
    }

    /**
    * sub news string
    * @param string $string
    * @param int $words
    * @author duy.ngo
    * @since 21-1-2013
    * @return array $string
    */
    public function subNewsString($string, $words){
        try {
            if(is_string($string) && !empty($string)){
                $wordNumber = explode(' ', $string);
                if(count($wordNumber) > $words){
                    $wordNumber = array_slice($wordNumber, 0, $words);
                    $string = trim(implode(' ', $wordNumber)).'...';
                }
            }
            return $string;
        } catch (Exception $e) {
            return $string;
        }
    }

    /**
     * Update view count of post by postId
     *
     * if post hasn't been viewed, we add meta post view count,
     * otherwise we update meta post view count
     *
     * @author thu.nguyen
     * @since 21-1-2013
     * @param int $postId
     * @return void
     */
    public function getNewsDetail($newsId){
    	$cols = array('NewsId', 'Title', 'Summary', 'Content', 'ImageUrl', 'CreatedDate');
    	$select = $this->select()->from($this, $cols)->where('NewsId = ?', $newsId)->where('IsDisabled = 0');
    	$result = $this->fetchRow($select);var_dump($result);die;
    	if($result) return $result;
    	else return null;
    }
}