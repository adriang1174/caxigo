<?php

if ($_GET['tab2'] == "create")
{
    require_once('sources/create_album.php');
}
else
{
    $album_id = (int) $_GET['tab2'];
    $queryOne = $conn->query("SELECT id,name,descr,timeline_id FROM " . DB_MEDIA . " WHERE id=" . $album_id . " AND type='album' AND temp=0 AND active=1");

    if ($queryOne->num_rows == 1) {
        $album = $queryOne->fetch_array(MYSQLI_ASSOC);

        $albumTimelineObj = new \SocialKit\User();
        $albumTimelineObj->setId($album['timeline_id']);
        $album['timeline'] = $albumTimelineObj->getRows();

        $continue = true;

        if ($album['timeline']['post_privacy'] == "following")
        {
            if (! $albumTimelineObj->isFollowing())
            {
                $continue = false;
            }
        }

        foreach ($album as $key => $value)
        {
            if (! is_array($value))
            {
                $themeData['album_' . $key] = $value;
            }
            else
            {
                foreach ($value as $key2 => $value2)
                {
                    if (! is_array($value2))
                    {
                        $themeData['album_' . $key . '_' . $key2] = $value2;
                    }
                }
            }
        }

        /* */
        if ($album['timeline_id'] == $user['id'])
        {
            $themeData['options'] = \SocialKit\UI::view('album/default-options');
        }
        else
        {
            $themeData['options'] = \SocialKit\UI::view('album/end-options');
        }

        $i = 0;
        $listPhotos = '';

        $albumObj = new \SocialKit\Media();
        $albumObj->setId($album['id']);
        $photos = $albumObj->getRows();

        foreach ($photos['each'] as $photo)
        {
            //echo '<pre>'; var_dump($photo); echo '</pre>';
            $themeData['list_photo_story_id'] = $photo['post_id'];
            $themeData['list_photo_url'] = $config['site_url'] . '/' . $photo['url'] . '_100x100.' . $photo['extension'];

            if ($photos['timeline']['id'] == $user['id'])
            {
                $themeData['list_photo_buttons'] = \SocialKit\UI::view('album/list-photo-each-buttons');
            }

            $listPhotos .= \SocialKit\UI::view('album/list-photo-each');
        }
        $themeData['list_photos'] = $listPhotos;

        /* Suggestions */
        $themeData['suggestions'] = getFollowSuggestions();

        /* Trending */
        $themeData['trendings'] = getTrendings('popular');

        if (isLogged())
        {
            $themeData['sidebar'] = \SocialKit\UI::view('album/sidebar');
        }

        $themeData['end'] = \SocialKit\UI::view('album/end');
        /* */
        
        if ($continue == true)
        {
            $themeData['page_content'] = \SocialKit\UI::view('album/content');
        }
    }
}
?>