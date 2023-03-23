<?php

class mumlazimCron
{
    private $createCat = true;
    private $log = false;
    private $remote;
    private $startDate = '2018-02-14T00:00:00';
    private $pages;
    private $sleep = 5;
    private $writerId = 7049;
    private $catID = 158407;

    public function __construct()
    {
        //define('WP_USE_THEMES', false); // prevent functions.php from loading
        remove_filter('content_save_pre', 'wp_filter_post_kses');
        remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');
        set_time_limit(0);
        remove_action('pre_insert_term', 'disallow_insert_term', 10, 2);

        global $argv;


        $this->curl()->fetchPageFromCms(); //->exit();
        //$log = 'finished mumlazim';
        //file_put_contents('./log_mumlazim_'.date("j.n.Y").'.log', $log.PHP_EOL, FILE_APPEND);
        add_filter('content_save_pre', 'wp_filter_post_kses');
        add_filter('content_filtered_save_pre', 'wp_filter_post_kses');
    }

    private function close()
    {
        exit();
    }

    private function log($data)
    {

    }

    private function fetchPageFromCms()
    {
        //$this->log(sprintf('body %s', $this->remote['body']));

        $response = json_decode($this->remote['body'], true);

        $posts = array();
        $exists = array();

        global $expertsCat;

        if (!count($response)) {
            $log = 'Response wasnt json' . $response;
            file_put_contents('./log_mumlazim_'.date("j.n.Y").'.log', $log.PHP_EOL, FILE_APPEND);
            return; //$this->log(sprintf('Response wasnt json %s', $response));
        }

        date_default_timezone_set('UTC');

        foreach ($response as $_post) {

            $args = array('meta_key' => 'mumlazimId', 'meta_value' => $_post['id']);


            $query = new WP_Query($args);
            if ($query->have_posts()) {
                $postId = $query->posts[0]->ID;

                if (strtotime($_post['modified_gmt']) <= strtotime($query->posts[0]->post_modified_gmt)) {
                    // $this->log('continue');
                    continue;
                }

                $exists[] = $postId;

                $prepare = array(
                    'ID' => $query->posts[0]->ID,

                    'meta_input' => array('mumlazimId' => $_post['id'], 'search_engines' => $_post['post-meta-fields']['Search_Engines'][0], 'nrg_featured_image' => $_post['featured_media'] ),

                    'post_date' => date('Y-m-d H:i:s', strtotime($_post['date'])),
                    'post_date_gmt' => date('Y-m-d H:i:s', strtotime($_post['date_gmt'])),
                    'post_modified' => date('Y-m-d H:i:s', strtotime($_post['modified'])),
                    'post_modified_gmt' => date('Y-m-d H:i:s', strtotime($_post['modified_gmt'])),

                    'post_title' => $_post['title']['rendered'],
                    'post_excerpt' => $_post['excerpt']['rendered'],
                    'post_content' => $this->content_replace_images($_post['content']['rendered']),
                );

                //$this->log($prepare['post_modified_gmt']);
                wp_update_post($prepare);


            } else {
                $prepare = array(
                    'meta_input' => array('mumlazimId' => $_post['id'], 'search_engines' => $_post['post-meta-fields']['Search_Engines'][0],'nrg_featured_image' => $_post['featured_media']),
                    'post_date' => date('Y-m-d H:i:s', strtotime($_post['date'])),
                    'post_date_gmt' => date('Y-m-d H:i:s', strtotime($_post['date_gmt'])),
                    'post_modified' => date('Y-m-d H:i:s', strtotime($_post['modified'])),
                    'post_modified_gmt' => date('Y-m-d H:i:s', strtotime($_post['modified_gmt'])),

                    'post_title' => $_post['title']['rendered'],
                    'post_excerpt' => $_post['excerpt']['rendered'],
                    'post_content' => $this->content_replace_images($_post['content']['rendered']),
                    'post_status' => 'publish',
                );


                $postId = wp_insert_post($prepare);

                wp_update_post(array('ID' => $postId, 'post_date_gmt' => date('Y-m-d H:i:s', strtotime($_post['modified_gmt']))));

                $posts[] = $postId;
            }

            wp_set_object_terms($postId, array($_post['category']), 'category');


            if(isset($_post['reporters']) && ! empty($_post['reporters'])){
                foreach ($_post['reporters'] as $reporter){
                    // file_put_contents('./log_mumlazim_'.date("j.n.Y").'.log', $reporter, FILE_APPEND);
                    wp_set_object_terms($postId, array($reporter), 'writer', true);

                }
            }
            update_field('post_subtitle', $_post['acf']['subtitle'], $postId);
            update_field('collaboration', $_post['acf']['collaboration'], $postId);
            if (isset($_post['_links']['wp:featuredmedia'][0]['href'])) {

                $data = json_decode(wp_remote_retrieve_body(wp_remote_get($_post['_links']['wp:featuredmedia'][0]['href'])), true);
                //$this->import_fetured_image($data['source_url'], $postId );
                $attachimg =  $this->import_fetured_image($data['source_url'], $postId );

                $get_post = get_post($postId);
                $post_content = $get_post->post_content;
                $new_attach = wp_get_attachment_image($attachimg,'full',false, array ('title' => ''));
                $return = str_replace('http:','https:',$new_attach);
                $post_content = '<div class="mumlazim-align">'.$return.'</div>'.$post_content;
                $post_data = array('ID'=>$postId,'post_content'=>$post_content);
                wp_update_post($post_data);
            }


            // @TODO move attachment

            //try{rocket_clean_post($postId);} catch (Exception $e){}

        }



        return $this;
    }

