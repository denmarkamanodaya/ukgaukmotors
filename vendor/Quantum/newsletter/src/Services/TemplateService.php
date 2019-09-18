<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : TemplateService.php
 **/

namespace Quantum\newsletter\Services;


use File;
use Flash;
use Quantum\newsletter\Models\Newsletter;
use Quantum\newsletter\Models\NewsletterTemplates;
use Storage;

class TemplateService
{
    public function getAllTemplates($template_type="template")
    {
        $templates = NewsletterTemplates::orderBY('title', 'ASC')->where('template_type', $template_type)->paginate(20);
        return $templates;
    }

    public function createTemplate($request)
    {
        $template = NewsletterTemplates::create($request->all());
        $this->writeTemplate($template);
        flash('Template has been created.')->success();
        \Activitylogger::log('Admin - Created Newsletter Template: '.$template->title, $template);
    }

    public function getTemplateBySlug($slug, $template_type="template")
    {
        $load = ['newsletters'];
        $newsletter = NewsletterTemplates::with($load)->where('slug', $slug)->where('template_type', $template_type)->firstOrFail();
        return $newsletter;
    }

    public function getTemplateById($id, $template_type="template")
    {
        $load = ['newsletters'];
        $newsletter = NewsletterTemplates::with($load)->where('id', $id)->where('template_type', $template_type)->firstOrFail();
        return $newsletter;
    }

    public function getAllTemplatesList($template_type="template", $except=null)
    {
        $templates = NewsletterTemplates::orderBY('id', 'ASC')->where('template_type', $template_type)->pluck('title', 'id');

        return $templates;
    }

    public function updateTemplate($request, $slug)
    {
        $template = $this->getTemplateBySlug($slug, $request->template_type);
        if($template->template_type == 'theme' && $template->id == 1)
        {
            flash('This is the default theme, it can not be edited.')->success();
            return;
        }
        $template->update($request->all());
        if($request->template_type == 'theme')
        {
            $this->writeTemplate($template);
            flash('Theme has been updated.')->success();
            \Activitylogger::log('Admin - Updated Newsletter Theme : '.$template->title, $template);
        }
        if($request->template_type == 'template')
        {
            flash('Template has been updated.')->success();
            \Activitylogger::log('Admin - Updated Newsletter Template : '.$template->title, $template);
        }
    }

    public function deleteTemplate($slug, $template_type="template")
    {
        $template = $this->getTemplateBySlug($slug, $template_type);

        if($template_type == 'theme')
        {
            if($template->id == 1)
            {
                flash('This is the default theme, it can not be deleted.')->success();
                return;
            }
            Newsletter::where('newsletter_templates_id', $template->id)->update(['newsletter_templates_id' => 1]);
            File::delete(base_path('resources/views/emails/'.$template->slug.'.blade.php'));
            flash('Theme has been updated.')->success();
            \Activitylogger::log('Admin - Deleted Newsletter Theme : '.$template->title, $template);
        }
        if($template_type == 'template')
        {
            flash('Template has been updated.')->success();
            \Activitylogger::log('Admin - Deleted Newsletter Template : '.$template->title, $template);
        }

        $template->delete();

    }

    private function writeTemplate($template)
    {
        $filePath = base_path('resources/views/emails/'.$template->slug.'.blade.php');
        $template->content = str_replace('[mailcontent]', '{!! $content_html !!}', $template->content);
        File::put($filePath, $template->content);
        @chmod($filePath,0777);
    }


}