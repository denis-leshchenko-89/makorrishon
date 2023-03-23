<?php

return array(
    'title' =>  esc_html(__('Translation Setting', 'jnews-front-translation')) ,
    'name' => 'translation_setting',
    'icon' => 'font-awesome:fa-cog',
    'controls' => array(
        array(
            'type' => 'toggle',
            'name' => 'enable_translation',
            'label' => esc_html(__('Enable Translation', 'jnews-front-translation')),
            'description' => esc_html(__('Enable build in translation from JNews.', 'jnews-front-translation')),
            'default' => '1',
        ),
    ),
);