    private function content_replace_images($content)
    {

        $disclaimer_text = 'האמור בכתבה כולל תוכן ומידע מסחרי/שיווקי , ומערכת מקור ראשון אינה אחראית למהימנותו. פרסום התכנים והמידע המסחרי בכתבה אינו מהווה המלצה או הצעה מצד מערכת מקור ראשון לרכוש ו/או להשתמש בשירותים או המוצרים בכתבה.';

//        preg_match_all("/((.*)img(.*))/", $content, $output_array);
//
//        for ($i = 0; $i < count($output_array[0]); $i++) {
//            $dom = new DOMDocument();
//            $dom->loadHTML(mb_convert_encoding($output_array[0][$i], 'HTML-ENTITIES', 'UTF-8'));
//
//            $img = $dom->getElementsByTagName('img')->item(0);
//
//            $img->removeAttribute('srcset');
//
//            $attach_id = $this->import_fetured_image($img->getAttribute('src'), false);
//
//
//            $src = wp_get_attachment_image_src($attach_id,array($img->getAttribute('width'),$img->getAttribute('height')));
//            if ($src) {
//                $img->setAttribute('src', $src[0]);
//
//            }
//            else
//                $content = str_replace($output_array[0][$i], $dom->saveHTML($img) , $content);
//        }

        preg_match_all("/(<figure ?.*>(.*)<\/figure>)/", $content, $output_array);

        for ($i = 0; $i < count($output_array[0]); $i++) {
            $dom = new DOMDocument();
            $dom->loadHTML(mb_convert_encoding($output_array[0][$i], 'HTML-ENTITIES', 'UTF-8'));

            $figure = $dom->getElementsByTagName('figure')->item(0);
            $img = $figure->getElementsByTagName('img')->item(0);

            $img->removeAttribute('srcset');

            $class = array_filter(explode(" ", $img->getAttribute('class')), function ($v, $k) {
                return strpos($v, 'wp-') === false;
            }, ARRAY_FILTER_USE_BOTH);

            $class = implode(" ", $class);
            $attach_id = $this->import_fetured_image($img->getAttribute('src'), false);
            $src = wp_get_attachment_image_src($attach_id, array($img->getAttribute('width'), $img->getAttribute('height')));
            $return = str_replace('http:','https:',$src);
            $img->setAttribute('src', $return[0]);
            $content = str_replace($output_array[0][$i], $dom->saveHTML($figure), $content);

        }

        return  '<div class="expert-channel-content">'.$content .'</div><div class="expert-channel-disclaimer">'.$disclaimer_text.'</div>';
    }

    private function import_fetured_image($image_url, $post_id = 0 )
    {
        // Add Featured Image to Post

        $image_name = basename($image_url);
        $upload_dir = wp_upload_dir(); // Set upload folder
        if (!$image_url) {
            return;
        }
        $return = str_replace('https:','http:',$image_url);
        $image_data = file_get_contents($return); // Get image data
        $unique_file_name = wp_unique_filename($upload_dir['path'], $image_name); // Generate unique name
        $filename = basename($unique_file_name); // Create image file name
// Check folder permission and define file location
        if (wp_mkdir_p($upload_dir['path'])) {
            $file = $upload_dir['path'] . '/' . $filename;
        } else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }

        // Create the image  file on the server
        file_put_contents($file, $image_data);
        // Check image file type
        $wp_filetype = wp_check_filetype($filename, null);

        // Set attachment data
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit'
        );

        // Create the attachment
        $attach_id = wp_insert_attachment($attachment, $file, $post_id);

        // Include image.php
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // Define attachment metadata
        $attach_data = wp_generate_attachment_metadata($attach_id, $file);
        // Assign metadata to attachment
        wp_update_attachment_metadata($attach_id, $attach_data);

        // And finally assign featured image to post
        if ($post_id) {
            set_post_thumbnail($post_id, $attach_id);
        }
        return $attach_id;
    }

    private function curl($page = 0)
    {
        try {
            $remote = wp_remote_get('http://mumlazim.makorrishon.co.il/wp-json/wp/v2/posts?per_page=10');
        }
        catch (Exception $e){

            file_put_contents('./log_mumlazim_'.date("j.n.Y").'.log', $e.PHP_EOL, FILE_APPEND);
        }
        $response_code  = wp_remote_retrieve_response_code( $remote );
       // file_put_contents('./log_mumlazim_'.date("j.n.Y").'.log', $response_code.PHP_EOL, FILE_APPEND);
// Set one or more request query parameters

        //if (!is_array($remote)) return exit('curl error http://dev-out.nrg.co.il/?' . http_build_query($queryString));

        $this->remote = $remote;
        $this->pages = $this->remote['headers']['x-wp-totalpages'];
      //  file_put_contents('./log_mumlazim_'.date("j.n.Y").'.log', $this->pages.PHP_EOL, FILE_APPEND);
        //  $this->log(sprintf('There are %d pages to fetch', ));
        // $this->log(sprintf("%s", print_r($this->remote['body'], true)));

        return $this;
    }

    private function paganation()
    {
        if ($this->pages <= 1) {
            return $this;
        }
        for ($i = 2; $i <= (int)$this->pages; $i++) {
            $this->curl($i)->log('Recived page %d from %d', $i, $this->pages);
            sleep($this->sleep);
        }
        return $this;
    }
}
/*if($_SERVER['REQUEST_URI'] == '/themes/jnews/cron/mumlazim-cron.php')
    $mumlazimCron = new mumlazimCron();*/
