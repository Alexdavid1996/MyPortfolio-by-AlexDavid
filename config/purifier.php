<?php
/**
 * HTMLPurifier config for Laravel (Mews\Purifier)
 * Cache dir can be overridden via PURIFIER_CACHE_DIR in .env
 * e.g. PURIFIER_CACHE_DIR=framework/cache/purifier
 */

return [
    'encoding'         => 'UTF-8',
    'finalize'         => true,
    'ignoreNonStrings' => false,

    // Safe default cache path (works on Windows and Linux servers)
    'cachePath'     => storage_path(env('PURIFIER_CACHE_DIR', 'framework/cache/purifier')),
    'cacheFileMode' => 0755,

    'settings' => [

        'default' => [
            // Use a supported doctype (HTML5 is not supported by HTMLPurifier)
            'HTML.Doctype' => 'HTML 4.01 Transitional',

            // Ensure HTMLPurifier itself writes to the same cache dir
            'Cache.SerializerPath' => storage_path(env('PURIFIER_CACHE_DIR', 'framework/cache/purifier')),

            // Allow only what the in-house editor needs
            'HTML.Allowed' => implode(',', [
                'p[style]',
                'br',
                'b','strong',
                'i','em','u',
                'a[href|title|rel|target]',
                'ul','ol','li',
                'span[style]',
                'img[width|height|alt|src]',
                'h2','h3','h4','h5',
                'blockquote',
            ]),

            // Minimal CSS
            'CSS.AllowedProperties' =>
                'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align',

            // Formatting helpers
            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.RemoveEmpty'   => true,

            // Link attributes
            'Attr.AllowedFrameTargets' => ['_blank','_self','_parent','_top'],
            'Attr.AllowedRel'          => ['nofollow','noopener','noreferrer','ugc','sponsored'],

            // URL schemes
            'URI.AllowedSchemes' => ['http','https'],
        ],

        // Optional profiles
        'test' => [
            'Attr.EnableID' => 'true',
        ],

        'youtube' => [
            'HTML.SafeIframe'      => 'true',
            'URI.SafeIframeRegexp' => "%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/)%",
        ],

        // Custom HTML5-ish elements you had before
        'custom_definition' => [
            'id'    => 'html5-definitions',
            'rev'   => 1,
            'debug' => false,

            'elements' => [
                // Sections
                ['section', 'Block', 'Flow', 'Common'],
                ['nav',     'Block', 'Flow', 'Common'],
                ['article', 'Block', 'Flow', 'Common'],
                ['aside',   'Block', 'Flow', 'Common'],
                ['header',  'Block', 'Flow', 'Common'],
                ['footer',  'Block', 'Flow', 'Common'],

                // Grouping
                ['address',    'Block',  'Flow', 'Common'],
                ['hgroup',     'Block',  'Required: h1 | h2 | h3 | h4 | h5 | h6', 'Common'],
                ['figure',     'Block',  'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow', 'Common'],
                ['figcaption', 'Inline', 'Flow', 'Common'],

                // Media
                ['video',  'Block', 'Optional: (source, Flow) | (Flow, source) | Flow', 'Common', [
                    'src'      => 'URI',
                    'type'     => 'Text',
                    'width'    => 'Length',
                    'height'   => 'Length',
                    'poster'   => 'URI',
                    'preload'  => 'Enum#auto,metadata,none',
                    'controls' => 'Bool',
                ]],
                ['source', 'Block', 'Flow', 'Common', [
                    'src'  => 'URI',
                    'type' => 'Text',
                ]],

                // Text-level
                ['s',    'Inline', 'Inline', 'Common'],
                ['var',  'Inline', 'Inline', 'Common'],
                ['sub',  'Inline', 'Inline', 'Common'],
                ['sup',  'Inline', 'Inline', 'Common'],
                ['mark', 'Inline', 'Inline', 'Common'],
                ['wbr',  'Inline', 'Empty',  'Core'],

                // Edits
                ['ins', 'Block', 'Flow', 'Common', ['cite' => 'URI', 'datetime' => 'CDATA']],
                ['del', 'Block', 'Flow', 'Common', ['cite' => 'URI', 'datetime' => 'CDATA']],
            ],

            'attributes' => [
                ['iframe', 'allowfullscreen', 'Bool'],
                ['table',  'height', 'Text'],
                ['td',     'border', 'Text'],
                ['th',     'border', 'Text'],
                ['tr',     'width',  'Text'],
                ['tr',     'height', 'Text'],
                ['tr',     'border', 'Text'],
            ],
        ],

        'custom_attributes' => [
            ['a', 'target', 'Enum#_blank,_self,_target,_top'],
        ],
        'custom_elements' => [
            ['u', 'Inline', 'Inline', 'Common'],
        ],
    ],
];
