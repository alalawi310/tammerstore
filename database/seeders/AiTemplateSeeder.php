<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Template;

class AiTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $templates = [
            [
                'template_name'=>'name',
                'prompt'=>'"Create creative product names:  ##description## \n\nSeed words: ##keywords## \n\n" in comma seprate and no number',
                'module'=>'products',
                'field_json'=>'{"field":[{"label":"Seed words","placeholder":"e.g.  fast, healthy, compact","field_type":"text_box","field_name":"keywords"},{"label":"Product Description","placeholder":"e.g. Provide product details","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=>'0',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'template_name'=>'description',
                'prompt'=>'Write a long creative product description for: ##title##',
                'module'=>'products',
                'field_json'=>'{"field":[{"label":"Product name","placeholder":"e.g. VR, Honda","field_type":"text_box","field_name":"title"},{"label":"Audience","placeholder":"e.g. Women, Aliens","field_type":"text_box","field_name":"audience"},{"label":"Product Description","placeholder":"e.g. VR is an innovative device that can allow you to be part of virtual world","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=>'1',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ],
            [
                'template_name'=>'name',
                'prompt'=>'give catchy only name of category  for : ##keywords##  without icon or emojis',
                'module'=>'category',
                'field_json'=>'{"field":[{"label":"Seed words","placeholder":"e.g.  fast, healthy, compact","field_type":"text_box","field_name":"keywords"}]}',
                'is_tone'=>'0',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ],
            [
                'template_name'=>'coupon_name',
                'prompt'=>'give 1 catchy only name of Offer or discount Coupon  for : ##keywords## ',
                'module'=>'coupan',
                'field_json'=>'{"field":[{"label":"Seed words","placeholder":"e.g.  fast, healthy, compact","field_type":"text_box","field_name":"keywords"}]}',
                'is_tone'=>'0',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ],
            [
                'template_name'=>'name',
                'prompt'=>'I am starting a #### shipping service and need a unique name that reflects style, efficiency, and reliability. Can you help me come up with some creative options?',
                'module'=>'shipping',
                'field_json'=>'{"field":[{"label":"What do want to ship? ","placeholder":"e.g.  Cloth, Electronics,","field_type":"text_box","field_name":"keywords"}]}',
                'is_tone'=>'0',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ],
            [
                'template_name'=>'name',
                'prompt'=>'please suggest only name for advance level or extraordinary page  which i can use in my "##description##" website',
                'module'=>'custom page',
                'field_json'=>'{"field":[{"label":"Describe your website ","placeholder":"e.g. Describe your website details ","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=>'0',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ],
            [
                'template_name'=>'content',
                'prompt'=>'please generate content for "##title##" page  which i can use in my "##description##"\n\nTone of generated text must be:\n ##tone_language## \n\n website',
                'module'=>'custom page',
                'field_json'=>'{"field":[{"label":"What is your Page title?","placeholder":"e.g. Outstanding achievements,contact us","field_type":"text_box","field_name":"title"},{"label":"Describe your website ","placeholder":"e.g. Describe your website details ","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=>'1',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ],
            [
                'template_name'=>'title',
                'prompt'=>'Generate blog titles for:\n\n ##description## \n\n',
                'module'=>'blog',
                'field_json'=>'{"field":[{"label":"What is your blog post is about?","placeholder":"e.g. Describe your blog post","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=>'0',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ],
            [
                'template_name'=>'content',
                'prompt'=>'"please generate detailed blog for this title :##description##\n\nTone of generated text must be:\n ##tone_language## \n\n for your business with intro & conclusion"',
                'module'=>'blog',
                'field_json'=>'{"field":[{"label":"What is your blog post is about?","placeholder":"e.g. Describe your blog post","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=>'1',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ],
            [
                'template_name'=>'name',
                'prompt'=>'please suggest subscription plan  name  for this  :  ##description##  for my business',
                'module'=>'plan',
                'field_json'=>'{"field":[{"label":"What is your plan about?","placeholder":"e.g. Describe your plan details ","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=>'0',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ],
            [
                'template_name'=>'description',
                'prompt'=>'please suggest subscription plan  description  for this  :  "##title##\n\nTone of generated text must be:\n ##tone_language## \n\n  for my business',
                'module'=>'plan',
                'field_json'=>'{"field":[{"label":"What is your plan title?","placeholder":"e.g. Pro Resller,Exclusive Access","field_type":"text_box","field_name":"title"}]}',
                'is_tone'=>'1',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ],
            [
                'template_name'=>'cookie_title',
                'prompt'=>'please suggest me cookie title for this ##description## website   which i can use in my website cookie',
                'module'=>'cookie',
                'field_json'=>'{"field":[{"label":"Website name or info","placeholder":"e.g. example website ","field_type":"textarea","field_name":"title"}]}',
                'is_tone'=>'0',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ],
            [
                'template_name'=>'strictly_cookie_title',
                'prompt'=>'please suggest me only Strictly Cookie Title for this ##description##  website which i can use in my website cookie',
                'module'=>'cookie',
                'field_json'=>'{"field":[{"label":"Website name or info","placeholder":"e.g. example website ","field_type":"textarea","field_name":"title"}]}',
                'is_tone'=>'0',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ],
            [
                'template_name'=>'cookie_description',
                'prompt'=>'please suggest me  Cookie description for this cookie title "##title##"\n\nTone of generated text must be:\n ##tone_language## \n\n   which i can use in my website cookie',
                'module'=>'cookie',
                'field_json'=>'{"field":[{"label":"Cookie Title ","placeholder":"e.g. example website ","field_type":"text_box","field_name":"title"}]}',
                'is_tone'=>'1',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ],
            [
                'template_name'=>'strictly_cookie_description',
                'prompt'=>'please suggest me Strictly Cookie description for this Strictly cookie title "##title## "\n\nTone of generated text must be:\n ##tone_language## \n\n   which i can use in my website cookie',
                'module'=>'cookie',
                'field_json'=>'{"field":[{"label":"Strictly Cookie Title ","placeholder":"e.g. example website ","field_type":"text_box","field_name":"title"}]}',
                'is_tone'=>'1',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ],
            [
                'template_name'=>'more_information_description',
                'prompt'=>'I need assistance in crafting compelling content for my ##web_name##\n\nTone of generated text must be:\n ##tone_language## \n\n website Contact Us page of my website. The page should provide relevant information to users, encourage them to reach out for inquiries, support, and feedback, and reflect the unique value proposition of my business.',
                'module'=>'cookie',
                'field_json'=>'{"field":[{"label":"Websit Name","placeholder":"e.g. example website ","field_type":"text_box","field_name":"web_name"}]}',
                'is_tone'=>'1',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ],
            [
                'template_name'=>'metadesc',
                'prompt'=>'"Write SEO meta description for:\n\n ##description## \n\nWebsite name is:\n ##title## \n\nSeed words:\n ##keywords## \n\nTone of generated text must be:\n ##tone_language## \n\n"',
                'module'=>'meta',
                'field_json'=>'{"field":[{"label":"Website Name","placeholder":"e.g. Amazon, Google","field_type":"text_box","field_name":"title"},{"label":"Website Description","placeholder":"e.g. Describe what your website or business do","field_type":"textarea","field_name":"description"},{"label":"Keywords","placeholder":"e.g.  cloud services, databases","field_type":"text_box","field_name":"keywords"}]}',
                'is_tone'=>'1',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ],
            [
                'template_name'=>'metakeyword',
                'prompt'=>'"Write SEO meta title for:\n\n ##description## \n\nWebsite name is:\n ##title## \n\nSeed words:\n ##keywords## \n\n"',
                'module'=>'meta',
                'field_json'=>'{"field":[{"label":"Website Name","placeholder":"e.g. Amazon, Google","field_type":"text_box","field_name":"title"},{"label":"Website Description","placeholder":"e.g. Describe what your website or business do","field_type":"textarea","field_name":"description"},{"label":"Keywords","placeholder":"e.g.  cloud services, databases","field_type":"text_box","field_name":"keywords"}]}',
                'is_tone'=>'0',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ],
            [
                'template_name'=>'storename',
                'prompt'=>'"Create creative Store names: ##description## \n\nSeed words: ##keywords## \n\n"',
                'module'=>'store',
                'field_json'=>'{"field":[{"label":"Seed words","placeholder":"e.g.  Store","field_type":"text_box","field_name":"keywords"},{"label":"Store Description","placeholder":"e.g. Store product details","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=>'0',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ]
        ];

        $templateQuery = Template::query();
        foreach($templates as $template) {
            if (is_array($template)) {
                $template = (object) $template;
            }
            $exits = (clone $templateQuery)->where('template_name', $template->template_name)->where('module', $template->module)->first();
            if (!$exits) {
                (clone $templateQuery)->create([
                    'template_name' => $template->template_name,
                    'prompt'        => $template->prompt,
                    'module'        => $template->module,
                    'field_json'    => $template->field_json,
                    'is_tone'       => $template->is_tone,
                ]);
            }
        }
    }
}
